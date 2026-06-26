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
require_once('../includes/session.php'); 
if(function_exists(SendMUSData) !== true){ include('../includes/mus.php'); } 

$credits = $myrow['credits']; 
$voucher = FilterText($_POST['voucherCode']); 

$check = mysql_query("SELECT type, credits FROM vouchers WHERE voucher = '" . $voucher . "' LIMIT 1") or die(mysql_error()); 
$valid = mysql_num_rows($check); 

if($valid > 0){ 
    $tmp = mysql_fetch_assoc($check); 
    $amount = $tmp[credits]; 
    $resultcode = "green"; 
    if($tmp['type'] == credits) { 
    $credits = $credits + $amount; 
    mysql_query("UPDATE users SET credits = '".$credits."' WHERE name = '" . FilterText($name) . "' LIMIT 1") or die(mysql_error()); 
    mysql_query("DELETE FROM vouchers WHERE voucher = '" . $voucher . "' LIMIT 1") or die(mysql_error()); 
    mysql_query("INSERT INTO `cms_transactions` (`date`, `amount`, `descr`, `userid`) VALUES ('".$date_full."', '".$amount."', 'Credit voucher redeem', '".$my_id."');") or die(mysql_error()); // Appearently ' is not good enough, has to be fancy ` =/ 
    $result = "Has canjeado " . $amount . " cr&eacute;ditos correctamente."; 
    @SendMUSData('UPRC' . $my_id); 
} else { 
    $item = mysql_query("SELECT tid FROM catalogue_items WHERE name_cct = '" . $amount . "' LIMIT 1") or die(mysql_error()); 
    $itemvalid = mysql_num_rows($item); 
    if($itemvalid > 0){ 
    $itemtmp = mysql_fetch_assoc($item); 
    $itemid = $itemtmp[tid]; 
    mysql_query("INSERT INTO furniture (tid, ownerid) VALUES ('".$itemid."', '".$my_id."');") or die(mysql_error()); 
    mysql_query("DELETE FROM vouchers WHERE voucher = '" . $voucher . "' LIMIT 1") or die(mysql_error()); 
    mysql_query("INSERT INTO `cms_transactions` (`date`, `amount`, `descr`, `userid`) VALUES ('".$date_full."', '".$amount."', 'Item voucher redeem', '".$my_id."');") or die(mysql_error()); 
    $result = "Has canjeado este mueble correctamente."; 
    @SendMUSData('UPRH' . $my_id); 
    } else { 
    $resultcode = "red"; 
    $result = "Art&iacute;culo no v&aacute;lido, contacta con un administrador para obtener ayuda."; 
    } 
    } 
} else { 
    $resultcode = "red"; 
    $result = "No se ha encontrado tu c&oacute;digo de canje. Int&eacute;ntalo de nuevo."; 
} 


echo "<ul> 
    <li class=\"even icon-purse\"> 
        <div>Tienes actualmente:</div>
        <span class=\"purse-balance-amount\">" . $credits . " cr&eacute;ditos</span>
        <div class=\"purse-tx\"><a href=\"transactions.php\">Transacciones de la cuenta</a></div> 
    </li> 
</ul> 
<div id=\"purse-redeem-result\"> 
        <div class=\"redeem-error\"> 
            <div class=\"rounded rounded-" . $resultcode . "\"> 
                " . $result . " 
            </div> 
        </div> 
</div>"; 

?>