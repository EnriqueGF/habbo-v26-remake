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

$pagename = "Wordfilter Options";

if(isset($_POST['add'])) {
	mysql_query("INSERT INTO cms_banners (text,banner,url,status,advanced,html) VALUES ('".$_POST['text']."','".$_POST['banner']."','".$_POST['url']."','".$_POST['status']."','".$_POST['advanced']."','".FilterText($_POST['html'])."')");
	$msg = "Acci&oacute;n realizada correctamente.";
	}
	
	if(isset($_POST['edit'])) {
	mysql_query("UPDATE cms_banners SET text='".$_POST['text']."',banner='".$_POST['banner']."',url='".$_POST['url']."',status='".$_POST['status']."',advanced='".$_POST['advanced']."',html='".FilterText($_POST['html'])."' WHERE id='".$_POST['id']."'");
	$msg = "Acci&oacute;n realizada correctamente.";
	}
	
	if(isset($_POST['delete'])) {
	mysql_query("DELETE FROM cms_banners WHERE id='".$_POST['id']."'");
	$msg = "Acci&oacute;n realizada correctamente.";
	}

@include('subheader.php');
@include('header.php');
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
 
  <?php if(!isset($_GET['a'])) {
			if(!isset($_GET['d'])) { ?>
<form action='index.php?p=banners' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>A&ntilde;adir banners</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<?php if(isset($_POST['add'])) { ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b><?php echo $msg; ?></b></div></td>
<td class='tablerow2'  width='60%'  valign='middle'></td>
</tr>
<?php } ?>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Text</b><div class='graytext'>El texto debajo de tu banner.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='text' value="<?php echo $_POST['text']; ?>" size='30' maxlength="50" class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Url of banner</b><div class='graytext'>&iquest;Cu&aacute;l es la URL de tu banner?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='banner' value="<?php echo $_POST['banner']; ?>" size='30' maxlength="255" class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Url</b><div class='graytext'>&iquest;A qu&eacute; sitio ir&aacute;n cuando hagan clic en el texto/banner?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='url' value="<?php echo $_POST['url']; ?>" size='30' maxlength="255" class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Advanced</b><div class='graytext'>Activar c&oacute;digo HTML (anula los ajustes anteriores)</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="radio"<?php if($_POST['advanced'] == 0) { echo " CHECKED"; } ?> value="0" name="advanced"> Desactivado<br><input type="radio"<?php if($_POST['advanced'] == 1) { echo " CHECKED"; } ?> value="1" name="advanced"> Activado</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>HTML</b><div class='graytext'>C&oacute;digo HTML para usuarios avanzados (por ejemplo, para Google Adsense)</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='html' cols='61' rows='3' wrap='soft' id='sub_desc' class='multitext'><?php echo $_POST['html']; ?></textarea></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Enable/disable</b><div class='graytext'>Activar o desactivar el banner.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="radio"<?php if($_POST['status'] == 0) { echo " CHECKED"; } ?> value="0" name="status"> Desactivado<br><input type="radio"<?php if($_POST['status'] == 1) { echo " CHECKED"; } ?> value="1" name="status"> Activado</td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='A&ntilde;adir banner' name="add" class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />	 </div>


<form action='index.php?p=banners' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Editor de banners</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<?php

$sql = mysql_query("SELECT * FROM cms_banners");
while($row = mysql_fetch_assoc($sql)) { ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Text</b><br> <?php echo $row['text']; ?><br><b>Banner url</b><br> <?php echo $row['banner']; ?><br><b>Linking url</b><br> <?php echo $row['url']; ?><br><b>Status</b><br> <?php if($row['status'] == "1") { echo "Activado"; }else{ echo "Desactivado"; } ?><br><b>Advanced</b><br> <?php if($row['advanced'] == "1") { echo "Activado"; }else{ echo "Desactivado"; } ?></div></td>
<td class='tablerow2'  width='60%'  valign='middle'><center><a href="index.php?p=banners&a=<?php echo $row['id']; ?>">Editar este banner</a><br><a href="index.php?p=banners&d=<?php echo $row['id']; ?>">Eliminar este banner</a></center></td>
</tr>
<?php } ?>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='A&ntilde;adir banner' name="add" class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />	 </div><?php } } ?>


<?php if(isset($_GET['a']) OR isset($_GET['d'])) { ?>
<form action='index.php?p=banners<?php if(isset($_GET['a'])) { echo "&a=".$_GET['a'].""; }else{ echo "&d=".$_GET['d'].""; } ?>' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'><?php if(isset($_GET['a'])) { echo "Editar banner"; }else{ echo "Eliminar banner"; } ?></div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<?php if(isset($_GET['a'])) {
$sql = mysql_query("SELECT * FROM cms_banners WHERE id='".$_GET['a']."'");
$row = mysql_fetch_assoc($sql); ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b><?php echo $msg; ?></b><div class='graytext'></div></td>
<td class='tablerow2'  width='60%'  valign='middle'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Text</b><div class='graytext'>El texto debajo de tu banner.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='text' value="<?php echo $row['text']; ?>" size='30' maxlength="50" class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Url of banner</b><div class='graytext'>&iquest;Cu&aacute;l es la URL de tu banner?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='banner' value="<?php echo $row['banner']; ?>" size='30' maxlength="255" class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Url</b><div class='graytext'>&iquest;A qu&eacute; sitio ir&aacute;n cuando hagan clic en el texto/banner?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='url' value="<?php echo $row['url']; ?>" size='30' maxlength="255" class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Advanced</b><div class='graytext'>Activar c&oacute;digo HTML (anula los ajustes anteriores)</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="radio"<?php if($row['advanced'] == 0) { echo " CHECKED"; } ?> value="0" name="advanced"> Desactivado<br><input type="radio"<?php if($row['advanced'] == 1) { echo " CHECKED"; } ?> value="1" name="advanced"> Activado</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>HTML</b><div class='graytext'>C&oacute;digo HTML para usuarios avanzados (por ejemplo, para Google Adsense)</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='html' cols='61' rows='3' wrap='soft' id='sub_desc' class='multitext'><?php echo HoloText($row['html']); ?></textarea></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Enable/disable</b><div class='graytext'>Activar o desactivar el banner.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="radio"<?php if($row['status'] == 0) { echo " CHECKED"; } ?> value="0" name="status"> Desactivado<br><input type="radio"<?php if($row['status'] == 1) { echo " CHECKED"; } ?> value="1" name="status"> Activado</td>
</tr>
<?php }else{
$sql = mysql_query("SELECT * FROM cms_banners WHERE id='".$_GET['d']."'");
$row = mysql_fetch_assoc($sql);
if(isset($_POST['delete'])) { ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b><?php echo $msg; ?></b></div></td>
<td class='tablerow2'  width='60%'  valign='middle'></td>
</tr>
<?php } ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Delete</b><div class='graytext'>&iquest;Quieres eliminar el banner con el siguiente texto:</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><?php echo $row['text']; ?></td>
</tr>
<?php } ?>

<tr>
<input type="hidden" name="id" value="<?php if(isset($_GET['a'])) { echo $_GET['a']; }else{ echo $_GET['d']; } ?>">
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='<?php if(isset($_GET['a'])) { echo "Editar"; }else{ echo "Eliminar"; } ?> banner' name="<?php if(isset($_GET['a'])) { echo "edit"; }else{ echo "delete"; } ?>" class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />	 </div><?php } ?>

<!-- / RIGHT CONTENT BLOCK -->
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