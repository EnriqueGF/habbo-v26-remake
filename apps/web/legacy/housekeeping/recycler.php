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

$pagename = "Recycler Options";

if(isset($_POST['recycler_enable'])){

	$recycler_enable = $_POST['recycler_enable'];
	$recycler_minownertime = $_POST['recycler_minownertime'];
	$recycler_session_length = $_POST['recycler_session_length'];
	$recycler_session_expirelength = $_POST['recycler_session_expirelength'];

	if(!empty($recycler_minownertime) && !empty($recycler_session_length) && !empty($recycler_session_expirelength)){

		mysql_query("UPDATE system_config SET sval = '".$recycler_enable."' WHERE skey = 'recycler_enable' LIMIT 1") or die(mysql_error());
		mysql_query("UPDATE system_config SET sval = '".$recycler_minownertime."' WHERE skey = 'recycle_minownertime' LIMIT 1") or die(mysql_error());
		mysql_query("UPDATE system_config SET sval = '".$recycler_session_length."' WHERE skey = 'recycler_session_length' LIMIT 1") or die(mysql_error());
		mysql_query("UPDATE system_config SET sval = '".$recycler_session_expirelength."' WHERE skey = 'recycler_session_expirelength' LIMIT 1") or die(mysql_error());

		$msg = "Ajustes guardados correctamente.";

		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Updated Server Settings (Recycler Options)','recycler.php','".$my_id."','','".$date_full."')") or die(mysql_error());

	} else {

		$msg = "Por favor, no dejes ning&uacute;n campo en blanco. Ajustes no guardados.";

	}

}

@include('subheader.php');
@include('header.php');
?>
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr> <td width='22%' valign='top' id='leftblock'>
 <div>
 <!-- LEFT CONTEXT SENSITIVE MENU -->
<?php @include('servermenu.php'); ?>
 <!-- / LEFT CONTEXT SENSITIVE MENU -->
 </div>
 </td>
 <td width='78%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
 
<form action='index.php?p=recycler&do=save' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Opciones del reciclador</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Activar reciclador</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='recycler_enable'  class='dropdown'>
									<option value='1'>Activado</option>
									<option value='0' <?php if(FetchServerSetting('recycler_enable') == "0"){ echo "selected='selected'"; } ?>>Desactivado</option>
								    </select>

</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Tiempo m&iacute;nimo de propiedad</b><div class='graytext'>El tiempo m&iacute;nimo requerido desde que se compr&oacute; el mueble para que pueda usarse con el reciclador.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='recycler_minownertime' value="<?php echo FetchServerSetting('recycler_minownertime'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Duraci&oacute;n de sesi&oacute;n</b><div class='graytext'>La duraci&oacute;n de una sesi&oacute;n del reciclador.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='recycler_session_length' value="<?php echo FetchServerSetting('recycler_session_length'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Duraci&oacute;n de expiraci&oacute;n de sesi&oacute;n</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='recycler_session_expirelength' value="<?php echo FetchServerSetting('recycler_session_expirelength'); ?>" size='30' class='textinput'></td>
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