<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright � 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================+
|| # HoloShop 1.0 Coded By Yifan Lu
|+===================================================*/

include('../core.php');

if(!session_is_registered(username)){ echo "<p>\nPor favor, inicia sesi&oacute;n primero.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"habboclub.closeSubscriptionWindow(); return false;\"><b>Hecho</b><i></i></a>\n</p>"; exit; }

$id = FilterText($_POST['optionNumber']);

$row = mysql_fetch_assoc(mysql_query("SELECT * FROM cms_badge_shop WHERE id = '".$id."' LIMIT 1"));

?>

<div id="hc_confirm_box">

    <img src="<?php echo $cimagesurl.$badgesurl.$row['image'].".gif"; ?>" alt="" align="left" style="margin:10px;" />
<p><b><?php echo $row['name']; ?></b></p>
<p>&iquest;Seguro que quieres comprar esta placa por <?php echo $row['cost']; ?> monedas?</p>
<p>
<a href="#" class="new-button" onclick="habboclub.closeSubscriptionWindow(); return false;">
<b>Cancelar</b><i></i></a>
<a href="#" ondblclick="habboclub.showSubscriptionResultWindow(<?php echo $id; ?>,'Purchase Badge'); return false;" onclick="habboclub.showSubscriptionResultWindow(<?php echo $months; ?>,'<?php echo strtoupper($shortname); ?> CLUB'); return false;" class="new-button">
<b>Comprar</b><i></i></a>
</p>

</div>

<div class="clear"></div>



