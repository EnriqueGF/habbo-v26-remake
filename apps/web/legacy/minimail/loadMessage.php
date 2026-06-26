<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright &copy; 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

if($bypass != "true"){
include('../core.php');
include('../includes/session.php');
}

if(isset($_GET['messageId'])){
	$mesid = $_GET['messageId'];
	$label = $_GET['label'];
	$sql = mysql_query("SELECT * FROM cms_minimail WHERE id = '".$mesid."'");
	$row = mysql_fetch_assoc($sql);
	$sql2 = mysql_query("SELECT * FROM users WHERE id = '".$row['senderid']."'");
	$senderrow = mysql_fetch_assoc($sql2);
	$sql3 = mysql_query("SELECT * FROM users WHERE id = '".$row['to_id']."'");
	$torow = mysql_fetch_assoc($sql3); ?>
	<ul class="message-headers">
				<li><a href="#" class="report" title="Denunciar por ofensivo"></a></li>
			<li><b>Asunto:</b> <?php echo $row['subject']; ?></li>
			<li><b>De:</b> <?php echo $senderrow['name']; ?></li>
			<li><b>Para:</b> <?php echo $torow['name']; ?></li>

		</ul>
		<div class="body-text"><?php echo HoloText($row['message'],false ,true); ?><br></div>
		<div class="reply-controls">
			<div>
				<div class="new-buttons clearfix">
				<?php if($row['conversationid'] != '0'){ ?>
					<a href="#" class="related-messages" id="rel-<?php echo $row['conversationid']; ?>" title="Mostrar conversaci&oacute;n completa"></a>
				<?php } ?>
				<?php if($label == "trash"){ ?>
					<a href="#" class="new-button undelete"><b>Recuperar</b><i></i></a>
					<a href="#" class="new-button red-button delete"><b>Eliminar</b><i></i></a>
				<?php } elseif($label == "inbox") { ?>
					<a href="#" class="new-button red-button delete"><b>Eliminar</b><i></i></a>
					<a href="#" class="new-button reply"><b>Responder</b><i></i></a>
				<?php } ?>
				</div>
			</div>
			<div style="display: none;">
				<textarea rows="5" cols="10" class="message-text"></textarea><br>
				<div class="new-buttons clearfix">
					<a href="#" class="new-button cancel-reply"><b>Cancelar</b><i></i></a>
					<a href="#" class="new-button preview"><b>Vista previa</b><i></i></a>

					<a href="#" class="new-button send-reply"><b>Enviar</b><i></i></a>
				</div>
			</div>
		</div>
	<?php if($label == "inbox"){ mysql_query("UPDATE cms_minimail SET read_mail = '1' WHERE id = '".$mesid."'");}
}
if(isset($_POST['label']) OR $bypass == "true"){
	$label = $_POST['label'];
	$start = $_POST['start'];
	$conversationid = $_POST['conversationId'];
	$unread = $_POST['unreadOnly'];
	if($bypass == "true"){
		$label = $page;
		$start = $startpage;
	}

	?>
		<a href="#" class="new-button compose"><b>Nuevo</b><i></i></a>
	<div class="clearfix labels nostandard">
		<ul class="box-tabs">
		<?php
		$sql = mysql_query("SELECT * FROM cms_minimail WHERE to_id = '".$my_id."' AND read_mail = '0'");
		$unreadmail = mysql_num_rows($sql);
		?>
			<li <?php if($label == "inbox"){ echo "class=\"selected\""; } ?>><a href="#" label="inbox">Bandeja<?php if($unreadmail <> 0){ echo " (".$unreadmail.")"; } ?></a><span class="tab-spacer"></span></li>
			<li <?php if($label == "sent"){ echo "class=\"selected\"";  } ?>><a href="#" label="sent">Enviados</a><span class="tab-spacer"></span></li>
			<li <?php if($label == "trash"){ echo "class=\"selected\""; } ?>><a href="#" label="trash">Papelera</a><span class="tab-spacer"></span></li>
		</ul>

	</div>
	<div id="message-list" class="label-<?php echo $label; ?>">
	<div class="new-buttons clearfix">
		<div class="labels inbox-refresh"><a href="#" class="new-button green-button" label="inbox" style="float: left; margin: 0"><b>Actualizar</b><i></i></a></div>
	</div>

	<div style="clear: both; height: 1px"></div>
<?php if($label == "inbox"){ ?>
	<div class="navigation">
		<div class="unread-selector"><input type="checkbox" class="unread-only" <?php if($unread == "true"){ echo "checked"; } ?>/> no le&iacute;dos</div>
			<?php
			$sql1 = mysql_query("SELECT * FROM cms_minimail WHERE to_id = '".$my_id."' AND deleted = '0'");
			$allmail = mysql_num_rows($sql1);
			$sql1 = mysql_query("SELECT * FROM cms_minimail WHERE to_id = '".$my_id."' AND deleted = '0' AND read_mail = '0'");
			$unreadmail = mysql_num_rows($sql1);
			if($unread == "true"){
				$allnum = $unreadmail;
			} else {
				$allnum = $allmail;
			}
			if($start != null){
				$offset = $start;
				$startnum = $start + 1;
				$endnum = $start + 10;
				if($endnum > $allnum){ $endnum = $allnum; }
			} else {
				$sql1 = mysql_query("SELECT * FROM cms_minimail WHERE to_id = '".$my_id."' AND deleted = '0' LIMIT 10");
				$endnum = mysql_num_rows($sql1);
				$offset = "0";
				$startnum = "1";
			}
			$var1 = " <a href=\"#\" class=\"newer\">M&aacute;s nuevos</a> ";
			$var2 = " ".$startnum." - ".$endnum." de ".$allnum." ";
			$var3 = " <a href=\"#\" class=\"older\">M&aacute;s antiguos</a> ";
			$var4 = " <a href=\"#\" class=\"newest\">Los m&aacute;s nuevos</a> ";
			$var5 = "<!-- <a href=\"#\" class=\"oldest\">Oldest</a> -->";
			$totalpages = ceil($allnum / 10);
			if($endnum != $allnum && $startnum != 1){
				$maillist = $var1.$var2.$var3;
			} elseif($endnum != $allnum && $startnum == 1){
				$maillist = $var2.$var3;
			} elseif($endnum == $allnum && $startnum != 1){
				$maillist = $var1.$var2;
			} elseif($endnum == $allnum && $startnum == 1){
				$maillist = $var2;
			}
			if($startnum + 20 < $allnum && $endnum <> $allnum){
				$maillist = $maillist.$var5;
			}
			if($startnum - 20 > 0){
				$maillist = $var4.$maillist;
			}
			$maillist = "<p>".$maillist."</p>";
			?>
		<?php if($allmail == 0){ ?>
			<p class="no-messages">
				   No hay mensajes
			</p>
		<?php } elseif($unreadmail == "0" && $unread == "true"){ ?>
			<p class="no-messages">
				   No hay mensajes sin leer
			</p>
		<?php } ?>
			<div class="progress"></div>
			<?php if($unread == "true"){
				if($unreadmail <> 0){ echo $maillist; }
				} else {
				if($allmail <> 0){ echo $maillist; }
				} ?>
	</div>

	<?php
			   $i = 0;
			   if($unread == "true"){
			   	$getem = mysql_query("SELECT * FROM cms_minimail WHERE to_id = '".$my_id."' AND deleted = '0' AND read_mail = '0' ORDER BY ID DESC LIMIT 10 OFFSET ".$offset) or die(mysql_error());
			   } else {
			   	$getem = mysql_query("SELECT * FROM cms_minimail WHERE to_id = '".$my_id."' AND deleted = '0' ORDER BY id DESC LIMIT 10 OFFSET ".$offset) or die(mysql_error());
			   }
			   while ($row = mysql_fetch_assoc($getem)) {
					   $i++;

					   if($row['read_mail'] == 0){
						   $read = "unread";
					   } else {
						   $read = "read";
					   }

					   $mysql = mysql_query("SELECT * FROM users WHERE id = '".$row['senderid']."' LIMIT 1");
					   $senderrow = mysql_fetch_assoc($mysql);
					   $figure = "/habbo-imaging/avatarimage?figure=".$senderrow['figure']."&size=s&direction=9&head_direction=2&gesture=sml";

	printf("	<div class=\"message-item %s \" id=\"msg-%s\">
			<div class=\"message-preview\" status=\"%s\">

				<span class=\"message-tstamp\" isotime=\"%s\" title=\"%s\">
					%s
				</span>
				<img src=\"%s\" />
				<span class=\"message-sender\" title=\"%s\">%s</span>

				<span class=\"message-subject\" title=\"%s\">&ldquo;%s&rdquo;</span>
			</div>
			<div class=\"message-body\" style=\"display: none;\">
				<div class=\"contents\"></div>

				<div class=\"message-body-bottom\"></div>
			</div>
		</div>", $read, $row['id'], $read, $row['date'], $row['date'], $row['date'], $figure, $senderrow['name'], $senderrow['name'], $row['subject'], $row['subject']);
			   }
	?>

	<div class="navigation">
			<div class="progress"></div>

			<?php if($unread == "true"){
				if($unreadmail <> 0){ echo $maillist; }
				} else {
				if($allmail <> 0){ echo $maillist; }
				} ?>
	</div>

	</div>
	<?php } elseif($label == "sent"){ ?>
		<div class="navigation">
			<?php
			$sql1 = mysql_query("SELECT * FROM cms_minimail WHERE senderid = '".$my_id."'");
			$allmail = mysql_num_rows($sql1);
			$allnum = $allmail;
			if($start != null){
				$offset = $start;
				$startnum = $start + 1;
				$endnum = $start + 10;
				if($endnum > $allnum){ $endnum = $allnum; }
			} else {
				$sql1 = mysql_query("SELECT * FROM cms_minimail WHERE senderid = '".$my_id."' AND deleted = '0' LIMIT 10");
				$endnum = mysql_num_rows($sql1);
				$offset = "0";
				$startnum = "1";
			}
			$var1 = " <a href=\"#\" class=\"newer\">M&aacute;s nuevos</a> ";
			$var2 = " ".$startnum." - ".$endnum." de ".$allnum." ";
			$var3 = " <a href=\"#\" class=\"older\">M&aacute;s antiguos</a> ";
			$var4 = " <a href=\"#\" class=\"newest\">Los m&aacute;s nuevos</a> ";
			$var5 = "<!-- <a href=\"#\" class=\"oldest\">Oldest</a> -->";
			$totalpages = ceil($allnum / 10);
			if($endnum != $allnum && $startnum != 1){
				$maillist = $var1.$var2.$var3;
			} elseif($endnum != $allnum && $startnum == 1){
				$maillist = $var2.$var3;
			} elseif($endnum == $allnum && $startnum != 1){
				$maillist = $var1.$var2;
			} elseif($endnum == $allnum && $startnum == 1){
				$maillist = $var2;
			}
			if($startnum + 20 < $allnum && $endnum <> $allnum){
				$maillist = $maillist.$var5;
			}
			if($startnum - 20 > 0){
				$maillist = $var4.$maillist;
			}
			$maillist = "<p>".$maillist."</p>";
			?>
			<?php if($allmail == 0){ ?>
				<p class="no-messages">
					   No hay mensajes enviados
				</p>
			<?php } ?>
				<div class="progress"></div>

				<?php if($allmail <> 0){ echo $maillist; } ?>
		</div>
		<?php
				   $i = 0;
				   $getem = mysql_query("SELECT * FROM cms_minimail WHERE senderid = '".$my_id."' ORDER BY id DESC LIMIT 10 OFFSET ".$offset) or die(mysql_error());

				   while ($row = mysql_fetch_assoc($getem)) {
						   $i++;

						   if($row['read_mail'] == 0){
							   $read = "unread";
						   } else {
							   $read = "read";
						   }

						   $mysql = mysql_query("SELECT * FROM users WHERE id = '".$row['senderid']."' LIMIT 1");
						   $senderrow = mysql_fetch_assoc($mysql);
						   $figure = "/habbo-imaging/avatarimage?figure=".$senderrow['figure']."&size=s&direction=9&head_direction=2&gesture=sml";

		printf("	<div class=\"message-item %s \" id=\"msg-%s\">
				<div class=\"message-preview\" status=\"%s\">

					<span class=\"message-tstamp\" isotime=\"%s\" title=\"%s\">
						%s
					</span>
					<img src=\"%s\" />
					<span class=\"message-sender\" title=\"Para: %s\">Para: %s</span>

					<span class=\"message-subject\" title=\"%s\">&ldquo;%s&rdquo;</span>
				</div>
				<div class=\"message-body\" style=\"display: none;\">
					<div class=\"contents\"></div>

					<div class=\"message-body-bottom\"></div>
				</div>
			</div>", $read, $row['id'], $read, $row['date'], $row['date'], $row['date'], $figure, $senderrow['name'], $senderrow['name'], $row['subject'], $row['subject']);
				   }
		?>

		<div class="navigation">
				<div class="progress"></div>

				<?php if($allmail <> 0){ echo $maillist; } ?>
	</div>
	<?php } elseif($label == "trash"){ ?>
			<div class="trash-controls notice">
				Los mensajes de esta carpeta con m&aacute;s de 30 d&iacute;as se eliminan autom&aacute;ticamente. <a href="#" class="empty-trash">Vaciar papelera</a>

			</div>

		<div class="navigation">
			<?php
			$sql1 = mysql_query("SELECT * FROM cms_minimail WHERE to_id = '".$my_id."' AND deleted = '1'");
			$allmail = mysql_num_rows($sql1);
			$allnum = $allmail;
			if($start != null){
				$offset = $start;
				$startnum = $start + 1;
				$endnum = $start + 10;
				if($endnum > $allnum){ $endnum = $allnum; }
			} else {
				$sql1 = mysql_query("SELECT * FROM cms_minimail WHERE to_id = '".$my_id."' AND deleted = '1' LIMIT 10");
				$endnum = mysql_num_rows($sql1);
				$offset = "0";
				$startnum = "1";
			}
			$var1 = " <a href=\"#\" class=\"newer\">M&aacute;s nuevos</a> ";
			$var2 = " ".$startnum." - ".$endnum." de ".$allnum." ";
			$var3 = " <a href=\"#\" class=\"older\">M&aacute;s antiguos</a> ";
			$var4 = " <a href=\"#\" class=\"newest\">Los m&aacute;s nuevos</a> ";
			$var5 = "<!-- <a href=\"#\" class=\"oldest\">Oldest</a> -->";
			$totalpages = ceil($allnum / 10);
			if($endnum != $allnum && $startnum != 1){
				$maillist = $var1.$var2.$var3;
			} elseif($endnum != $allnum && $startnum == 1){
				$maillist = $var2.$var3;
			} elseif($endnum == $allnum && $startnum != 1){
				$maillist = $var1.$var2;
			} elseif($endnum == $allnum && $startnum == 1){
				$maillist = $var2;
			}
			if($startnum + 20 < $allnum && $endnum <> $allnum){
				$maillist = $maillist.$var5;
			}
			if($startnum - 20 > 0){
				$maillist = $var4.$maillist;
			}
			$maillist = "<p>".$maillist."</p>";
			?>
			<?php if($allmail == 0){ ?>
				<p class="no-messages">
					   No hay mensajes eliminados
				</p>
			<?php } ?>
				<div class="progress"></div>

				<?php if($allmail <> 0){ echo $maillist; } ?>
		</div>
		<?php
				   $i = 0;
				   $getem = mysql_query("SELECT * FROM cms_minimail WHERE to_id = '".$my_id."' AND deleted = '1' ORDER BY ID DESC LIMIT 10 OFFSET ".$offset) or die(mysql_error());

				   while ($row = mysql_fetch_assoc($getem)) {
						   $i++;

						   if($row['read_mail'] == 0){
							   $read = "unread";
						   } else {
							   $read = "read";
						   }

						   $mysql = mysql_query("SELECT * FROM users WHERE id = '".$row['senderid']."' LIMIT 1");
						   $senderrow = mysql_fetch_assoc($mysql);
						   $figure = "/habbo-imaging/avatarimage?figure=".$senderrow['figure']."&size=s&direction=9&head_direction=2&gesture=sml";

		printf("	<div class=\"message-item %s \" id=\"msg-%s\">
				<div class=\"message-preview\" status=\"%s\">

					<span class=\"message-tstamp\" isotime=\"%s\" title=\"%s\">
						%s
					</span>
					<img src=\"%s\" />
					<span class=\"message-sender\" title=\"%s\">%s</span>

					<span class=\"message-subject\" title=\"%s\">&ldquo;%s&rdquo;</span>
				</div>
				<div class=\"message-body\" style=\"display: none;\">
					<div class=\"contents\"></div>

					<div class=\"message-body-bottom\"></div>
				</div>
			</div>", $read, $row['id'], $read, $row['date'], $row['date'], $row['date'], $figure, $senderrow['name'], $senderrow['name'], $row['subject'], $row['subject']);
				   }
		?>

		<div class="navigation">
				<div class="progress"></div>

				<?php if($allmail <> 0){ echo $maillist; } ?>
	</div>

	</div>
	<?php } elseif($label == "conversation"){ ?>
			<div class="trash-controls notice">
				Est&aacute;s leyendo una conversaci&oacute;n. Haz clic en las pesta&ntilde;as de arriba para volver a tus carpetas.

			</div>
		<?php $id = $_POST['messageId'];
		$conid = $_POST['conversationId']; ?>

		<div class="navigation">
			<?php
			$sql1 = mysql_query("SELECT * FROM cms_minimail WHERE conversationid = '".$conid."' AND deleted = '0'");
			$allmail = mysql_num_rows($sql1);
			$allnum = $allmail;
			if($start != null){
				$offset = $start;
				$startnum = $start + 1;
				$endnum = $start + 10;
				if($endnum > $allnum){ $endnum = $allnum; }
			} else {
				$sql1 = mysql_query("SELECT * FROM cms_minimail WHERE conversationid = '".$conid."' AND deleted = '0' LIMIT 10");
				$endnum = mysql_num_rows($sql1);
				$offset = "0";
				$startnum = "1";
			}
			$var1 = " <a href=\"#\" class=\"newer\">M&aacute;s nuevos</a> ";
			$var2 = " ".$startnum." - ".$endnum." de ".$allnum." ";
			$var3 = " <a href=\"#\" class=\"older\">M&aacute;s antiguos</a> ";
			$var4 = " <a href=\"#\" class=\"newest\">Los m&aacute;s nuevos</a> ";
			$var5 = "<!-- <a href=\"#\" class=\"oldest\">Oldest</a> -->";
			$totalpages = ceil($allnum / 10);
			if($endnum != $allnum && $startnum != 1){
				$maillist = $var1.$var2.$var3;
			} elseif($endnum != $allnum && $startnum == 1){
				$maillist = $var2.$var3;
			} elseif($endnum == $allnum && $startnum != 1){
				$maillist = $var1.$var2;
			} elseif($endnum == $allnum && $startnum == 1){
				$maillist = $var2;
			}
			if($startnum + 20 < $allnum && $endnum <> $allnum){
				$maillist = $maillist.$var5;
			}
			if($startnum - 20 > 0){
				$maillist = $var4.$maillist;
			}
			$maillist = "<p>".$maillist."</p>";
			?>
			<?php if($allmail == 0){ ?>
				<p class="no-messages">
					   No hay mensajes en la conversaci&oacute;n
				</p>
			<?php } ?>
				<div class="progress"></div>

				<?php if($allmail <> 0){ echo $maillist; } ?>
		</div>
		<?php
				   $i = 0;
				   $getem = mysql_query("SELECT * FROM cms_minimail WHERE conversationid = '".$conid."' AND deleted = '0' ORDER BY id DESC LIMIT 10 OFFSET ".$offset) or die(mysql_error());

				   while ($row = mysql_fetch_assoc($getem)) {
						   $i++;

						   if($row['read_mail'] == 0){
							   $read = "unread";
						   } else {
							   $read = "read";
						   }

						   $mysql = mysql_query("SELECT * FROM users WHERE id = '".$row['senderid']."' LIMIT 1");
						   $senderrow = mysql_fetch_assoc($mysql);
						   $figure = "/habbo-imaging/avatarimage?figure=".$senderrow['figure']."&size=s&direction=9&head_direction=2&gesture=sml";

		printf("	<div class=\"message-item %s \" id=\"msg-%s\">
				<div class=\"message-preview\" status=\"%s\">

					<span class=\"message-tstamp\" isotime=\"%s\" title=\"%s\">
						%s
					</span>
					<img src=\"%s\" />
					<span class=\"message-sender\" title=\"%s\">%s</span>

					<span class=\"message-subject\" title=\"%s\">&ldquo;%s&rdquo;</span>
				</div>
				<div class=\"message-body\" style=\"display: none;\">
					<div class=\"contents\"></div>

					<div class=\"message-body-bottom\"></div>
				</div>
			</div>", $read, $row['id'], $read, $row['date'], $row['date'], $row['date'], $figure, $senderrow['name'], $senderrow['name'], $row['subject'], $row['subject']);
				   }
		?>

		<div class="navigation">
				<div class="progress"></div>

				<?php if($allmail <> 0){ echo $maillist; } ?>
	</div>
	<?php }
	if($bypass == "true" && $message != ""){ ?><div style="opacity: 1;" class="notification"><?php echo $message; ?></div></div><?php }
}
?>