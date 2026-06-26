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

$pagename = "HoloCMS Configuration";

if(isset($_POST['sitename'])){

	$sitename = FilterText($_POST['sitename']);
	$shortname = FilterText($_POST['shortname']);
	$enable_sso = $_POST['enable_sso'];
	$start_credits = $_POST['credits'];
	$language = FilterText($_POST['language']);
	$analytics = FilterText($_POST['analytics']);

	if(!empty($sitename) && !empty($shortname) && !empty($language)){

		mysql_query("UPDATE cms_system SET sitename = '".$sitename."', shortname = '".$shortname."', enable_sso = '".$enable_sso."', language = '".$language."' , start_credits='".$start_credits."', analytics = '".$analytics."' LIMIT 1") or die(mysql_error());
		$msg = "Ajustes guardados correctamente.";

		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Updated CMS Settings (General Configuration)','cms_config.php','".$my_id."','','".$date_full."')") or die(mysql_error());

	} else {

		$msg = "No dejes ning&uacute;n campo en blanco. Ajustes no guardados.";

	}

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
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
 
<form action='index.php?p=site&do=save' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Configuraci&oacute;n de HoloCMS</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Nombre del sitio</b><div class='graytext'>Este es el nombre completo de tu sitio, p. ej. 'Holo Hotel'.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='sitename' value="<?php echo FetchCMSSetting('sitename'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Nombre corto</b><div class='graytext'>Este es el nombre corto de tu sitio, p. ej. 'Holo'.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='shortname' value="<?php echo FetchCMSSetting('shortname'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Activar SSO</b><div class='graytext'>Si est&aacute; activado, se generar&aacute;n tickets SSO y los usuarios iniciar&aacute;n sesi&oacute;n con SSO.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='enable_sso'  class='dropdown'>
									<option value='1'>Activado</option>
									<option value='0' <?php if(FetchCMSSetting('enable_sso') == "0"){ echo "selected='selected'"; } ?>>Desactivado</option>
								    </select>
</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Idioma</b><div class='graytext'>C&oacute;digo de idioma de 2 caracteres desde el que tu sitio leer&aacute; los ficheros de idioma, p. ej. 'en'. Si el idioma no es v&aacute;lido, se usar&aacute; el ingl&eacute;s.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='language' value="<?php echo FetchCMSSetting('language'); ?>" size='2' maxlength='2' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Cr&eacute;ditos iniciales</b><div class='graytext'>&iquest;Cu&aacute;ntos cr&eacute;ditos reciben los usuarios al registrarse en el sitio?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='credits' value="<?php echo FetchCMSSetting('start_credits'); ?>" size='2' maxlength='5' class='textinput'></td>
</tr>


<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Guardar configuraci&oacute;n' class='realbutton' accesskey='s'></td></tr>
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