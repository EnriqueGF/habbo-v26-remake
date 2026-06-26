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

$password = FilterText($_POST['password']);

if(strlen($password) < 6){
	echo "La contrase&ntilde;a debe tener al menos 6 caracteres.";
} else {
	header("X-JSON: \"charOk\"");
	echo "&iexcl;La contrase&ntilde;a es segura!";
}

?>

