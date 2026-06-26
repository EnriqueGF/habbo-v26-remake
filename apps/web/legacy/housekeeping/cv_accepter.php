<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright © 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

//MODULE pour les recrutement cv crée par Monio62 ou monio de britania!

require_once('../core.php');
if($hkzone !== true){ header("Location: index.php?throwBack=true"); exit; }
if(!session_is_registered(acp)){ header("Location: index.php?p=login"); exit; }

$pagename = "Solicitudes de reclutamiento aceptadas y rechazadas";

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
 <div class='tableborder'>
 <div class='tableheaderalt'><?php echo $sitename; ?> Lista de solicitudes aceptadas y rechazadas</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='10%' align='center'>Aceptado/Rechazado</td>
  <td class='tablesubheader' width='29%'>Apodo</td>
  <td class='tablesubheader' width='30%' align='center'>E-Mail</td>
  <td class='tablesubheader' width='10%' align='center'>Puesto deseado</td>
  <td class='tablesubheader' width='10%' align='center'>Ver</td>
 </tr>
<?php
$retour_cv_accepter = mysql_query("SELECT * FROM cms_recrutement WHERE cv_accepter='Accepter' or  cv_accepter='Refuser' ORDER BY id") or die(mysql_error());
while($donnees_cv_accepter = mysql_fetch_array($retour_cv_accepter)){
?>
  <tr>
  <td class='tablerow2' align='center'><b><?php if($donnees_cv_accepter['cv_accepter'] == 'Accepter'){ echo 'Aceptado'; }else if($donnees_cv_accepter['cv_accepter'] == 'Refuser'){ echo 'Rechazado'; }?></b></td>
  <td class='tablerow2' align='center'><?php echo $donnees_cv_accepter['pseudo']; ?></td>
  <td class='tablerow2' align='center'><a href='mailto:<?php echo $donnees_cv_accepter['email']; ?>'><?php echo $donnees_cv_accepter['email']; ?></a></td>
  <td class='tablerow2' align='center'><?php echo $donnees_cv_accepter['poste_souhaiter']; ?></td>
  <td class='tablerow2' align='center'><a href='index.php?p=cv_poster&id=<?php echo $donnees_cv_accepter['id']; ?>'><img src='./images/edit.gif' alt='Ver CV'></a></td>
  </tr>
<?php
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