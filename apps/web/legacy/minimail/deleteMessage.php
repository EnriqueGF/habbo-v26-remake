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

$label = $_POST['label'];
$id = $_POST['messageId'];
$start = $_POST['start'];
$conversation = $_POST['conversationId'];

$sql = mysql_query("SELECT * FROM cms_minimail WHERE id = '".$id."' LIMIT 1");
$row = mysql_fetch_assoc($sql);

if($row['deleted'] == "1"){
mysql_query("DELETE FROM cms_minimail WHERE id = '".$id."' LIMIT 1");
$bypass = "true";
$page = "trash";
$message = "El mensaje se ha eliminado correctamente.";
include('loadMessage.php');
} else {
mysql_query("UPDATE cms_minimail SET deleted = '1' WHERE id = '".$id."' LIMIT 1");
$bypass = "true";
$page = "inbox";
$message = "El mensaje se ha movido a la papelera. Puedes recuperarlo si lo deseas.";
include('loadMessage.php');
} ?>