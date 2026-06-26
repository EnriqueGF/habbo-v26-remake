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

$pagename = "Edit guestroom";

if(isset($_GET['key'])) {
	if(isset($_POST['owner']) || isset($_POST['showname']) || isset($_POST['name']) || isset($_POST['description']) || isset($_POST['state']) ||  isset($_POST['visitors_max'])) {
	mysql_query("UPDATE rooms SET owner='".$_POST['owner']."',password='".$_POST['password']."',showname='".$_POST['showname']."',name='".$_POST['name']."',description='".$_POST['description']."',state='".$_POST['state']."',visitors_now='".$_POST['visitors_now']."',visitors_max='".$_POST['visitors_max']."' WHERE id='".$_GET['key']."'") or die(mysql_error());
	
	$sql = mysql_query("SELECT id FROM users WHERE name='".$_POST['owner']."'") or die(mysql_error());
		while($row = mysql_fetch_assoc($sql)) {
			mysql_query("UPDATE furniture SET ownerid='".$row['id']."' WHERE roomid='".$_GET['key']."'") or die(mysql_error());
		}
	$msg = "Actualizado correctamente.";
	}else{
	$msg = "Rellena todos los campos.";
	}
}else{
echo "Proporciona un ID de sala.";
die;
}

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
 
<?php
if($_GET['a'] == "delete") {
mysql_query("DELETE FROM rooms WHERE id='".$_GET['key']."' LIMIT 1");
echo "<b>Eliminado correctamente.</b><br>";
}

$sql = mysql_query("SELECT * FROM rooms WHERE id='".$_GET['key']."' AND owner != '' LIMIT 1");
$row = mysql_fetch_assoc($sql);

if(mysql_num_rows($sql) != 1) {
echo "La sala no existe o existen salas duplicadas.";
die;
}
if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
 
<form method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Editar sala privada</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Propietario de la sala</b><div class='graytext'>&iquest;Qui&eacute;n es el propietario de la sala?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="text" name="owner" maxlength="20" value="<?php echo $row['owner']; ?>"></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Mostrar/ocultar nombre</b><div class='graytext'>&iquest;Activar/desactivar el nombre del propietario (para que aparezca "Propietario: -")?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><SELECT style="COLOR: black; FONT-FAMILY: Verdana" name=showname> <OPTION value="0"<?php if($row['showname'] == 0) { echo " selected"; } ?>>Mostrar</OPTION><OPTION value="1"<?php if($row['showname'] == 1) { echo " selected"; } ?>>Ocultar</OPTION></SELECT></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Nombre de la sala</b><div class='graytext'>&iquest;Cu&aacute;l es el nombre de la sala?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="text" name="name" value="<?php echo $row['name']; ?>"></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Descripci&oacute;n de la sala</b><div class='graytext'>&iquest;Cu&aacute;l es la descripci&oacute;n de la sala?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="text" name="description" value="<?php echo $row['description']; ?>"></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Estado</b><div class='graytext'>&iquest;Cu&aacute;l es el estado de la sala?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><SELECT style="COLOR: black; FONT-FAMILY: Verdana" name=state> <OPTION value="0"<?php if($row['state'] == 0) { echo " selected"; } ?>>Abierta</OPTION><OPTION value="1"<?php if($row['state'] == 1) { echo " selected"; } ?>>Cerrada (timbre)</OPTION><OPTION value="2"<?php if($row['state'] == 2) { echo " selected"; } ?>>Protegida con contrase&ntilde;a</OPTION><OPTION value="3"<?php if($row['state'] == 3) { echo " selected"; } ?>>Solo HC</OPTION><OPTION value="4"<?php if($row['state'] == 4) { echo " selected"; } ?>>Solo staff</OPTION></SELECT></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Contrase&ntilde;a de la sala</b><div class='graytext'>&iexcl;Esto solo funciona si la sala est&aacute; protegida con contrase&ntilde;a!</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="password" name="password" maxlength="20" value="<?php echo $row['password']; ?>"></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Visitantes actuales</b><div class='graytext'>Cu&aacute;ntos visitantes hay ahora mismo (datos ficticios).</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="text" name="visitors_now" value="<?php echo $row['visitors_now']; ?>"></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>M&aacute;ximo de visitantes</b><div class='graytext'>&iquest;Cu&aacute;ntos visitantes pueden entrar en la sala?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="text" name="visitors_max" value="<?php echo $row['visitors_max']; ?>"></td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Guardar opciones' class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />	 </div><!-- / RIGHT CONTENT BLOCK -->
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