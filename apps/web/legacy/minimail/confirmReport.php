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

include('../core.php');
include('../includes/session.php');

$id = $_POST['messageId'];

$sql = mysql_query("SELECT * FROM cms_minimail WHERE id = '".$id."' LIMIT 1");
$row = mysql_fetch_assoc($sql);

if($row['senderid'] == $my_id){
	$error = 1;
	$message = "No puedes denunciar tus propios mensajes.";
}

if($error == 1){
?>
<ul class="error">
	<li><?php echo $message; ?></li>
</ul>

<p>
<a href="#" class="new-button cancel-report"><b>Cancelar</b><i></i></a>
</p>
<?php
} else {
$sql = mysql_query("SELECT * FROM users WHERE id = '".$row['senderid']."' LIMIT 1");
$senderrow = mysql_fetch_assoc($sql);
?>
<p>
&iquest;Seguro que quieres denunciar el mensaje <b><?php echo $row['subject']; ?></b> a los moderadores y eliminar a <b><?php echo $senderrow['name']; ?></b> de tu lista de amigos? Esta acci&oacute;n no se puede deshacer.
</p>

<p>
<a href="#" class="new-button cancel-report"><b>Cancelar</b><i></i></a>
<a href="#" class="new-button send-report"><b>Enviar denuncia</b><i></i></a>
</p>
<?php } ?>