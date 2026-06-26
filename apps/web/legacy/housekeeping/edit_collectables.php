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

if($_GET['a'] == "delete"){
mysql_query("DELETE FROM cms_collectables WHERE id = '".$_GET['key']."'");
header("Location: index.php?p=collectables");
}

$pagename = "Collectables";

@include('subheader.php');
@include('header.php');

if(isset($_POST['edit']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['furni_image_big']) && isset($_POST['furni_image_small']) && isset($_POST['year']) && isset($_POST['month']) && isset($_POST['tid'])) {
mysql_query("UPDATE cms_collectables SET title='".$_POST['title']."',description='".$_POST['description']."',image_small='".$_POST['furni_image_small']."',image_large='".$_POST['furni_image_big']."',year='".$_POST['year']."',month='".$_POST['month']."',tid='".$_POST['tid']."' WHERE id='".$_GET['key']."' LIMIT 1") or die(mysql_error());
$msg = "Modificado correctamente.";
}elseif(isset($_POST['add'])) {
mysql_query("INSERT INTO cms_collectables (title,image_small,image_large,tid,description,month,year) VALUES ('".$_POST['title']."','".$_POST['furni_image_small']."','".$_POST['furni_image_big']."','".$_POST['tid']."','".$_POST['description']."','".$_POST['month']."','".$_POST['year']."')") or die(mysql_error());
$msg = "Coleccionable a&ntilde;adido.";
}else{
$msg = "Rellena todos los campos.";
}

?>
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr> <td width='22%' valign='top' id='leftblock'>
 <div>
 <!-- LEFT CONTEXT SENSITIVE MENU -->
<?php @include('sitemenu.php'); ?>
 <!-- / LEFT CONTEXT SENSITIVE MENU -->
 </div>
 </td>
 <td width='78%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
 
<form action='index.php?p=collectables_edit&a=<?php echo $_GET['a']; ?><?php if(isset($_GET['key'])) { echo "&key=".$_GET['key'].""; } ?>' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'><?php if($_GET['a'] == "add"){ echo "A&ntilde;adir "; }elseif($_GET['a'] == "edit") { echo "Editar "; } ?>Coleccionables</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<?php if($_GET['a'] == "add") { ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Nombre</b><div class='graytext'>&iquest;Cu&aacute;l es el nombre del coleccionable?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='title' value="<?php echo $_POST['title']; ?>" maxlength='50' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Descripci&oacute;n</b><div class='graytext'>&iquest;Cu&aacute;l es la descripci&oacute;n?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='description' value="<?php echo $_POST['description']; ?>" maxlength='175' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Imagen del Furni (grande)</b><div class='graytext'>Proporciona una URL de la imagen del mobiliario (grande).</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='furni_image_big' value="<?php echo $_POST['furni_image_big']; ?>" maxlength='255' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Imagen del Furni (peque&ntilde;a)</b><div class='graytext'>Proporciona una URL de la imagen del mobiliario (peque&ntilde;a).</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='furni_image_small' value="<?php echo $_POST['furni_image_small']; ?>" maxlength='255' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Mes</b><div class='graytext'>&iquest;En qu&eacute; mes debe aparecer el mobiliario?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><SELECT style="COLOR: black; FONT-FAMILY: Verdana" name=month> <OPTION value="01" selected>Enero</OPTION><OPTION value="02">Febrero</OPTION><OPTION value="03">Marzo</OPTION><OPTION value="04">Abril</OPTION><OPTION value="05">Mayo</OPTION><OPTION value="06">Junio</OPTION><OPTION value="07">Julio</OPTION><OPTION value="08">Agosto</OPTION><OPTION value="09">Septiembre</OPTION><OPTION value="10">Octubre</OPTION><OPTION value="11">Noviembre</OPTION><OPTION value="12">Diciembre</OPTION></SELECT></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>A&ntilde;o</b><div class='graytext'>&iquest;En qu&eacute; a&ntilde;o debe aparecer el mobiliario?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='year' value="<?php echo $_POST['year']; ?>" maxlength='4' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Furni tid</b><div class='graytext'>&iquest;Cu&aacute;l es el "tid" del mobiliario? Puedes encontrar todos los tids <a href="index.php?p=furniture">aqu&iacute;</a>.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='tid' value="<?php echo $_POST['tid']; ?>" maxlength='9' class='textinput'></td>
</tr>
<?php }elseif($_GET['a'] == "edit") {
		if(isset($_GET['key'])) {
		$sql = mysql_query("SELECT * FROM cms_collectables WHERE id='".HoloText($_GET['key'])."' LIMIT 1");
		$row = mysql_fetch_assoc($sql);?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Nombre</b><div class='graytext'>&iquest;Cu&aacute;l es el nombre del coleccionable?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='title' value="<?php echo $row['title']; ?>" maxlength='50' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Descripci&oacute;n</b><div class='graytext'>&iquest;Cu&aacute;l es la descripci&oacute;n?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='description' value="<?php echo $row['description']; ?>" maxlength='175' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Imagen del Furni (grande)</b><div class='graytext'>Proporciona una URL de la imagen del mobiliario (grande).</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='furni_image_big' value="<?php echo $row['image_large']; ?>" maxlength='255' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Imagen del Furni (peque&ntilde;a)</b><div class='graytext'>Proporciona una URL de la imagen del mobiliario (peque&ntilde;a).</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='furni_image_small' value="<?php echo $row['image_small']; ?>" maxlength='255' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Mes</b><div class='graytext'>&iquest;En qu&eacute; mes debe aparecer el mobiliario?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><SELECT style="COLOR: black; FONT-FAMILY: Verdana" name=month> <OPTION value="01" selected>Enero</OPTION><OPTION value="02">Febrero</OPTION><OPTION value="03">Marzo</OPTION><OPTION value="04">Abril</OPTION><OPTION value="05">Mayo</OPTION><OPTION value="06">Junio</OPTION><OPTION value="07">Julio</OPTION><OPTION value="08">Agosto</OPTION><OPTION value="09">Septiembre</OPTION><OPTION value="10">Octubre</OPTION><OPTION value="11">Noviembre</OPTION><OPTION value="12">Diciembre</OPTION></SELECT></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>A&ntilde;o</b><div class='graytext'>&iquest;En qu&eacute; a&ntilde;o debe aparecer el mobiliario?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='year' value="<?php echo $row['year']; ?>" maxlength='4' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Furni tid</b><div class='graytext'>&iquest;Cu&aacute;l es el "tid" del mobiliario? Puedes encontrar todos los tids <a href="index.php?p=furniture">aqu&iacute;</a>.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='tid' value="<?php echo $row['tid']; ?>" maxlength='4' class='textinput'></td>
</tr>
<?php }
} ?>

<?php if(isset($_GET['a']) && !isset($_GET['key'])) {
		if($_GET['a'] == "edit") {
echo "Si quieres editar un coleccionable, ve a la vista general y haz clic en el icono de editar.";
	}
}	?>
<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' name='<?php if($_GET['a'] == "add") { echo "add"; }else{ echo "edit"; } ?>' value='<?php if($_GET['a'] == "add") { echo "A&ntilde;adir coleccionable"; }else{ echo "Editar coleccionable"; } ?>' class='realbutton' accesskey='s'></td></tr>
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