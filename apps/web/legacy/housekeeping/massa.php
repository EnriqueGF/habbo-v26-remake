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
if(function_exists(SendMUSData) !== true){ include('../includes/mus.php'); }

if(!isset($_POST['category'])){ // do not try to save when it's a category jump
			if(isset($_POST['credits'])) {
				if(is_numeric($_POST['credits'])) {
				$sql = mysql_query("SELECT id,credits FROM users");
				$count = mysql_num_rows($sql);
				while($row = mysql_fetch_assoc($sql)) {
				mysql_query("UPDATE users SET credits = credits + '".$_POST['credits']."' WHERE id='".$row['id']."' LIMIT 1");
				@SendMUSData('UPRC' . $row['id']);
				}
				mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Gave credits to every user, total users: ".$count." (Amount ".$_POST['credits'].")','massa.php','".$my_id."','','".$date_full."')") or die(mysql_error());
				$msg = "Katsjing, todos han recibido sus cr&eacute;ditos. Usuarios procesados: ".$count."";
				}else{
				$msg = "Error, utiliza solo n&uacute;meros.";
				}
				
				
			}elseif(isset($_POST['code']) || isset($_POST['type'])) {
			$sql = mysql_query("SELECT id FROM users");
			$count = mysql_num_rows($sql);
			while($row = mysql_fetch_assoc($sql)) {
			mysql_query("UPDATE users_badges SET iscurrent = '0' WHERE userid='".$row['userid']."' AND iscurrent = '1'") or die(mysql_error());
			mysql_query("INSERT INTO users_badges (userid,badgeid,iscurrent) VALUES('".$row['id']."','".$_POST['code']."','1')") or die(mysql_error());
			@SendMUSData('UPRS' . $row['id']);
			}
			
			if($_POST['type'] == 2) {
			$sql = mysql_query("SELECT id FROM users");
			$count = mysql_num_rows($sql);
			while($row = mysql_fetch_assoc($sql)) {
			$time = time() + ($_POST['time'] * 24 * 60 * 60);
			mysql_query("INSERT INTO cms_badges (userid,days,badgeid) VALUES ('".$row['id']."','".date('Y-m-d', $time)."','".$_POST['code']."')");
			}
			mysql_query("INSERT INTO system_stafflog (action,message,note,userid,timestamp) VALUES ('Housekeeping','Gave badge to every user (for ".$_POST['time']." days), total users: ".$count." (Badge id: ".$_POST['code'].").','massa.php','".$my_id."','".$date_full."')") or die(mysql_error());
			}else{
			mysql_query("INSERT INTO system_stafflog (action,message,note,userid,timestamp) VALUES ('Housekeeping','Gave badge to every user (perm.), total users: ".$count." (Badge id: ".$_POST['code'].").','massa.php','".$my_id."','".$date_full."')") or die(mysql_error());
			}
			
			$msg = "Katsjing, todos han recibido su insignia. Usuarios procesados: ".$count."";
			}elseif(isset($_POST['user']) || isset($_POST['badgec'])) {
			$sql = mysql_query("SELECT id FROM users WHERE name='".$_POST['user']."'");
			if(mysql_num_rows($sql) == 1) {
			$sql = mysql_query("SELECT id FROM users WHERE name='".$_POST['user']."'");
			$row = mysql_fetch_assoc($sql);
			mysql_query("DELETE FROM users_badges WHERE userid='".$row['id']."' AND badgeid='".$_POST['badgec']."'");
			if(mysql_error == 0) {
			$msg = "La insignia ".$_POST['badgec']." se ha quitado correctamente.";
			mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Badge (".$_POST['badgec'].") taken off','massa.php','".$my_id."','".$row['id']."','".$date_full."')") or die(mysql_error());
			}else{
			$msg = "Este usuario no tiene esta insignia.";
			}
			}else{
			$msg = "El usuario no existe.";
			}
		}elseif(isset($_POST['take'])) {
	$sql = mysql_query("SELECT * FROM users_badges WHERE badgeid='".$_POST['take']."'");
	$count = mysql_num_rows($sql);
	while($row = mysql_fetch_assoc($sql)) {
	mysql_query("DELETE FROM users_badges WHERE badgeid='".$_POST['take']."'");
	}
	mysql_query("INSERT INTO system_stafflog (action,message,note,userid,timestamp) VALUES ('Housekeeping','Badge taken off from all users who had the badge ".$_POST['take'].". Users processed: ".$count."','massa.php','".$my_id."','".$date_full."')") or die(mysql_error());
	$msg = "Las insignias se han quitado correctamente. Usuarios procesados: ".$count."";
	}
}

$pagename = "Content Management";

$catId = $_POST['category'];

if(empty($catId) || !is_numeric($catId) || $catId < 1 || $catId > 5){
    $catId = 1;
} else {
    $catId = $catId;
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
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>

<form action='index.php?p=massa_stuff&do=jumpCategory' method='post' name='Jumper!' id='Jumper!'>
<div class='tableborder'>
<div class='tableheaderalt'>Seleccionar categor&iacute;a</div>
<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
    <td class='tablerow2'  width='100%'  valign='middle'  align='center'>
        <select name='category' class='dropdown'>
            <option value='1' <?php if($catId == "1"){ echo "selected='selected'"; } ?>>Cr&eacute;ditos masivos</option>
            <option value='2' <?php if($catId == "2"){ echo "selected='selected'"; } ?>>Insignia masiva (periodo/perm.)</option>
            <option value='3' <?php if($catId == "3"){ echo "selected='selected'"; } ?>>Quitar insignia a un usuario concreto</option>
			<option value='4' <?php if($catId == "4"){ echo "selected='selected'"; } ?>>Quitar insignia a todos los usuarios con c&oacute;digo [...]</option>
        </select>
        &nbsp;
        <input type='submit' value='Ir' class='realbutton' accesskey='s'>
    </td>
</tr>
</table>
</div>
</form>

<br />
 
<form action='index.php?p=massa_stuff&do=save' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Acciones masivas</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>

<?php if($catId == 1) { ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Cantidad</strong><div class='graytext'>La cantidad de cr&eacute;ditos que recibir&aacute; todo el mundo.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='credits' value="" size='3' maxlength='5' class='textinput'></td>
</tr>
<?php }elseif($catId == 2) { ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>C&oacute;digo de insignia</strong><div class='graytext'>&iquest;Cu&aacute;l es el c&oacute;digo de insignia que recibir&aacute; todo el mundo?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='code' value="" size='3' maxlength='3' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>&iquest;Permanente o por un tiempo especial?</strong><div class='graytext'>&iquest;Recibe todo el mundo la insignia de forma permanente o por un tiempo limitado?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='type' value='1'> Permanente <input type='radio' name='type' value='2'> Por un tiempo especial</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>&iquest;Cu&aacute;nto tiempo la reciben?</strong><div class='graytext'>&iquest;Cu&aacute;nto tiempo reciben la insignia? <b>Nota:</b> si elegiste "permanente", esto no importa.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><SELECT style="COLOR: black; FONT-FAMILY: Verdana" name=time> <OPTION value="1" selected>1 d&iacute;a</OPTION><OPTION value="2">2 d&iacute;as</OPTION><OPTION value="3">3 d&iacute;as</OPTION><OPTION value="4">4 d&iacute;as</OPTION><OPTION value="5">5 d&iacute;as</OPTION><OPTION value="6">6 d&iacute;as</OPTION><OPTION value="7">1 semana</OPTION><OPTION value="14">2 semanas</OPTION><OPTION value="21">3 semanas</OPTION><OPTION value="30">1 mes</OPTION><OPTION value="60">2 meses</OPTION><OPTION value="90">3 meses</OPTION><OPTION value="120">4 meses</OPTION><OPTION value="150">5 meses</OPTION><OPTION value="180">6 meses</OPTION><OPTION value="210">7 meses</OPTION><OPTION value="240">8 meses</OPTION><OPTION value="270">9 meses</OPTION><OPTION value="300">10 meses</OPTION><OPTION value="330">11 meses</OPTION><OPTION value="365">1 a&ntilde;o</OPTION><OPTION value="730">2 a&ntilde;os</OPTION><OPTION value="1095">3 a&ntilde;os</OPTION><OPTION value="1460">4 a&ntilde;os</OPTION><OPTION value="1825">5 a&ntilde;os</OPTION><OPTION value="3650">10 a&ntilde;os</OPTION> <OPTION value="5475">15 a&ntilde;os</OPTION><OPTION value="7300">20 a&ntilde;os</OPTION><OPTION value="9125">25 a&ntilde;os</OPTION></SELECT></td>
</tr>
<?php }elseif($catId == 3) { ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Usuario</strong><div class='graytext'>&iquest;A qui&eacute;n quieres quitarle una insignia?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='user' value="" size='3' maxlength='100' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>C&oacute;digo de insignia</strong><div class='graytext'>&iquest;Qu&eacute; insignia quieres quitar? Introduce el c&oacute;digo de insignia.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='badgec' value="" size='3' maxlength='3' class='textinput'></td>
</tr>
<?php }elseif($catId == 4) { ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>C&oacute;digo de insignia</strong><div class='graytext'>&iquest;Qu&eacute; insignia quieres quitar? Introduce el c&oacute;digo de insignia.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='take' value="" size='3' maxlength='3' class='textinput'></td>
</tr>
<?php }else{ ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Cantidad</strong><div class='graytext'>La cantidad de cr&eacute;ditos que recibir&aacute; todo el mundo.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='credits' value="" size='3' maxlength='5' class='textinput'></td>
</tr>
<?php } ?>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Enviar' class='realbutton' accesskey='s' name='massa'></td></tr>
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