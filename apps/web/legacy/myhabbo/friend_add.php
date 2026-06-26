<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright � 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================+
|| # Parts by Yifan Lu
|| # www.obbahhotel.com
|+===================================================*/

include('../core.php');

$id = $_POST['accountId'];
$sql = mysql_query("SELECT * FROM messenger_friendships WHERE userid = '".$id."' AND friendid = '".$my_id."'");
$rows = mysql_num_rows($sql);
if($rows <> 0){
$error = 1;
$message = "Esta persona ya es tu amigo.";
}

$sql = mysql_query("SELECT * FROM messenger_friendships WHERE userid = '".$my_id."' AND friendid = '".$id."'");
$rows = mysql_num_rows($sql);
if($rows <> 0){
$error = 1;
$message = "Esta persona ya es tu amigo.";
}

$sql = mysql_query("SELECT * FROM messenger_friendrequests WHERE userid_from = '".$my_id."' AND userid_to = '".$id."'");
$rows = mysql_num_rows($sql);
if($rows <> 0){
$error = 1;
$message = "Ya has enviado una solicitud de amistad a esta persona.";
}

$sql = mysql_query("SELECT * FROM messenger_friendrequests WHERE userid_to = '".$my_id."' AND userid_from = '".$id."'");
$rows = mysql_num_rows($sql);
if($rows <> 0){
$error = 1;
$message = "Esta persona ya te ha enviado una solicitud de amistad.";
}

if($id == $my_id){
$error = 1;
$message = "No puedes enviarte una solicitud de amistad a ti mismo.";
}

if($error <> 1){
$sql = mysql_query("SELECT MAX(requestid) FROM messenger_friendrequests WHERE userid_to = '".$id."'");
$requestid = mysql_result($sql, 0);
$requestid = $requestid + 1;

mysql_query("INSERT INTO messenger_friendrequests (userid_from,userid_to,requestid) VALUES ('".$my_id."','".$id."','".$requestid."')");

$message = "Solicitud de amistad enviada correctamente.";
} ?>
	Dialog.showInfoDialog("add-friend-messages",  
		"<?php echo $message; ?>",
		"OK");