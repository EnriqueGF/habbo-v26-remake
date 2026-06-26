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

$pagename = "Loader Configuration";

if(isset($_POST['ip'])){

	$ip = FilterText($_POST['ip']);
	$texts = FilterText($_POST['texts']);
	$variables = FilterText($_POST['variables']);
	$dcr = FilterText($_POST['dcr']);
	$reload_url = FilterText($_POST['reload_url']);
	$localhost = $_POST['localhost'];

	if(!empty($ip) && !empty($texts) && !empty($variables) && !empty($dcr) && !empty($reload_url)){

		mysql_query("UPDATE cms_system SET ip = '".$ip."', texts = '".$texts."', variables = '".$variables."', dcr = '".$dcr."', reload_url = '".$reload_url."', localhost = '".$localhost."', loader='".$_POST['loader']."' LIMIT 1") or die(mysql_error());
		$msg = "Ajustes guardados correctamente.";
		mysql_query("UPDATE cms_content SET contentvalue = '".$_POST['widescreen']."' WHERE contentkey='client-widescreen'");

		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Updated CMS Settings (Loader Configuration)','loader.php','".$my_id."','','".$date_full."')") or die(mysql_error());

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
 
<form action='index.php?p=loader&do=save' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Configuraci&oacute;n del cargador</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Direcci&oacute;n IP externa</b><div class='graytext'>La direcci&oacute;n IP <b>externa</b> u hostname donde se encuentra el emulador Holograph. El segundo campo es el puerto, que se detecta autom&aacute;ticamente.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'>
<input type='text' name='ip' value="<?php echo FetchCMSSetting('ip'); ?>" size='30' class='textinput'>&nbsp;:&nbsp;
<input type='text' name='nothing' value="<?php echo FetchServerSetting('server_game_port'); ?>" disabled='disabled' size='6' maxlength='6' class='textinput'>
</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Textos externos</b><div class='graytext'>URL que apunta a tus textos externos.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='texts' value="<?php echo FetchCMSSetting('texts'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Variables externas</b><div class='graytext'>URL que apunta a tus variables externas.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='variables' value="<?php echo FetchCMSSetting('variables'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Habbo DCR</b><div class='graytext'>URL que apunta a tu fichero DCR de Habbo. La protecci&oacute;n de dominio cruzado se omitir&aacute; autom&aacute;ticamente donde sea necesario.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='dcr' value="<?php echo FetchCMSSetting('dcr'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>URL de recarga</b><div class='graytext'>URL que apunta al cargador.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='reload_url' value="<?php echo FetchCMSSetting('reload_url'); ?>" size='30' class='textinput'></td>
</tr>

<?php $wide = mysql_query("SELECT * FROM cms_content WHERE contentkey='client-widescreen'");
$rowtje = mysql_fetch_assoc($wide);
?>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Pantalla ancha</b><div class='graytext'>Determina si el cliente de juego debe mostrarse en modo pantalla ancha o no.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='widescreen' class='dropdown'><option value='1'>Activado</option><option <?php if($rowtje['contentvalue'] == 0) { echo "selected=selected"; } ?> value='0'>Desactivado</option></select></td>
</tr>

<?php $V23 = mysql_query("SELECT * FROM cms_system");
$rok = mysql_fetch_assoc($V23);
?>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>V23 loader</b><div class='graytext'>&iquest;Quieres usar el cargador V23 con 'Abriendo nombre del hotel...' o prefieres el cargador antiguo?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='loader' class='dropdown'><option value='1'>V23 loader</option><option <?php if($rok['loader'] == 0) { echo "selected=selected"; } ?> value='0'>Cargador no V23</option></select></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Servidor en localhost</b><div class='graytext'>Si el emulador Holograph se ejecuta en el mismo servidor/ordenador que HoloCMS, pon esto en 'S&iacute;'.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='localhost'  class='dropdown'>
									<option value='1'>S&iacute;</option>
									<option value='0' <?php if(FetchCMSSetting('localhost') == "0"){ echo "selected='selected'"; } ?>>No</option>
								    </select>
</td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Guardar' class='realbutton' accesskey='s'></td></tr>
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