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

$pagename = "Transactions";

if(isset($_POST['query'])){

	$query = FilterText($_POST['query']);

	$get_logs = mysql_query("SELECT * FROM cms_transactions WHERE userid = '".$query."' ORDER BY id DESC") or die(mysql_error());
	$results = mysql_num_rows($get_logs);

	if($results > 0){
		$msg = "Mostrando registros de transacciones del usuario..";
	} else {
		$msg = "No se encontr&oacute; nada.";
	}

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
 
<form action='index.php?p=transactions&do=search' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Registros de transacciones</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>ID de usuario</strong><div class='graytext'>El ID del usuario cuyos registros de transacciones deseas ver. Para buscar el ID de un usuario, usa <a href='index.php?p=uid'>la herramienta de ID de usuario</a>.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='query' value="<?php echo $query; ?>" size='30' class='textinput'></td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Obtener registros' class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />

<?php if($results > 0){ ?>
 <div class='tableborder'>
 <div class='tableheaderalt'>Resultados de b&uacute;squeda</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='25%' align='center'>Fecha</td>
  <td class='tablesubheader' width='50%' align='center'>Descripci&oacute;n</td>
  <td class='tablesubheader' width='25%' align='center'>Actividad</td>
 </tr>
<?php

while($row = mysql_fetch_assoc($get_logs)){
	printf(" <tr>
  <td class='tablerow1' align='center'>%s</td>
  <td class='tablerow2' align='center'>%s</td>
  <td class='tablerow2' align='center'>%s</td>
</tr>", $row['date'], $row['descr'], $row['amount']);
}
?>
 
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