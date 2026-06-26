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

$pagename = "Solicitudes de reclutamiento";

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
<?php
if(isset($_GET['id'])){

    if(is_numeric($_GET['id'])){

	$id_cv = mysql_real_escape_string($_GET['id']); 
	$retour_voir_cv = mysql_query("SELECT * FROM cms_recrutement WHERE id=".$id_cv."") or die(mysql_error());
    $donnees_voir_cv = mysql_fetch_array($retour_voir_cv);

mysql_query("UPDATE cms_recrutement SET lu_cv='1' WHERE id='".$id_cv."'") or die(mysql_error());

//variables
$pseudo = $donnees_voir_cv['pseudo'];
$email = $donnees_voir_cv['email'];
$date = $donnees_voir_cv['date_envoi'];
$poste = $donnees_voir_cv['poste_souhaiter'];
$competences = utf8_encode($donnees_voir_cv['comptences']); //ne pas toucher a utf8 encode c pr encoer els caractere php en utf8 :)
$motivations = utf8_encode($donnees_voir_cv['motivations']);
$age = $donnees_voir_cv['age'];
?>
<div class="tableheaderalt">CV de <?php echo $pseudo; ?></div>
<form action="index.php?p=cv_poster&id=<?php echo $id_cv; ?>" method="post">
<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<?php
if(isset($_POST['accepte_cv'])){

//selectionne l'id du destinataire et de l'expediteur pour le mp priver!

$retour_expediteur = mysql_query("SELECT id FROM users WHERE name='".$_SESSION['hkusername']."'") or die(mysql_error());
$donnees_expediteur = mysql_fetch_array($retour_expediteur);

$id_expediteur = $donnees_expediteur['id'];

$retour_destinataire = mysql_query("SELECT id FROM users WHERE name='".$pseudo."'") or die(mysql_error());
$donnees_destinataire = mysql_fetch_array($retour_destinataire);

$id_destinataire = $donnees_destinataire['id'];

//variables
$accepter_le_cv = $_POST['accepte_cv'];
$pseudo_destinataire = $_POST['pseudo'];
$email_destinataire = $_POST['email'];
$poste_destinataire = $_POST['poste'];
$sujet_mp = 'Tu solicitud de incorporaci&oacute;n en '.$sitename.'';
$date_actuel = date('d/m/Y H\hi s\s');
$mp_a_envoyer = 'Hola '.$pseudo_destinataire.', tu solicitud de incorporaci&oacute;n en el sitio ha sido '.$accepter_le_cv.'. Para m&aacute;s informaci&oacute;n visita el sitio o responde a este correo o mensaje. &iexcl;Que tengas un buen d&iacute;a!';
//on envoi un message priver
mysql_query("INSERT INTO cms_minimail (senderid, to_id, subject, date, message, read_mail, deleted, conversationid) VALUES ('".$id_expediteur."','".$id_destinataire."','".$sujet_mp."','".$date_actuel."','".$mp_a_envoyer."','0','0','0')") or die(mysql_error());

mysql_query("UPDATE cms_recrutement SET cv_accepter='".$_POST['accepte_cv']."' WHERE id='".$id_cv."'") or die(mysql_error());

?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b></b></td>
<?php
    if($accepter_le_cv == 'Accepeter'){
?>
<td class='tablerow2'  width='60%'  valign='middle'><font color="green">Has aceptado su solicitud :), recibir&aacute; un e-mail autom&aacute;ticamente + un mensaje en su buz&oacute;n de entrada para informarle de que ha sido aceptado.</font></td>
<?php
    }else if($accepter_le_cv == 'Refuser'){
?>
<td class='tablerow2'  width='60%'  valign='middle'><font color="red">Has rechazado su solicitud, recibir&aacute; un e-mail autom&aacute;ticamente + un mensaje en su buz&oacute;n de entrada para informarle de que no ha sido aceptado.</font></td>
<?php
    }
?>
</tr>
<?php
}
?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Apodo</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name="pseudo" disabled="disabled" value="<?php echo $pseudo; ?>" size='30' class='textinput'></td>
</tr>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Email</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name="email" disabled="disabled" value="<?php echo $email; ?>" size='30' class='textinput'></td>
</tr>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Fecha de env&iacute;o</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' disabled="disabled" value="<?php echo $date; ?>" size='30' class='textinput'></td>
</tr>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Puesto deseado</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name="poste" disabled="disabled" value="<?php echo $poste; ?>" size='30' class='textinput'></td>
</tr>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Sus competencias</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea disabled="disabled" cols="60" rows="5" wrap="soft" class="multitext"><?php echo $competences; ?></textarea></td>
</tr>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Sus motivaciones</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea disabled="disabled" cols="60" rows="5" wrap="soft" class="multitext"><?php echo $motivations; ?></textarea></td>
</tr>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Su edad</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' disabled="disabled" value="<?php echo $age; ?> a&ntilde;os" size='30' class='textinput'></td>
</tr>
<tr>
<?php
if($donnees_voir_cv['cv_accepter'] == '0'){
?>
<tr>
<td align='center' class='tablesubheader' colspan='2'>
<input type='submit' name="accepte_cv" value='Accepter' class='realbutton' accesskey='s'>
<input type='submit' name="accepte_cv" value='Refuser' class='realbutton' accesskey='s'></td>
</tr>
<?php
}else{
echo '<b><font color="red">Este CV ya ha sido revisado.</font></b>';
}
?>
</form></table></div>
<?php
    }else{
?>
<b>ERROR</b><br/>
<font color="red"><b>&iexcl;Vaya! El CV no existe o has accedido a esta p&aacute;gina de forma incorrecta.</b></font>
<?php
	}
	
}else{
?>
 <div class='tableheaderalt'><?php echo $sitename; ?> Lista de CVs recibidos</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='10%' align='center'>Le&iacute;do/No le&iacute;do</td>
  <td class='tablesubheader' width='29%'>Apodo</td>
  <td class='tablesubheader' width='30%' align='center'>E-Mail</td>
  <td class='tablesubheader' width='20%' align='center'>Fecha de env&iacute;o</td>
  <td class='tablesubheader' width='10%' align='center'>Puesto deseado</td>
  <td class='tablesubheader' width='10%' align='center'>Ver</td>
 </tr>
<?php
$retour_cv = mysql_query("SELECT * FROM cms_recrutement ORDER BY ID") or die(mysql_error());
while($donnees_cv = mysql_fetch_array($retour_cv)){
?>
  <tr>
  <td class='tablerow2' align='center'><u><b><?php if($donnees_cv['lu_cv'] == '1'){ echo 'Le&iacute;do'; }else if($donnees_cv['lu_cv'] == '0'){ echo 'No le&iacute;do'; } ?></b></u></td>
  <td class='tablerow2' align='center'><?php echo $donnees_cv['pseudo']; ?></td>
  <td class='tablerow2' align='center'><a href='mailto:<?php echo$donnees_cv['email']; ?>'><?php echo$donnees_cv['email']; ?></a></td>
  <td class='tablerow2' align='center'><?php echo $donnees_cv['date_envoi']; ?></td>
  <td class='tablerow2' align='center'><?php echo $donnees_cv['poste_souhaiter']; ?></td>
  <td class='tablerow2' align='center'><a href='index.php?p=cv_poster&id=<?php echo $donnees_cv['id']; ?>'><img src='./images/edit.gif' alt='Ver CV'></a></td>
  </tr>
<?php
 }
?>
 
 </table>
<?php
}
?>
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