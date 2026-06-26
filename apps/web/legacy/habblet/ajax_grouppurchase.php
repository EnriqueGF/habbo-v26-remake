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

include('../core.php');
if(function_exists(SendMUSData) !== true){ include('../includes/mus.php'); }

if(!session_is_registered(username)){ echo "<p>\nPor favor, inicia sesi&oacute;n primero.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>Hecho</b><i></i></a>\n</p>"; exit; }

$do = $_GET['do'];

	// Make sure the user meets the requirements to buy a group. If not, this part
	// should cut off the script.
	
	if(getContent('allow-group-purchase') !== "1"){

			echo "<p id=\"purchase-result-error\">No se pudo comprar el grupo. Int&eacute;ntalo m&aacute;s tarde.</p>\n<div id=\"purchase-group-errors\">\n<p>\nLa compra de grupos ha sido deshabilitada por la direcci&oacute;n del Hotel. Int&eacute;ntalo m&aacute;s tarde.<br />\n</p>\n</div>\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>Hecho</b><i></i></a>\n</p>\n<div class=\"clear\"></div>"; exit;

	} elseif($myrow['credits'] < 20){

			echo "<p id=\"purchase-result-error\">No se pudo comprar el grupo. Int&eacute;ntalo m&aacute;s tarde.</p>\n<div id=\"purchase-group-errors\">\n<p>\nNo tienes suficientes cr&eacute;ditos. <a href=\"credits.php\">&iexcl;Consigue m&aacute;s aqu&iacute;!</a><br />\n</p>\n</div>\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>Hecho</b><i></i></a>\n</p>\n<div class=\"clear\"></div>"; exit;

	} else {

			$groups_owned = mysql_evaluate("SELECT COUNT(*) FROM groups_details WHERE ownerid = '".$my_id."' LIMIT 10");

			if($groups_owned > 10){
				echo "<p id=\"purchase-result-error\">No se pudo comprar el grupo. Int&eacute;ntalo m&aacute;s tarde.</p>\n<div id=\"purchase-group-errors\">\n<p>\nHas alcanzado el n&uacute;mero m&aacute;ximo de grupos <i>en propiedad</i> por usuario (3).<br />\n</p>\n</div>\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>Hecho</b><i></i></a>\n</p>\n<div class=\"clear\"></div>"; exit;
			}

	}


	// The buy part. If the script has not been cut off yet, we should be ready to go.

	if(empty($do) || $do !== "purchase_confirmation"){

		echo "<p>\n<img src='./habbo-imaging/badge-fill/b0503Xs09114s05013s05015.gif' border='0' align='left'>Aqu&iacute; puedes crear tu propio grupo. Los grupos solo cuestan <b>20</b> cr&eacute;ditos.\n</p>\n\n<p>\n<b>Nombre del grupo</b><br /><input type='text' name='name' id='group_name' value='' length='10' maxlength='25'>\n</p>\n\n<p>\n<b>Descripci&oacute;n del grupo</b><br />\n<textarea name='description' id='group_description' maxlength='200'></textarea>\n</p>\n\n<p>\n&iexcl;Podr&aacute;s modificar estos datos cuando hayas creado el grupo!\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.confirm(); return false;\"><b>Comprar</b><i></i></a>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>Volver</b><i></i></a>\n</p>"; exit;

	} elseif($do == "purchase_confirmation"){

		$group_name = trim($_POST['name']);
		$group_desc = trim($_POST['description']);

		if(empty($group_name) || empty($group_desc)){

			echo "<p>\nPor favor, no dejes ning&uacute;n campo vac&iacute;o.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); GroupPurchase.open(); return false;\"><b>Volver</b><i></i></a>\n</p>"; exit;

		} else {

			if(strlen($group_name > 25) && !is_numeric($group_name)){

				echo "<p>\n&iexcl;El nombre del grupo es demasiado largo!\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); GroupPurchase.open(); return false;\"><b>Volver</b><i></i></a>\n</p>"; exit;

			} elseif(strlen($group_desc > 200) && !is_numeric($group_desc)){

				echo "<p>\n&iexcl;La descripci&oacute;n del grupo es demasiado larga!\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); GroupPurchase.open(); return false;\"><b>Volver</b><i></i></a>\n</p>"; exit;

			} else {

				$check = mysql_query("SELECT id FROM groups_details WHERE name = '".$group_name."' LIMIT 1") or die(mysql_error());
				$already_exists = mysql_num_rows($check);

				if($already_exists > 0){

					echo "<p>\nEste grupo ya existe\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); GroupPurchase.open(); return false;\"><b>Volver</b><i></i></a>\n</p>";

				} else {

					$orname = $group_name;
					$group_name = FilterText($orname);
					$group_desc = FilterText($group_desc);

					mysql_query("INSERT INTO groups_details (name,description,ownerid,created,badge,type) VALUES ('".$group_name."','".$group_desc."','".$my_id."','".$date_full."','b0503Xs09114s05013s05015','0')") or die(mysql_error());

					$check = mysql_query("SELECT id FROM groups_details WHERE ownerid = '".$my_id."' ORDER BY id DESC LIMIT 1") or die(mysql_error());
					$row = mysql_fetch_assoc($check);
					$group_id = $row['id'];

					mysql_query("INSERT INTO groups_memberships (userid,groupid,member_rank,is_current) VALUES ('".$my_id."','".$group_id."','2','0')") or die(mysql_error());
					mysql_query("UPDATE users SET credits = credits - 20 WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
					mysql_query("INSERT INTO cms_transactions (userid,descr,date,amount) VALUES ('".$my_id."','Group purchase','".$date_full."','-20')") or die(mysql_error());
					
					@SendMUSData('UPRC' . $my_id);

					echo "<p>\n<b>&iexcl;Grupo comprado!</b><br /><br /><img src='./habbo-imaging/badge-fill/b0503Xs09114s05013s05015.gif' border='0' align='left'>&iexcl;Enhorabuena! Eres el creador/a de <b>".HoloText($orname)."</b>.<br /><br />Haz clic <a href='group_profile.php?id=".$group_id."'>aqu&iacute;</a> para ir a la p&aacute;gina principal de tu grupo. O cierra haciendo clic en el bot&oacute;n Salir.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>Salir</b><i></i></a>\n</p>";

				}

			}

		}

	} else {

		echo "<p>\nSe ha producido un error desconocido. Int&eacute;ntalo de nuevo en unos minutos.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>OK</b><i></i></a>\n</p>";

	}

?>



