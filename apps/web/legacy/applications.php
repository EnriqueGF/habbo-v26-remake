<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright � 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

$allow_guests = false;

include('core.php');
include('includes/session.php');

$pagename = "Applications";
$pageid = "apply";

include('templates/community/subheader.php');
include('templates/community/header.php');

?>

<?php if(!isset($_GET['id'])) { ?>
<div id="container">
	<div id="content">
    <div id="column1" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix brown ">

							<h2 class="title">Solicitudes
							</h2>
						<div class="habblet box-content">
						<?php 	$sql = mysql_query("SELECT id,name FROM cms_application_forms WHERE enabled='1' AND deleted='0' ORDER BY name ASC");
								$sqll = mysql_query("SELECT id,name FROM cms_application_forms WHERE enabled='0' AND deleted='0' ORDER BY name ASC"); 						?>
&iquest;Quieres ser miembro del staff? A continuaci&oacute;n puedes ver las solicitudes abiertas y cerradas. En total hay <b><?php echo mysql_num_rows($sql); ?></b> solicitudes abiertas y <b><?php echo mysql_num_rows($sqll); ?></b> cerradas. <i>Si no hay solicitudes abiertas, int&eacute;ntalo m&aacute;s tarde.</i><br><br><b>Solicitudes abiertas:</b><br><?php
	$sql = mysql_query("SELECT id,name FROM cms_application_forms WHERE enabled='1' AND deleted='0' ORDER BY name ASC");
	if(mysql_num_rows($sql) < 1) { echo "<i>&iexcl;No hay solicitudes abiertas!</i>"; }
	while($row = mysql_fetch_assoc($sql)) {
	echo "<a href='applications.php?id=".$row['id']."'>".HoloText($row['name'])."</a><br>";
	 } ?>
	 <br><b>Solicitudes cerradas:</b><br><?php
	$sql = mysql_query("SELECT id,name FROM cms_application_forms WHERE enabled='0' AND deleted='0' ORDER BY name ASC");
	if(mysql_num_rows($sql) < 1) { echo "<i>&iexcl;No hay solicitudes cerradas!</i><br>"; }
	while($row = mysql_fetch_assoc($sql)) {
	echo "".HoloText($row['name'])."<br>";
	 } ?><br>Saludos,<br>El equipo de staff
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix blue ">
	
							<h2 class="title">&iquest;Quieres ser miembro del staff?
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p>Si quieres ser miembro del equipo de staff, rellena una solicitud. La leeremos y te diremos si eres aceptado. &iexcl;Es muy sencillo y solo lleva unos minutos!</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div>


<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix green ">
	
							<h2 class="title">&iexcl;Aceptado! :D
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p>&iexcl;Si eres aceptado, enhorabuena! Pronto recibir&aacute;s tu placa y permisos. &iexcl;Todo el equipo te desea mucho &eacute;xito!</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div>


<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix red ">
	
							<h2 class="title">&iquest;No aceptado? :O :(
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p>Es posible que no seas aceptado o que no recibas respuesta. Si no eres aceptado, int&eacute;ntalo m&aacute;s tarde con una mejor solicitud. Si no recibes respuesta, quiz&aacute;s a&uacute;n no ha sido le&iacute;da.</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div><?php }else{
$sql = mysql_query("SELECT * FROM cms_application_forms WHERE id='".$_GET['id']."' AND enabled='1' AND deleted='0'");
$usersql = mysql_query("SELECT * FROM users WHERE id='".$my_id."' LIMIT 1");
if(mysql_num_rows($sql) > 0){
$row = mysql_fetch_assoc($sql);
$user = mysql_fetch_assoc($usersql); ?>




<?php

if(isset($_POST['sumbit'])) {


mysql_query("INSERT INTO cms_applications (rankname,username,realname,birth,sex,country,general_information,experience,education,additional_information,accepted_disclaimer) VALUES ('".HoloText($row['name'])."','".$rawname."','".$_POST['realname']."','".$user['birth']."','".$_POST['sex']."','".$_POST['country']."','".$_POST['general_information']."','".$_POST['experience']."','".$_POST['education']."','".$_POST['additional_information']."','".$_POST['accepted_disclaimer']."')") or die(mysql_error());
echo "<b>&iexcl;Tu solicitud ha sido enviada!</b>";

}
?>






<div id="container">
	<div id="content">
    <div id="column1" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix brown ">

							<h2 class="title">Solicitud: <?php echo HoloText($row['name']); ?>
							</h2>
						<div class="habblet box-content">
						<i><b>Nota:</b> No hay verificaci&oacute;n autom&aacute;tica. Si env&iacute;as el formulario incompleto, igualmente se enviar&aacute;. Solo puedes solicitar una vez hasta que sea le&iacute;da.</i><hr><center>El equipo de staff te desea mucha suerte.</center><hr>
						<?php if($row['introduction'] != "" OR $row['introduction'] != " ") { ?>
						<b>Introducci&oacute;n</b><br>
						<?php echo wordwrap(HoloText(nl2br($row['introduction'])),5000,"\n",1); ?><br><br><?php }
						if($row['requirements'] != "" OR $row['requirements'] != " ") { ?>
						<b>Requisitos</b><br>
						<?php echo wordwrap(HoloText(nl2br($row['requirements'])),5000,"\n",1); ?><br><br><?php }
								?>&iquest;Seguro que quieres solicitar este puesto? S&iacute;, rellena el formulario de abajo.<br><br>
								
					<form method="post">
						<table cellspacing="1" cellpadding="1" width="420" border="3">
							<?php if($row['username'] == 1) { ?>
							<tr>
								<td><b>Nombre de usuario</b><br>
								<i>&iquest;Cu&aacute;l es tu nombre de usuario?</i></td>
								
								<td><input type="text" maxlength="50" name="username" disabled="disabled" value="<?php echo HoloText($user['name']); ?>"></td>
							</tr>
							<?php } 
							if($row['realname'] == 1) { ?>
						<tr>
							<td><b>Nombre</b><br>
							<i>&iquest;Cu&aacute;l es tu nombre completo?</i></td>
							
							<td><input type="text" maxlength="50" name="realname" value="<?php echo $_POST['realname']; ?>"></td>
						</tr><?php }
							if($row['birth'] == 1) { ?>
						<tr>
							<td><b>Fecha de nacimiento</b><br>
							<i>&iquest;Cu&aacute;l es tu fecha de nacimiento?</i></td>
							
							<td><input type="text" maxlength="50" name="birth" disabled="disabled" value="<?php echo $user['birth']; ?>"></td>
						</tr>
							<?php }
							if($row['sex'] == 1) { ?>
						<tr>
							<td><b>Sexo</b><br>
							&iquest;Cu&aacute;l es tu sexo (Hombre/Mujer)?</td>
							
							<td><input type="text" maxlength="10" name="sex" value="<?php echo $_POST['sex']; ?>"></td>
						</tr>
							<?php }
							if($row['country'] == 1) { ?>
						<tr>
							<td><b>Pa&iacute;s</b><br><i>&iquest;En qu&eacute; pa&iacute;s vives?</i></td>
							
							<td><input type="text" maxlength="50" name="country" value="<?php echo $_POST['country']; ?>"></td>
						</tr>
							<?php }
							if($row['education'] == 1) { ?>
						<tr>
							<td><b>Estudios</b><br><i>&iquest;Qu&eacute; nivel de estudios tienes?</i></td>
							
							<td><input type="text" maxlength="50" name="education" value="<?php echo $_POST['education']; ?>"></td>
						</tr>
							<?php } ?>
					</table>
					
					<br>
					
					<table cellspacing="1" cellpadding="0" width="420" border="3">
						<?php if($row['general_information'] == 1) { ?>
							<td><b>Informaci&oacute;n general</b><br><i>&iquest;Por qu&eacute; te interesa este puesto y por qu&eacute; deber&iacute;amos elegirte?</i><br>
							<textarea name='general_information' cols='64' rows='5'><?php echo $_POST['general_information']; ?></textarea></td>
						<?php } ?>
					</table>
					
					<br>
					
					<table cellspacing="1" cellpadding="0" width="420" border="3">
						<?php if($row['experience'] == 1) { ?>
							<td><b>Experiencia</b><br><i>&iquest;Tienes experiencia? Si es as&iacute;, cu&eacute;ntanos.</i><br>
							<textarea name='experience' cols='64' rows='5'><?php echo $_POST['experience']; ?></textarea></td>
						<?php } ?>
					</table>
					
					<br>
					
					<table cellspacing="1" cellpadding="0" width="420" border="3">
						<?php if($row['additional_information'] == 1) { ?>
							<td><b>Informaci&oacute;n adicional</b><br><i>&iquest;Cu&aacute;les son tus aficiones e intereses?</i><br>
							<textarea name='additional_information' cols='64' rows='5'><?php echo $_POST['additional_information']; ?></textarea></td>
						<?php } ?>
					</table>
					<?php
					$questionscheck = mysql_query("SELECT * FROM cms_application_questions WHERE aid='".$_GET['id']."'");
					if(mysql_num_rows($questionscheck) > 0) { ?>

					<br>

					<?php
					$sql = mysql_query("SELECT * FROM cms_application_questions WHERE aid='".$_GET['id']."' AND aoq='1'");
					if(mysql_num_rows($sql) > 0) { ?>
					<table cellspacing="1" cellpadding="0" width="420" border="3">
							<td><b>Preguntas</b><br><i>A continuaci&oacute;n hay algunas preguntas.</i><br>
							<?php
							while($row = mysql_fetch_assoc($sql)) {
							$questions = mysql_query("SELECT * FROM cms_application_questions WHERE qid='".$row['id']."'") or die(mysql_error()); ?>
							<br><b><?php echo HoloText($row['text']); ?></b><br>
							<?php while($row = mysql_fetch_assoc($questions)) {
							$type = $row['type']; ?>
						 	<input value="<?php echo $row['id']; ?>" type="<?php if($row['sort'] != 1) { echo "checkbox"; }else{ echo "radio"; } ?>" name="<?php echo $row['type']; ?>"> <?php echo $row['text']; ?><br>
						<?php	}
							 }
						?></td>
					</table>
					
					<br>
<?php } ?>
					<?php } ?>
						<?php
$sql = mysql_query("SELECT * FROM cms_application_forms WHERE id='".$_GET['id']."' AND enabled='1' AND deleted='0'");
$row = mysql_fetch_assoc($sql);
						if($row['show_disclaimer'] == 1) {
								if($row['disclaimer_text'] != " " OR $row['disclaimer_text'] != "") {?>
								
					<table cellspacing="1" cellpadding="0" width="420" border="3">
							<td><b>Aviso legal</b><br><i>Lee el siguiente aviso legal y acuerda con sus t&eacute;rminos.</i><br><br><center>--------------------------------------------------------------------------------</center>
							<font color="gray"><?php echo wordwrap(HoloText(nl2br($row['disclaimer_text'])),5000,"\n",1); ?></font><br><center>--------------------------------------------------------------------------------</center><br>
							<INPUT type=checkbox name="agreement"<?php if(isset($_POST['agreement'])){ echo " CHECKED"; } ?>> Acepto este aviso legal.
							</td>
					</table>
						<?php }
							} ?>
					<br>
					<center><input type="submit" name="sumbit" value="&iexcl;Enviar solicitud!"></center>
					</form>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div>

<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix blue ">
	
							<h2 class="title">&iexcl;Aqu&iacute; estamos!
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p>&iexcl;Aqu&iacute; estamos! Est&aacute;s a punto de comenzar tu solicitud. El equipo de staff te desea mucha suerte.</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div>

<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix green ">
	
							<h2 class="title">&iquest;Preguntas?
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p>Es posible que encuentres preguntas en el formulario. As&iacute; podemos evaluar tus conocimientos. &iexcl;Mucha suerte!</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div>
























<?php }else{ ?>
<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="column1" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix red ">
	
							<h2 class="title">&iexcl;Esta solicitud est&aacute; cerrada!
							</h2>
						<div id="notfound-content" class="box-content">
    <p class="error-text">Lo sentimos, esta solicitud est&aacute; cerrada o no existe.</p> <img id="error-image" src="./web-gallery/v2/images/error.gif" />
    <p class="error-text">Usa el bot&oacute;n 'Atr&aacute;s' de tu navegador para volver.</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<div id="column2" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix green ">
	
							<h2 class="title">&iquest;Buscabas algo?
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p><b>&iquest;El grupo o la p&aacute;gina personal de un amigo?</b><br/>
    Mira si aparece en la p&aacute;gina <a href="community.php">Comunidad</a>.</p>

    <p><b>&iquest;Salas geniales?</b><br/>
    Explora la lista de <a href="community.php">Salas recomendadas</a>.</p>

    <p><b>&iquest;Qu&eacute; les interesa a otros usuarios?</b><br/>
    Echa un vistazo a las <a href="tags.php">principales etiquetas</a>.</p>

     <p><b>&iquest;C&oacute;mo obtener cr&eacute;ditos?</b><br/>
    Echa un vistazo a la p&aacute;gina de <a href="credits.php">cr&eacute;ditos</a>.</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<?php
	}
}
include('templates/community/footer.php');

?>
