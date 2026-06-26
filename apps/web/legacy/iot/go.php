<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright � 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind. 
+---------------------------------------------------*/

include('../config.php');
include('../core.php');
include('../includes/session.php');

if(!isset($_POST['gosend']) && !isset($_GET['post'])){
	// Let's give the URL that pointless help tool feeling
	if(!isset($_GET['lang'])){
	header("location:go.php?lang=en&country=uk");
	} else if(!isset($_GET['lang'])){
	header("location:go.php?lang=en&country=uk");
	} else if($_GET['lang'] !== "en"){
	header("location:go.php?lang=en&country=uk");
	} else if($_GET['country'] !== "uk"){
	header("location:go.php?lang=en&country=uk");
	}
}

include('header.php');

if(isset($_POST['gosend'])){
$subject = FilterText($_POST['subject']);
$message = FilterText($_POST['message']);
	if(empty($subject) || empty($message)){
	$result = "Por favor, no dejes ning&uacute;n campo en blanco.";
	} else {
	$sql2 = mysql_query("SELECT * FROM cms_help WHERE ip = '".$remote_ip."' and picked_up = '0' LIMIT 1");
	$num2 = mysql_num_rows($sql2);
		if($num2 == "0"){
		$result = "Solicitud enviada. Nos pondremos en contacto contigo en breve; ten paciencia, puede llevar un tiempo. Ten en cuenta que no puedes enviar m&aacute;s solicitudes de soporte hasta que esta sea atendida.";
		$disableform = "1";
		$go_sql = mysql_query("INSERT INTO cms_help (username,message,subject,ip,date,picked_up) VALUES ('".$rawname."','".$message."','".$subject."','".$remote_ip."','".$date_full."','0')") or die(mysql_error());
		} else {
		$result = "No se puede enviar la solicitud de soporte: ya tienes una solicitud pendiente. Int&eacute;ntalo de nuevo m&aacute;s tarde.";
		$disableform = "1";
		}
	}
}

if(isset($_GET['post'])){
$post_id = $_GET['post'];
	$sql2 = mysql_query("SELECT * FROM cms_help WHERE ip = '".$remote_ip."' and picked_up = '0' LIMIT 1");
	$num2 = mysql_num_rows($sql2);
	$sql3 = mysql_query("SELECT * FROM cms_forum_posts WHERE id = '".$post_id."' LIMIT 1");
	$exists = mysql_num_rows($sql3);
	if($exists > 0){ $row3 = mysql_fetch_assoc($sql3); }
		if($num2 == "0"){
			if($_GET['sure'] == "yes" && $exists > 0){
				$result = "Gracias por denunciar esta publicaci&oacute;n.";
				$disableform = "1";
				$page = $_GET['page'];
				if(empty($page) || !is_numeric($page)){ $page = 1; }
				$go_sql = mysql_query("INSERT INTO cms_help (username,message,subject,ip,date,picked_up) VALUES ('".$rawname."','This user has reported an post on the Discussion Board. The Post ID is ".$post_id.", in thread ".$row3['threadid'].".\n\nOriginal Message\n-----------------------------------\n".FilterText($row3['message'])."\n\nURL:\n-----------------------------------\n".$path."viewthread.php?thread=".$row3['threadid']."&page=".$page."#post-".$row3['id']."','DISCUSSION BOARD - REPORTED POST','".$remote_ip."','".$date_full."','0')") or die(mysql_error());
			} elseif($exists < 1){
				$result = "Publicaci&oacute;n no v&aacute;lida.";
				$disableform = "1";
			} else {
				$result = "&iquest;La publicaci&oacute;n que has seleccionado es ofensiva o incumple las normas? &iquest;Seguro que quieres denunciarla?<br /><br /><a href='go.php?do=report&post=".$post_id."&page=".$_GET['page']."&sure=yes'>Continuar</a>";
				$disableform = "1";
			}
		} else {
		$result = "No se puede denunciar la publicaci&oacute;n: ya tienes una solicitud de soporte pendiente. Int&eacute;ntalo de nuevo m&aacute;s tarde. Ten en cuenta que las denuncias de publicaciones tambi&eacute;n cuentan como solicitudes de soporte.";
		$disableform = "1";
		}
}

?>

  <div style="height: 4px;"></div>
  <div style="height: 18px; padding: 0 0 0 12px;">&nbsp;</div>	
  <div class="portlet">
   <div class="portlet-top-process"><div class="portlet-process-header">&nbsp;</div></div>
   <div class="portlet-body-process">
   <div class="imaindiv">
<form method="post" action="go.php"><input type="hidden" name="sid" value="55"><table border="0" cellspacing="0" cellpadding="0" class="ihead">
 <tr>
  <td class="icon"><img src="header_images/Western2/1.gif" alt=" " width="47" height="37" /></td>
  <td class="text"><h2>Haz tu pregunta</h2></td></tr>
</table>
<br>

<table border="0" cellspacing="0" cellpadding="0" class="content-table">
 <tr>
  <td>
   <div class="iinfodiv">
    Puedes contactar con el Soporte al Jugador a trav&eacute;s de este formulario. Se pondr&aacute;n en contacto contigo lo antes posible. Si es urgente, tambi&eacute;n puedes usar el sistema de Llamada de Ayuda dentro del juego.
<br><br>
<?php if(!empty($result)){
echo "<b>".$result."</b>";
echo "<br><br>"; } ?>

<?php if($disableform !== "1"){ ?>
    Asunto:<br>
    <input type="text" name="subject"  size="50" maxlength="50" value="" /><br>
    Tu pregunta/problema:<br>
    <textarea name="message"  class="imessageform"></textarea>
   </div>
<?php } ?>
  <br>
  </td>
 </tr>
</table>
   <div style="float:right;">
   <table height="21" border="0" cellpadding="0" cellspacing="0" class="button">
<?php if($disableform !== "1"){ ?>
    <tr><td class="button-left-side"></td><td class="middle"><input type="submit" class="proceedbutton" name="gosend" value="Enviar" /></td><td class="button-right-side-arrow"></td></tr>
<?php } else { ?>
    <tr><td class="button-left-side"></td><td class="middle"><input type="button" class="proceedbutton" onclick="javascript:window.close()" value="Cerrar" /></td><td class="button-right-side-arrow"></td></tr>
<?php } ?>
   </table>
   </div>
</form>
   </div>
   </div>
   <div class="portlet-bottom-process"></div>
  </div>
 </div>

<?php

include('footer.php');

?>