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

require_once('../core.php');
if($hkzone !== true){ header("Location: index.php?throwBack=true"); exit; }
if(!session_is_registered(acp)){ header("Location: index.php?p=login"); exit; }

$pagename = "Guestroom editor";

@include('subheader.php');
@include('header.php');
?>
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr> <td width='22%' valign='top' id='leftblock'>
 <div>
 <!-- LEFT CONTEXT SENSITIVE MENU -->
<?php @include('usermenu.php'); ?>
 <!-- / LEFT CONTEXT SENSITIVE MENU -->
 </div>
 </td>
 <td width='78%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->
 
 <div class='tableborder'>
 <div class='tableheaderalt'><?php echo $sitename; ?> - Editor de salas privadas</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='1%' align='center'>ID de sala</td>
  <td class='tablesubheader' width='14%' align='center'>Propietario</td>
  <td class='tablesubheader' width='30%' align='center'>Nombre de sala</td>
  <td class='tablesubheader' width='30%' align='center'>Descripci&oacute;n</td>
  <td class='tablesubheader' width='23%' align='center'>Tipo</td>
  <td class='tablesubheader' width='2%' align='center'>Editar</td>
 </tr>
<?php
$get_rooms = mysql_query("SELECT * FROM rooms WHERE owner != '' OR owner != ''") or die(mysql_error());

while($row = mysql_fetch_assoc($get_rooms)){ ?>
 <tr>
  <td class='tablerow1' align='center'><?php echo $row['id']; ?></td>
  <td class='tablerow2'><?php echo HoloText($row['owner']); ?></div></td>
  <td class='tablerow2' align='center'><?php echo HoloText($row['name']); if($row['name'] == "" OR $row['name'] == " ") { echo "<b><i>Sin nombre</i></b>"; } ?></td>
  <td class='tablerow2' align='center'><?php echo HoloText($row['description']); if($row['description'] == "" OR $row['description'] == " ") { echo "<b><i>Sin descripci&oacute;n</i></b>"; } ?></td>
  <td class='tablerow2' align='center'><?php if($row['state'] == 0) { echo "Abierta"; }elseif($row['state'] == 1) { echo "Cerrada (los visitantes deben llamar)"; }elseif($row['state'] == 2) { echo "Protegida con contrase&ntilde;a"; }elseif($row['state'] == 3) { echo "Solo HC"; }elseif($row['state'] == 4) { echo "Solo staff"; }else{ echo "Desconocido"; } ?></td>
  <td class='tablerow2' align='center'><a href='index.php?p=editguestroom&key=<?php echo $row['id']; ?>&a=edit'><img src='./images/edit.gif' alt='Editar sala privada'></a> <a href='index.php?p=editguestroom&key=<?php echo $row['id']; ?>&a=delete'><img src='./images/delete.gif' alt='Eliminar sala privada'></a></td>
</tr>
<?php } ?>
 
 </table>
</div>
	 </div><!-- / RIGHT CONTENT BLOCK -->
	 </td></tr>
</table>
</div><!-- / OUTERDIV -->
<div align='center'><br />
<?php
$mtime = explode(' ', microtime());
$totaltime = $mtime[0] + $mtime[1] - $starttime;
printf('Tiempo: %.3f', $totaltime);
?>
</div>