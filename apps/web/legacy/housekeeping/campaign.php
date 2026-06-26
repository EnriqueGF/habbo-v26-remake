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

mysql_query("DELETE FROM cms_campaigns WHERE id = '".$_GET['key']."'");

header("Location: index.php?p=campaign");

}



if(!isset($_GET['a'])){

	$_GET['a'] = "add";

}



$pagename = "Add Hot Campaign";



@include('subheader.php');

@include('header.php');



if(isset($_POST['edit']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['furni_image']) && isset($_POST['furni_image_small']) && isset($_POST['year']) && isset($_POST['month']) && isset($_POST['tid'])) {

mysql_query("UPDATE cms_campaigns SET name='".$_POST['name']."',image='".$_POST['image']."',url='".$_POST['url']."',desc='".$_POST['desc']."' WHERE id='".$_GET['key']."' LIMIT 1") or die(mysql_error());

$msg = "Modificado correctamente.";

}elseif(isset($_POST['add'])) {

mysql_query("INSERT INTO cms_campaigns (`name`, `image`, `url`, `desc`) VALUES ('".$_POST['name']."','".$_POST['image']."','".$_POST['url']."','".$_POST['desc']."')") or die(mysql_error());

$msg = "Noticia destacada a&ntilde;adida.";

}else{

$msg = "No olvides rellenar todos los campos.";

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

 

<form action='index.php?p=campaign&a=post' method='post' name='theAdminForm' id='theAdminForm'>

<div class='tableborder'>

<div class='tableheaderalt'><?php if($_GET['a'] == "add"){ echo "A&ntilde;adir "; }elseif($_GET['a'] == "edit") { echo "Editar "; } ?>Campa&ntilde;a</div>



<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>

<?php if($_GET['a'] == "add") { ?>

<tr>

<td class='tablerow1'  width='40%'  valign='middle'><b>Nombre</b><div class='graytext'>Nombre de la noticia destacada</div></td>

<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='name' value="<?php echo $_POST['name']; ?>" maxlength='500' class='textinput'></td>

</tr>



<tr>

<td class='tablerow1'  width='40%'  valign='middle'><b>Image</b><div class='graytext'>URL de la imagen de la noticia destacada</div></td>

<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='image' value="<?php echo $_POST['image']; ?>" maxlength='500' class='textinput'></td>

</tr>



<tr>

<td class='tablerow1'  width='40%'  valign='middle'><b>Enlace</b><div class='graytext'>URL de la noticia o grupo</div></td>

<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='url' value="<?php echo $_POST['url']; ?>" maxlength='500' class='textinput'></td>

</tr>



<tr>

<td class='tablerow1'  width='40%'  valign='middle'><b>Description</b><div class='graytext'>Descripci&oacute;n de tu noticia destacada</div></td>

<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='desc' value="<?php echo $_POST['desc']; ?>" maxlength='500' class='textinput'></td>

</tr>



<?php }elseif($_GET['a'] == "edit") {

		if(isset($_GET['key'])) {

		$sql = mysql_query("SELECT * FROM cms_campaigns WHERE id='".HoloText($_GET['key'])."' LIMIT 1");

		$row = mysql_fetch_assoc($sql);?>

<tr>

<td class='tablerow1'  width='40%'  valign='middle'><b>Nombre</b><div class='graytext'>Nombre de la campa&ntilde;a.</div></td>

<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='name' value="<?php echo $_POST['name']; ?>" maxlength='500' class='textinput'></td>

</tr>



<tr>

<td class='tablerow1'  width='40%'  valign='middle'><b>Image</b><div class='graytext'>La imagen de la campa&ntilde;a.</div></td>

<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='image' value="<?php echo $_POST['image']; ?>" maxlength='500' class='textinput'></td>

</tr>



<tr>

<td class='tablerow1'  width='40%'  valign='middle'><b>URL</b><div class='graytext'>La URL de la campa&ntilde;a.</div></td>

<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='url' value="<?php echo $_POST['url']; ?>" maxlength='500' class='textinput'></td>

</tr>



<tr>

<td class='tablerow1'  width='40%'  valign='middle'><b>Description</b><div class='graytext'>La descripci&oacute;n de la campa&ntilde;a.</div></td>

<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='desc' value="<?php echo $_POST['desc']; ?>" maxlength='500' class='textinput'></td>

</tr>



<?php }

} ?>



<?php if(isset($_GET['a']) && !isset($_GET['key'])) {

		if($_GET['a'] == "edit") {

echo "Si quieres editar una campa&ntilde;a, ve a la vista general y haz clic en el icono de editar.";

	}

}	?>

<tr>

<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' name='<?php if($_GET['a'] == "add") { echo "add"; }else{ echo "edit"; } ?>' value='<?php if($_GET['a'] == "add") { echo "A&ntilde;adir noticia"; }else{ echo "Editar campa&ntilde;a"; } ?>' class='realbutton' accesskey='s'></td></tr>

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
