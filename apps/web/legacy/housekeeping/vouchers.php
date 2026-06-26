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

$pagename = "Vouchers"; 

if(isset($_POST['voucher'])){ 

    if(!empty($_POST['voucher']) && !empty($_POST['credits'])){ 


            mysql_query("INSERT INTO vouchers (voucher,type,credits) VALUES ('".FilterText($_POST['voucher'])."','".$_POST['type']."','".$_POST['credits']."')") or die(mysql_error()); 
            $msg = "El vale ha sido creado correctamente.";

            mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Created voucher ".FilterText($_POST['voucher'])." worth ".FilterText($_POST['credits'])." credits','vouchers.php','".$my_id."','','".$date_full."')") or die(mysql_error()); 

        } 
    } else { 

        $msg = "Por favor, no dejes ning&uacute;n campo en blanco.";

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
  
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></p></strong><?php } ?> 
  
<form action='index.php?p=vouchers&do=create' method='post' name='theAdminForm' id='theAdminForm'> 
<div class='tableborder'> 
<div class='tableheaderalt'>Crear vale</div>

<?php
function randomVoucher($code) {
$characters = "1234567890abdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$key = $characters[rand(0,71)];
for($i=1;$i<$code;$i++)
	{
		$key .= $characters[rand(0,71)];
	}
	return $key;
}

?>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'> 
<tr> 
<td class='tablerow1'  width='40%'  valign='middle'><strong>C&oacute;digo de canje</strong><div class='graytext'>El c&oacute;digo de canje que el usuario debe introducir para recibir los cr&eacute;ditos.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='voucher' value="<? echo randomVoucher(8); ?>" size='30' class='textinput'></td> 
</tr> 

<tr> 
<td class='tablerow1'  width='40%'  valign='middle'><strong>Tipo</strong><div class='graytext'>El tipo de vale que se puede canjear; hay dos opciones: Cr&eacute;ditos o Art&iacute;culo. Cr&eacute;ditos es un vale de cr&eacute;ditos normal; Art&iacute;culo es un vale de canje de mobiliario.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><select size='1' name='type'> 
<option value='credits'>Cr&eacute;ditos</option>
<option value='item'>Art&iacute;culo</option>
</select></td> 
</tr> 

<tr> 
<td class='tablerow1'  width='40%'  valign='middle'><strong>Cantidad</strong><div class='graytext'>La cantidad de cr&eacute;ditos o el art&iacute;culo que recibir&aacute; el usuario al introducir el c&oacute;digo de canje. Para art&iacute;culos, introduce el nombre CCT correcto del objeto.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='credits' value="" size='30' class='textinput'></td> 
</tr> 

<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Crear vale' class='realbutton' accesskey='s'></td></tr>
</form></table></div><br /> 

<?php 
$get_vouchers = mysql_query("SELECT * FROM vouchers") or die(mysql_error()); 
?> 

 <div class='tableborder'> 
 <div class='tableheaderalt'>Vales existentes</div>
 <table cellpadding='4' cellspacing='0' width='100%'> 
 <tr> 
  <td class='tablesubheader' width='60%' align='center'>C&oacute;digo de canje</td>
  <td class='tablesubheader' width='40%' align='center'>Cr&eacute;ditos</td>
 </tr> 
<?php 

while($row = mysql_fetch_assoc($get_vouchers)){ 
    printf(" <tr> 
  <td class='tablerow1' align='center'>%s</td> 
  <td class='tablerow2' align='center'>%s</td> 
</tr>", $row['voucher'], $row['credits']); 
} 
?> 
  
 </table> 
</div> 
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