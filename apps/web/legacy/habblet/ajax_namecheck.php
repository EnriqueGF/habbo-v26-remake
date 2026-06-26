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

$name = FilterText($_POST['name']);
$filter = preg_replace("/[^a-z\d\-=\?!@:\.]/i", "", $name);

$tmp = mysql_query("SELECT id FROM users WHERE name = '".$name."' LIMIT 1") or die(mysql_error());
$tmp = mysql_num_rows($tmp);

if($tmp > 0){
	header("X-JSON: {\"registration_name\":\"Lo sentimos, ese nombre de usuario ya est&aacute; en uso. Por favor elige otro.\"}");
} elseif($filter !== $name){
	header("X-JSON: {\"registration_name\":\"Lo sentimos, el nombre de usuario contiene caracteres no v&aacute;lidos.\"}");
} elseif(strlen($name) > 24){
	header("X-JSON: {\"registration_name\":\"Lo sentimos, el nombre de usuario es demasiado largo.\"}");
} elseif(strlen($name) < 1){
	header("X-JSON: {\"registration_name\":\"Por favor introduce un nombre de usuario.\"}");
} else {
	$pos = strrpos($refer, "MOD-");
	if ($pos === true) {
		header("X-JSON: {\"registration_name\":\"Este nombre no est&aacute; permitido.\"}");
	} else {
		header("X-JSON: {}");
	}
}

?>

