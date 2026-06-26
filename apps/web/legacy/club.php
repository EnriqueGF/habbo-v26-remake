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

$allow_guests = true;

include('core.php');
include('includes/session.php');

$pagename = $shortname . " Club";
$pageid = "7";
$body_id = "home";

/** Commented out - new AJAX HC handling implented as of v2.0
if(isset($_GET['months'])){
$months = $_GET['months'];
	if($months == 1){
		if($myrow['credits'] > 19){
		mysql_query("UPDATE users SET credits = credits - 20 WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
		GiveHC($my_id, 1);
		mysql_query("INSERT INTO cms_transactions (userid,amount,date,descr) VALUES ('".$my_id."','-20','".$date_full."','Club subscription')");
		$hc_alert = "<img src=\"./web-gallery/album1/piccolo_happy.gif\" style=\"float: left;\" height=\"87\" width=\"32\"><b>Purchase Successfull</b><br /><br />You have successfully purchased a subscription of " . $months . " month(s) of " . $shortname . " club!";
		} else {
		$hc_alert = "<b>Erreur</b><br /><br />Tu n'as pas assez de cr&eacute;dits pour t'inscrire";
		}
	} else if($months == 3){
		if($myrow['credits'] > 49){
		mysql_query("UPDATE users SET credits = credits - 50 WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
		GiveHC($my_id, 3);
		mysql_query("INSERT INTO cms_transactions (userid,amount,date,descr) VALUES ('".$my_id."','-50','".$date_full."','Club subscription')");
		$hc_alert = "<img src=\"./web-gallery/album1/piccolo_happy.gif\" style=\"float: left;\" height=\"87\" width=\"32\"><b>Purchase Successfull</b><br /><br />You have successfully purchased a subscription of " . $months . " month(s) of " . $shortname . " club!";
		} else {
		$hc_alert = "<b>Erreur</b><br /><br />Tu n'as pas assez de cr&eacute;dits pour t'inscrire";
		}
	} else if($months == 6){
		if($myrow['credits'] > 79){
		mysql_query("UPDATE users SET credits = credits - 80 WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
		GiveHC($my_id, 6);
		mysql_query("INSERT INTO cms_transactions (userid,amount,date,descr) VALUES ('".$my_id."','-80','".$date_full."','Club subscription')");
		$hc_alert = "<img src=\"./web-gallery/album1/piccolo_happy.gif\" style=\"float: left;\" height=\"87\" width=\"32\"><b>Purchase Successfull</b><br /><br />You have successfully purchased a subscription of " . $months . " month(s) of " . $shortname . " club!";
		} else {
		$hc_alert = "<b>Erreur</b><br /><br />Tu n'as pas assez de cr&eacute;dits pour t'inscrire";
		}
	} else {
	$hc_alert = "<b>An error occured</b><br /><br />Tu dois choiser entre 1, 3 ou 6 mois.";
	}
} */

include('templates/community/subheader.php');
include('templates/community/header.php');

echo "<div id='container'>
	<div id='content'>
		<div id='column1' class='column'>
		<div class='habblet-container '>
						<div class='cbb clearfix hcred '>

							<h2 class='title'>".$shortname." Club: &iexcl;h&aacute;zte HC!</h2>
						<div id ='habboclub-products'>
    <div id='habboclub-clothes-container'>
        <div class='habboclub-extra-image'></div>
        <div class='habboclub-clothes-image'></div>
    </div>

    <div class='clearfix'></div>
    <div id='habboclub-furniture-container'>
        <div class='habboclub-furniture-image'></div>
    </div>
</div>


					</div>
				</div>
				<script type='text/javascript'>if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class='habblet-container '>
						<div class='cbb clearfix lightbrown '>

							<h2 class='title'>Ventajas</h2>
						<div id='habboclub-info' class='box-content'>
    <p>El ".$shortname." Club es el club m&aacute;s exclusivo del hotel. Solo los mejores son aceptados y sus miembros son la envidia del hotel.</p>
    <h3 class='heading'>1. Ropa y accesorios extra</h3>
    <p class='content habboclub-clothing'>Luce un look &uacute;nico con una gran variedad de ropa, accesorios y cortes de pelo exclusivos.</p>
    <h3 class='heading'>2. Muebles gratis</h3>
    <p class='content habboclub-furni'>&iexcl;Cada mes un nuevo mueble de regalo!</p>
    <p class='content'>Nota: si abandonas el Habbo Club y vuelves m&aacute;s tarde, tu suscripci&oacute;n contin&uacute;a desde donde la dejaste.</p>
    <h3 class='heading'>3. Formas de sala exclusivas</h3>
    <p class='content'>&iexcl;Dise&ntilde;os de sala exclusivos para lucir tus muebles!</p>
    <p class='habboclub-room' />
    <h3 class='heading'>4. Acceso prioritario</h3>
    <p class='content'>Entra antes que nadie y accede a salas exclusivas para miembros HC.</p>
    <h3 class='heading'>5. Actualizaciones de p&aacute;gina personal</h3>
    <p class='content'>&iexcl;Adios a los banners de publicidad! Con el Habbo Club tienes widgets y fondos de pantalla HC exclusivos.</p>
    <h3 class='heading'>6. M&aacute;s amigos</h3>
    <p class='content habboclub-communicator'>&iexcl;600 personas! Una lista de amigos enorme.</p>
    <h3 class='heading'>7. Comandos especiales</h3>
    <p class='content habboclub-commands right'>Usa el comando :chooser para ver qui&eacute;n est&aacute; en la sala. &iexcl;Muy &uacute;til!</p>
    <br />
    <p>Usa el comando :furni para ver los muebles de una sala. &iexcl;Incluso los escondidos bajo la cama!


</p>
</div>


					</div>
				</div>
				<script type='text/javascript'>if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>
<div id='column2' class='column'>
				<div class='habblet-container '>
						<div class='cbb clearfix hcred '>

							<h2 class='title'>Mi suscripci&oacute;n</h2>
<div id='hc-membership-info' class='box-content'>";
if($logged_in){
echo "<p>
No eres";

if(!IsHCMember($my_id)){
echo "";
}

echo " miembro del ".$shortname." Club
</p>
<p>";
if(IsHCMember($my_id)){
	echo "Tienes " . HCDaysLeft($my_id) . " d&iacute;as HC";
} else {
	echo "&nbsp;";
}
echo "</p>
</div>";

if($myrow['credits'] == "20" || $myrow['credits'] > 20){ ?>
<div id='hc-buy-container' class='box-content'>
<div id='hc-buy-buttons' class='hc-buy-buttons rounded rounded-hcred'>
<form>
<table>
<tr>
	<td><a class="new-button fill" onclick="habboclub.buttonClick(1,'<?php echo strtoupper($shortname); ?> CLUB'); return false;" href="#"><b>Comprar 1 mes</b><i></i></a></td>
    <td>&nbsp;20 Cr&eacute;ditos</td>
</tr>
<tr>
	<td><a class="new-button fill" onclick="habboclub.buttonClick(3,'<?php echo strtoupper($shortname); ?> CLUB'); return false;" href="#"><b>Comprar 3 meses</b><i></i></a></td>
    <td>&nbsp;50 Cr&eacute;ditos</td>
</tr>
<tr>
	<td><a class="new-button fill" onclick="habboclub.buttonClick(6,'<?php echo strtoupper($shortname); ?> CLUB'); return false;" href="#"><b>Comprar 6 meses</b><i></i></a></td>
    <td>&nbsp;80 Cr&eacute;ditos</td>
</tr>
</table>
</form>
</div>
</div>
<?php } else { ?>
<div id="hc-buy-container" class="box-content">
        <div id="hc-buy-buttons" class="hc-buy-buttons rounded rounded-hcred">
            <form class="subscribe-form" method="post">
                <table width="100%">
                  <p class="credits-notice">Para unirte al <?php echo $shortname; ?> Club necesitas cr&eacute;ditos. El <?php echo $shortname; ?> Club cuesta m&iacute;nimo 20 cr&eacute;ditos.</p>
                  <a class="new-button fill" href="credits.php"><b>&iexcl;Obt&eacute;n cr&eacute;ditos!</b><i></i></a>
                </table>
            </form>
        </div>
    </div>
<?php }
} else {
    echo "Inicia sesi&oacute;n para ver tus estad&iacute;sticas del ".$shortname." Club";
}


echo "					</div>
				</div>
				<script type='text/javascript'>if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class='habblet-container '>





				</div>
				<script type='text/javascript'>if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

"; /*				<div class='habblet-container '>
						<div class='cbb clearfix lightbrown '>

							<h2 class='title'>Discount!</h2>
<div class='box-content'>
Hurrah! A major discount on all ".$shortname." Club subscriptions! Buy one on this page now and save up to 15 credits!
</div>


					</div>
				</div>
				<script type='text/javascript'>if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script> */ echo "

</div>

</div>";

include('templates/community/footer.php');

?>