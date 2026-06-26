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

$pagename = "Applications forms";


@include('subheader.php');
@include('header.php');

$catId = $_POST['category'];

if(empty($catId) || !is_numeric($catId) || $catId < 1 || $catId > 5){
    $catId = 1;
} else {
    $catId = $catId;
}

if(isset($_POST['name']) || isset($_POST['introduction']) || isset($_POST['requirements']) || isset($_POST['disclaimer'])) {
mysql_query("INSERT INTO cms_application_forms (name,introduction,requirements,hconly,username,realname,birth,sex,country,general_information,show_disclaimer,disclaimer_text,enabled) VALUES ('".$_POST['name']."','".$_POST['introduction']."','".$_POST['requirements']."','".$_POST['hconly']."','".$_POST['username']."','".$_POST['realname']."','".$_POST['birth']."','".$_POST['sex']."','".$_POST['country']."','".$_POST['general_information']."','".$_POST['show_dislcaimer']."','".$_POST['disclaimer']."','".$_POST['enabled']."')");
if(mysql_error() == "") {
$msg = "A&ntilde;adido correctamente";
	}else{
		$msg = "&iexcl;Rellena todos los campos!";
		echo mysql_error();
	}
}
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
 
 
 
 <table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<?php echo $msg; ?>
<form action='index.php?p=application_edit&do=jumpCategory' method='post' name='Jumper!' id='Jumper!'>
<div class='tableborder'>
<div class='tableheaderalt'>Seleccionar categor&iacute;a</div>
<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
    <td class='tablerow2'  width='100%'  valign='middle'  align='center'>
        <select name='category' class='dropdown'>
            <option value='1' <?php if($catId == "1"){ echo "selected='selected'"; } ?>>Formularios de solicitud</option>
            <option value='2' <?php if($catId == "2"){ echo "selected='selected'"; } ?>>A&ntilde;adir formulario de solicitud</option>
        </select>
        &nbsp;
        <input type='submit' value='Ir' class='realbutton' accesskey='s'>
    </td>
</tr>
</table>
</div>
</div>
</form>

<br />
 
 
 <?php if(isset($_POST['category'])) {
		if($_POST['category'] == 1) { ?>
 <div class='tableborder'>
 <div class='tableheaderalt'><?php echo $sitename; ?> editor de formularios de solicitud</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='2%' align='center'>ID de solicitud</td>
  <td class='tablesubheader' width='31%' align='center'>Nombre</td>
  <td class='tablesubheader' width='31%' align='center'>Introducci&oacute;n</td>
  <td class='tablesubheader' width='31%' align='center'>Aviso legal</td>
  <td class='tablesubheader' width='3%' align='center'>Solo HC</td>
  <td class='tablesubheader' width='2%' align='center'>Editar</td>
 </tr>
<?php
$get_rooms = mysql_query("SELECT id,name,introduction,hconly,disclaimer_text FROM cms_application_forms WHERE deleted='0'") or die(mysql_error());

while($row = mysql_fetch_assoc($get_rooms)){ ?>
 <tr>
  <td class='tablerow1' align='center'><?php echo $row['id']; ?></td>
  <td class='tablerow2'><?php echo HoloText($row['name']); ?></div></td>
  <td class='tablerow2' align='center'><?php echo HoloText($row['introduction']); ?></td>
  <td class='tablerow2' align='center'><?php echo HoloText($row['disclaimer_text']); ?></td>
  <td class='tablerow2' align='center'><?php if($row['hconly'] == 0) { echo "No"; }else{ echo "S&iacute;"; } ?></td>
  <td class='tablerow2' align='center'><a href='index.php?p=application_form_edit&key=<?php echo $row['id']; ?>&a=edit'><img src='./images/edit.gif' alt='Editar formulario de solicitud'></a> <a href='index.php?p=application_form_edit&key=<?php echo $row['id']; ?>&a=delete'><img src='./images/delete.gif' alt='Eliminar formulario de solicitud'></a></td>
</tr>
<?php } ?>
 
 </table>
</div>
<?php }elseif($_POST['category'] == 2) { ?>



<form action='index.php?p=application_edit&do=save' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>A&ntilde;adir un formulario</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>


<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Nombre del rango</b><div class='graytext'>&iquest;Para qu&eacute; pueden solicitar?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='name' value='<?php echo $_POST['name']; ?>' size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Introducci&oacute;n</b><div class='graytext'>Describe brevemente qu&eacute; hay que hacer para este rango y en qu&eacute; consiste.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='introduction' cols='84' rows='5' class='textinput'><?php echo $_POST['introduction']; ?></textarea></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Requisitos</b><div class='graytext'>&iquest;Cu&aacute;les son los requisitos para este puesto? <b>Ejemplo:</b> buen ingl&eacute;s, no enfadarse, buena capacidad de trabajo en equipo.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='requirements' cols='84' rows='5' class='textinput'><?php echo $_POST['requirements']; ?></textarea></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>&iquest;Solo HC?</b><div class='graytext'>&iquest;Solo pueden solicitar los miembros de HC?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='hconly' value='0'<?php if($_POST['hconly'] == 0) { echo " CHECKED"; } ?>> Todos pueden solicitar <input type='radio' name='hconly' value='1'<?php if($_POST['hconly'] == 1) { echo " CHECKED"; } ?>> Solo HC puede solicitar</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>&iquest;Nombre de usuario?</b><div class='graytext'>&iquest;Mostrar (NO rellenar, ya est&aacute; en la base de datos) el nombre de usuario en el formulario? <b>Ejemplo:</b><br><b>Nombre de usuario:</b> nombremiembro</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='username' value='0'<?php if($_POST['username'] == 0) { echo " CHECKED"; } ?>> No <input type='radio' name='username' value='1'<?php if($_POST['username'] == 1) { echo " CHECKED"; } ?>> S&iacute;</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>&iquest;Nombre real?</b><div class='graytext'>&iquest;Deben los usuarios indicar su nombre real?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='realname' value='0'<?php if($_POST['realname'] == 0) { echo " CHECKED"; } ?>> No <input type='radio' name='realname' value='1'<?php if($_POST['realname'] == 1) { echo " CHECKED"; } ?>> S&iacute;</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>&iquest;Fecha de nacimiento?</b><div class='graytext'>&iquest;Mostrar (NO rellenar, ya est&aacute; en la base de datos) la fecha de nacimiento en el formulario? <b>Ejemplo:</b><br><b>Fecha de nacimiento:</b> 24-02-1991</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='birth' value='0'<?php if($_POST['birth'] == 0) { echo " CHECKED"; } ?>> No <input type='radio' name='birth' value='1'<?php if($_POST['birth'] == 1) { echo " CHECKED"; } ?>> S&iacute;</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>&iquest;Sexo?</b><div class='graytext'>&iquest;Deben los usuarios indicar su sexo (hombre/mujer/otro)?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='sex' value='0'<?php if($_POST['sex'] == 0) { echo " CHECKED"; } ?>> No <input type='radio' name='sex' value='1'<?php if($_POST['sex'] == 1) { echo " CHECKED"; } ?>> S&iacute;</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>&iquest;Pa&iacute;s?</b><div class='graytext'>&iquest;Deben los usuarios indicar el pa&iacute;s en el que viven?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='country' value='0'<?php if($_POST['country'] == 0) { echo " CHECKED"; } ?>> No <input type='radio' name='country' value='1'<?php if($_POST['country'] == 1) { echo " CHECKED"; } ?>> S&iacute;</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>&iquest;Experiencia?</b><div class='graytext'>&iquest;Deben indicar su experiencia previa con este puesto?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='experience' value='0'<?php if($_POST['experience'] == 0) { echo " CHECKED"; } ?>> No <input type='radio' name='experience' value='1'<?php if($_POST['experience'] == 1) { echo " CHECKED"; } ?>> S&iacute;</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>&iquest;Estudios?</b><div class='graytext'>&iquest;Deben indicar qu&eacute; estudios realizan o realizaron?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='education' value='0'<?php if($_POST['education'] == 0) { echo " CHECKED"; } ?>> No <input type='radio' name='education' value='1'<?php if($_POST['education'] == 1) { echo " CHECKED"; } ?>> S&iacute;</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>&iquest;Informaci&oacute;n general?</b><div class='graytext'>&iquest;Deben facilitar informaci&oacute;n general (por qu&eacute; quieren el puesto, por qu&eacute; les interesa, etc.)?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='general_information' value='0'<?php if($_POST['general_information'] == 0) { echo " CHECKED"; } ?>> No <input type='radio' name='general_information' value='1'<?php if($_POST['general_information'] == 1) { echo " CHECKED"; } ?>> S&iacute;</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>&iquest;Informaci&oacute;n adicional?</b><div class='graytext'>&iquest;Deben facilitar informaci&oacute;n adicional (aficiones, etc.)?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='additional_information' value='0'<?php if($_POST['additional_information'] == 0) { echo " CHECKED"; } ?>> No <input type='radio' name='additional_information' value='1'<?php if($_POST['additional_information'] == 1) { echo " CHECKED"; } ?>> S&iacute;</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>&iquest;Mostrar aviso legal?</b><div class='graytext'>&iquest;Deben los usuarios leer un aviso legal antes de enviar la solicitud?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='show_disclaimer' value='0'<?php if($_POST['show_disclaimer'] == 0) { echo " CHECKED"; } ?>> No <input type='radio' name='show_disclaimer' value='1'<?php if($_POST['show_disclaimer'] == 1) { echo " CHECKED"; } ?>> S&iacute;</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Aviso legal</b><div class='graytext'>Redacta aqu&iacute; tu propio aviso legal. Los usuarios deben aceptarlo (si est&aacute; configurado) antes de enviar la solicitud.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='disclaimer' cols='84' rows='5' class='textinput'><?php echo $_POST['disclaimer']; ?></textarea></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>&iquest;Activado/desactivado?</b><div class='graytext'>&iquest;Est&aacute; la solicitud abierta o cerrada?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='enabled' value='0'<?php if($_POST['general_information'] == 0) { echo " CHECKED"; } ?>> Cerrada <input type='radio' name='enabled' value='1'<?php if($_POST['enabled'] == 1) { echo " CHECKED"; } ?>> Abierta</td>
</tr>

<tr>
<td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Crear formulario de solicitud' class='realbutton' accesskey='s'>
<input type="hidden" name="category" value="2"></td>
</tr>
</table>
</div>
</div>

</form>



<?php }
	}
		if(!isset($_POST['category'])) {?>
 <div class='tableborder'>
 <div class='tableheaderalt'><?php echo $sitename; ?> editor de formularios de solicitud</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='2%' align='center'>ID de solicitud</td>
  <td class='tablesubheader' width='31%' align='center'>Nombre</td>
  <td class='tablesubheader' width='31%' align='center'>Introducci&oacute;n</td>
  <td class='tablesubheader' width='31%' align='center'>Aviso legal</td>
  <td class='tablesubheader' width='3%' align='center'>Solo HC</td>
  <td class='tablesubheader' width='2%' align='center'>Editar</td>
 </tr>
<?php
$get_rooms = mysql_query("SELECT id,name,introduction,hconly,disclaimer_text FROM cms_application_forms WHERE deleted='0'") or die(mysql_error());

while($row = mysql_fetch_assoc($get_rooms)){ ?>
 <tr>
  <td class='tablerow1' align='center'><?php echo $row['id']; ?></td>
  <td class='tablerow2'><?php echo HoloText($row['name']); ?></div></td>
  <td class='tablerow2' align='center'><?php echo HoloText($row['introduction']); ?></td>
  <td class='tablerow2' align='center'><?php echo HoloText($row['disclaimer_text']); ?></td>
  <td class='tablerow2' align='center'><?php if($row['hconly'] == 0) { echo "No"; }else{ echo "S&iacute;"; } ?></td>
  <td class='tablerow2' align='center'><a href='index.php?p=application_form_edit&key=<?php echo $row['id']; ?>&a=edit'><img src='./images/edit.gif' alt='Editar formulario de solicitud'></a> <a href='index.php?p=application_form_edit&key=<?php echo $row['id']; ?>&a=delete'><img src='./images/delete.gif' alt='Eliminar formulario de solicitud'></a></td>
</tr>
<?php } ?>
 
 </table>
</div>
<?php } ?>
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