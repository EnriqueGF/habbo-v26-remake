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

$pagename = "Customer Support";

if($_GET['do'] == "pick" && isset($_GET['key']) && is_numeric($_GET['key'])){

	$viewmode = false;

	$check = mysql_query("SELECT id FROM cms_help WHERE id = '".$_GET['key']."' AND picked_up = '0' LIMIT 1") or die(mysql_error());
	$found = mysql_num_rows($check);

	if($found > 0){
		mysql_query("UPDATE cms_help SET picked_up = '1' WHERE id = '".$_GET['key']."' AND picked_up = '0' LIMIT 1") or die(mysql_error());
		$msg = "Consulta de ayuda recogida correctamente.";
		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Picked up help query (ID: ".$_GET['key'].")','helper.php','".$my_id."','','".$date_full."')") or die(mysql_error());
	} else {
		$msg = "ID inv&aacute;lido o consulta ya recogida.";
	}

} elseif($_GET['x'] == "refreshStatic"){

	$viewmode = false;
	$msg = "Vista general actualizada.";

} elseif($_GET['do'] == "delete" && isset($_GET['key']) && is_numeric($_GET['key'])){

	$viewmode = false;

	$check = mysql_query("SELECT id FROM cms_help WHERE id = '".$_GET['key']."' AND picked_up = '1' LIMIT 1") or die(mysql_error());
	$found = mysql_num_rows($check);

	if($found > 0){
		mysql_query("DELETE FROM cms_help WHERE id = '".$_GET['key']."' AND picked_up = '1' LIMIT 1") or die(mysql_error());
		$msg = "Consulta de ayuda eliminada correctamente.";
		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Deleted help query (ID: ".$_GET['key'].")','helper.php','".$my_id."','','".$date_full."')") or die(mysql_error());
	} else {
		$msg = "ID inv&aacute;lido o consulta a&uacute;n no recogida.<br />Recuerda que todas las consultas <i>deben</i> ser recogidas antes de poder eliminarlas.";
	}

} elseif($_GET['do'] == "view" && isset($_GET['key']) && is_numeric($_GET['key'])){

	$check = mysql_query("SELECT * FROM cms_help WHERE id = '".$_GET['key']."' LIMIT 1") or die(mysql_error());
	$found = mysql_num_rows($check);

	if($found > 0){
		$viewmode = true;
		$viewdata = mysql_fetch_assoc($check);
	} else {
		$viewmode = false;
		$msg = "ID inv&aacute;lido.";
	}

} else {

	$viewmode = false;

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
 
<?php if($viewmode == false){ ?>
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
 
<form action='index.php?p=news_manage&do=save' method='post' name='theAdminForm' id='theAdminForm'>
 <div class='tableborder'>
 <div class='tableheaderalt'>Soporte al usuario - Consultas de ayuda</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='1%' align='center'>ID</td>
  <td class='tablesubheader' width='18%'>Asunto</td>
  <td class='tablesubheader' width='25%' align='center'>Date</td>
  <td class='tablesubheader' width='20%' align='center'>Nombre de usuario</td>
  <td class='tablesubheader' width='15%' align='center'>&iquest;Recogida?</td>
  <td class='tablesubheader' width='20%' align='center'>ID de sala</td>
  <td class='tablesubheader' width='1%' align='center'>Responder</td>
  <td class='tablesubheader' width='1%' align='center'>Eliminar</td>
 </tr>
<?php
$get_articles = mysql_query("SELECT id,username,ip,message,date,picked_up,subject,roomid FROM cms_help ORDER BY id DESC") or die(mysql_error());

while($row = mysql_fetch_assoc($get_articles)){

if(!is_numeric($row['roomid']) || $row['roomid'] < 1){ $roomid = "N/A"; } else { $roomid = $row['roomid']; }
if($row['picked_up'] == 1){ $picked = "S&iacute;"; } else { $picked = "No (<a href='index.php?p=helper&do=pick&key=".$row['id']."'>Recoger</a>)"; }

	printf(" <tr>
  <td class='tablerow1' align='center'>%s</td>
  <td class='tablerow2'><a href='index.php?p=helper&do=view&key=%s'><strong>%s</strong></a></td>
  <td class='tablerow2' align='center'>%s</td>
  <td class='tablerow2' align='center'><a href='user_profile.php?tag=%s' target='_blank'>%s</a></td>
  <td class='tablerow2' align='center'>%s</td>
  <td class='tablerow2' align='center'>%s</td>
  <td class='tablerow2' align='center'><a href='index.php?p=alert&do=quickreply&key=%s'><img src='./images/edit.gif' alt='Respuesta r&aacute;pida'></a></td>
  <td class='tablerow2' align='center'><a href='index.php?p=helper&do=delete&key=%s'><img src='./images/delete.gif' alt='Eliminar'></a></td>															
</tr>", $row['id'], $row['id'], HoloText($row['subject']), $row['date'], $row['username'], $row['username'], $picked, $roomid, $row['id'], $row['id']);
}
?>
 
 </table>
 <div class='tablefooter' align='center'><div class='fauxbutton-wrapper'><span class='fauxbutton'><a href='index.php?p=helper&x=refreshStatic'>Actualizar vista general</a></span></div></div>
</div>
<?php } else { ?>
<form action='index.php?p=helper' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Consulta de ayuda (<?php echo $viewdata['subject']; ?>)</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Nombre de usuario</b><div class='graytext'>El nombre del usuario que envi&oacute; esta consulta.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='attrb' readonly='readonly' value="<?php echo HoloText($viewdata['username']); ?>" size='25' maxlength='25' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Direcci&oacute;n IP</b><div class='graytext'>La direcci&oacute;n IP del usuario que envi&oacute; esta consulta.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='attrb' readonly='readonly' value="<?php echo HoloText($viewdata['ip']); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Fecha</b><div class='graytext'>La fecha y hora en que se envi&oacute; esta consulta.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='attrb' readonly='readonly' value="<?php echo HoloText($viewdata['date']); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>ID de sala</b><div class='graytext'>Si la consulta se envi&oacute; desde el hotel, aqu&iacute; aparece el ID de sala.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='attrb' readonly='readonly' value="<?php if($viewdata['roomid'] > 0){ echo $viewdata['roomid']; } else { echo "N/A"; } ?>" size='30' class='textinput'></td>
</tr>

<tr><td align='left' class='tablesubheader' colspan='2' >Datos</td></tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Mensaje</b><div class='graytext'>La consulta enviada por el usuario a trav&eacute;s de la herramienta de ayuda o CFH en el juego.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='data' cols='60' rows='8' readonly='readonly' wrap='soft' id='sub_desc'   class='multitext'><?php echo HoloText($viewdata['message']); ?></textarea></td>
</tr>

<tr><td align='left' class='tablesubheader' colspan='2' >Opciones</td></tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Recoger</b><div class='graytext'>Si a&uacute;n no la has recogido, puedes hacerlo aqu&iacute;. Recoger una consulta indica que te encargar&aacute;s de ella.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><?php if($viewdata['picked_up'] == 1){ echo "Ya recogida"; } else { echo "<a href='index.php?p=helper&do=pick&key=".$viewdata['id']."'>Recoger</a>"; } ?></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Eliminar</b><div class='graytext'>Si la consulta est&aacute; recogida y resuelta, puedes eliminarla aqu&iacute;.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><?php echo "<a href='index.php?p=helper&do=delete&key=".$viewdata['id']."'>Eliminar</a>"; ?></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Respuesta r&aacute;pida</b><div class='graytext'>Responde r&aacute;pidamente a esta consulta.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><?php echo "<a href='index.php?p=alert&do=quickreply&key=".$viewdata['id']."'>Responder</a>"; ?></td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Volver a la vista general' class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />
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