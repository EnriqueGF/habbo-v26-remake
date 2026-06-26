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

if(!session_is_registered(username)){ echo "<p>\nPor favor, inicia sesi&oacute;n primero.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"habboclub.closeSubscriptionWindow(); return false;\"><b>Hecho</b><i></i></a>\n</p>"; exit; }

if(!IsHCMember($my_id)){
    echo "<p>No eres miembro del ".$shortname." Club</p>\n<p>&nbsp;</p>";
} else {
    echo "<p>Eres miembro del ".$shortname." Club</p>\n<p>Te quedan " . HCDaysLeft($my_id) . " d&iacute;a(s) de Club</p>";
}

?>



