<?php
include('../core.php');



// Gift check (noob/welcome stuff)
$sql = mysql_query("SELECT noob,gift,sort,roomid,lastgift FROM users WHERE id='".$my_id."' LIMIT 1");
$row = mysql_fetch_assoc($sql);
if($row['gift'] < 3) {
if($row['noob'] == 1) {
	if($row['lastgift'] < date("d-m-Y")) {
		mysql_query("INSERT INTO cms_noobgifts (userid,gift,read) VALUES ('".$my_id."','".$row['gift']."','0')");
		mysql_query("UPDATE users SET lastgift='".date("d-m-Y")."',gift=gift+'1' WHERE id='".$my_id."' LIMIT 1");
	}
}
}


// Now we gonna show again the "countdown" to the new gift.  ?>
<div class="gift-img"><?php if($row['gift'] == 0) { ?><img src="http://images.habbohotel.co.uk/habboweb/<?php echo $habboversion; ?>/web-gallery/v2/images/welcome/newbie_furni/noob_stool_<?php echo $row['sort']; ?>.png" alt="Mi primer taburete Habbo" /><?php }elseif($row['gift'] == 1) { ?><img src="http://images.habbohotel.co.uk/habboweb/23_deebb3529e0d9d4e847a31e5f6fb4c5b/9/web-gallery/v2/images/welcome/newbie_furni/noob_plant.png"><?php } ?></div>
<div class="gift-content-container">
<?php if($row['gift'] < 3) { ?>
<p class="gift-content">
Tu pr&oacute;ximo mueble gratuito ser&aacute; <strong><?php if($row['gift'] == 0) { echo "Mi primer taburete Habbo"; }elseif($row['gift'] == 1) { echo "planta"; } ?></strong>
</p>

<p>
<b>Tiempo restante:</b> <span id="gift-countdown"></span>
</p>

<p class="last">
<a class="new-button green-button" href="client.php?forwardId=2&roomId=<?php echo $row['roomid']; ?>" target="client" onclick="HabboClient.roomForward(this, '<?php echo $row['roomid']; ?>', 'private'); return false;"><b>Ir a tu sala &gt;&gt;</b><i></i></a>
</p>
<br style="clear: both" />
</div>



<?php
// calculate time

$time = time();
$day = date("j");
$month = date("n");
$year = date("y");
$date = mktime(0,0,0, $month, $day, $year);
$timeleft = $date-$time; ?>

<script type="text/javascript">
L10N.put("time.hours", "{0}h");
L10N.put("time.minutes", "{0}min");
L10N.put("time.seconds", "{0}s");
GiftQueueHabblet.init(<?php echo $timeleft; ?>);
</script>

<?php // End calculate time 
}else{
// End free gifts, we're now going to say that?>
<p>
&iquest;C&oacute;mo consigues m&aacute;s muebles para tu sala?
</p>

<p>
Puedes comprar un conjunto de muebles por solo 3 cr&eacute;ditos, que incluye una l&aacute;mpara, una alfombra y dos sillones. &iquest;C&oacute;mo se hace?
</p>

<ul><li>1. Compra cr&eacute;ditos en la secci&oacute;n de <a href="/credits">cr&eacute;ditos</a></li><li>2. Abre el cat&aacute;logo desde la barra de herramientas del Hotel (icono de silla)</li><li>3. Abre la secci&oacute;n de ofertas</li><li>4. Elige el conjunto de muebles que quieras</li><li>5. &iexcl;Gracias por comprar!</li></ul>

<p class="aftergift-img">
  <img src="http://images.habbohotel.co.uk/habboweb/23_deebb3529e0d9d4e847a31e5f6fb4c5b/9/web-gallery/v2/images/giftqueue/aftergifts.png" alt="" width="381" height="63"/>
</p>

<p class="last">
<a class="new-button green-button" href="client.php?forwardId=2&roomId=<?php echo $row['roomid']; ?>" target="client" onclick="HabboClient.roomForward(this, '<?php echo $row['roomid']; ?>', 'private'); return false;"><b>Ir a tu sala &gt;&gt;</b><i></i></a>
</p>

<script type="text/javascript">
HabboView.add(GiftQueueHabblet.initClosableHabblet);
<?php mysql_query("UPDATE users SET noob='0'"); ?>
</script>
<?php } ?>