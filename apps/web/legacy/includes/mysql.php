<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright © 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind. 
+---------------------------------------------------*/

if (!defined("IN_HOLOCMS")) { header("Location: ../index.php"); exit; }

mysql_connect("$sqlhostname", "$sqlusername", "$sqlpassword")or die("<br><font size='2' face='Tahoma'><b>BioCMS Error de configuraci&oacute;n:</b><br>No se ha podido conectar con el servidor MySQL indicado. Revisa el mensaje de error de arriba para m&aacute;s detalles.</font>");
mysql_select_db("$sqldb")or die("<br><font size='2' face='Tahoma'><b>BioCMS Error de configuraci&oacute;n:</b><br>No se ha podido seleccionar la base de datos indicada.</font>");

?>