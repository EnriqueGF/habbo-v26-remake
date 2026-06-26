<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright &copy; 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

include('../core.php');

if(!session_is_registered(username)){ echo "<p>\nPor favor, inicia sesi&oacute;n primero.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"habboclub.closeSubscriptionWindow(); return false;\"><b>Hecho</b><i></i></a>\n</p>"; exit; }

$months = $_POST['optionNumber'];

switch($months){
	case 1: $price = 20; $valid = 1; break;
	case 3: $price = 50; $valid = 1; break;
	case 6: $price = 80; $valid = 1; break;
}

if($valid !== 1){ $price = 20; $months = 1; $valid = 1; }

?>

<div id="hc_confirm_box">

    <img src="./web-gallery/album1/piccolo_happy.gif" alt="" align="left" style="margin:10px;" />
<p><b>Confirmaci&oacute;n</b></p>
<p>&iquest;Quieres <?php echo $months; ?> mes(es) de <?php echo $shortname; ?> Club? (<?php echo $months * 31; ?> d&iacute;as) cuestan <?php echo $price; ?> cr&eacute;ditos. Tienes: <?php echo $myrow['credits']; ?> cr&eacute;ditos.</p>

<p>
<a href="#" class="new-button" onclick="habboclub.closeSubscriptionWindow(); return false;">
<b>Volver</b><i></i></a>
<a href="#" ondblclick="habboclub.showSubscriptionResultWindow(<?php echo $months; ?>,'<?php echo strtoupper($shortname); ?> CLUB'); return false;" onclick="habboclub.showSubscriptionResultWindow(<?php echo $months; ?>,'<?php echo strtoupper($shortname); ?> CLUB'); return false;" class="new-button">
<b>Comprar</b><i></i></a>
</p>

</div>

<div class="clear"></div>



