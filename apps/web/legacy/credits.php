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

$pagename = "Cr&eacute;dits";
$pageid = "6";
$body_id = "home";

include('templates/community/subheader.php');
include('templates/community/header.php');

?>

<div id='container'>
<div id='content'>
<div id='column1' class='column'><div class='habblet-container '>		
<div class='cbb clearfix green '>
	
<h2 class='title'>&iquest;C&oacute;mo obtener cr&eacute;ditos?</h2>
<script src="./web-gallery/static/js/credits.js" type="text/javascript"></script>
<p class="credits-countries-select">
</p>
<ul id="credits-methods">
	<li id="credits-type-promo">
		<h4 class="credits-category-promo">La mejor opci&oacute;n</h4>
		<ul>
				<li class="clearfix even"><div id="method-44" class="credits-method-container">

					<div class="credits-summary" >
						<h3>Pedir a un Moderador</h3>
						<p>Los moderadores est&aacute;n por todo el hotel. Pide a uno y te dar&aacute; un c&oacute;digo regalo. Intr&oacute;ducelo en la derecha.</p>
						
						<p class="credits-read-more" id="method-show-44" style="display: none">Leer m&aacute;s</p>
					</div>
					<div id="method-full-44" class="credits-method-full">

							<p><b>&iquest;Pedir cr&eacute;ditos?</b><br />Ve a ver a un moderador. Si acepta, te dar&aacute; un c&oacute;digo regalo que deber&aacute;s introducir en la celda de la derecha. Recibir&aacute;s cr&eacute;ditos autom&aacute;ticamente.</p>

					</div>
					<script type="text/javascript">
					$("method-show-44").show();
					$("method-full-44").hide();
					</script>
				</div></li>
		</ul>
	</li>
	<li id="credits-type-quick_and_easy">
		<h4 class="credits-category-quick_and_easy">Otras opciones</h4>
		<ul>

				<li class="clearfix odd"><div id="method-1" class="credits-method-container">
					<div class="credits-summary">
						<h3>Recomienda a un amigo</h3>
						<p>Recomienda a un amigo a este hotel y gana cr&eacute;ditos.</p>

						
						<p class="credits-read-more" id="method-show-1" style="display: none">Leer m&aacute;s</p>
					</div>
					<div id="method-full-1" class="credits-method-full">
							<p><b>&iquest;C&oacute;mo hacerlo?</b><br /><br />Obt&eacute;n tu enlace en la p&aacute;gina de inicio y env&iacute;aselo a tus amigos. Cuando se registren, &iexcl;ganar&aacute;s cr&eacute;ditos!</p>
					</div>
					<script type="text/javascript">
					$("method-show-1").show();
					$("method-full-1").hide();
					</script>
				</div></li>
		</ul>
	</li>
	<li id="credits-type-other">
		<h4 class="credits-category-other">Herramientas</h4>
		<ul>

				<li class="clearfix odd"><div id="method-3" class="credits-method-container">
					<div class="credits-summary">
						<div class="credits-tools">
								<a  class="new-button" id="buy-button" href="#"><b>Vaciar mi mano</b><i></i></a>
							
						</div>
						<h3>Vaciar la mano</h3>
						<p>&iquest;La mano llena? Haz clic aqu&iacute; para reiniciarla.</p>
						
						<p class="credits-read-more" id="method-show-3" style="display: none">Leer m&aacute;s</p>
					</div>
					<div id="method-full-3" class="credits-method-full">

							<p><b>&iquest;C&oacute;mo hacerlo?</b><br /><br />Haz clic en el bot&oacute;n de arriba. &iquest;F&aacute;cil, no?</p>
					</div>
					<script type="text/javascript">
					$("method-show-3").show();
					$("method-full-3").hide();
					</script>
				</div></li>
		</ul>
	</li>
</ul>

<script type="text/javascript">
L10N.put("credits.navi.read_more", "Leer m&aacute;s");
L10N.put("credits.navi.close_fulltext", "Cerrar instrucciones");
PaymentMethodHabblet.init();
</script>
	
						
					</div>

				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 

</div>
<div id="column2" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix brown ">
	
							<h2 class="title">Tu monedero
							</h2>
						<div id="purse-habblet">
                                                    <?php if($logged_in){ ?>
	<form method="post" action="credits.php" id="voucher-form">
<ul>
    <li class="even icon-purse">
        <div>Tienes actualmente:</div>
        <span class="purse-balance-amount"><?php echo $myrow['credits']; ?> Cr&eacute;ditos</span>
        <div class="purse-tx"><a href="transactions.php">Mis transacciones</a></div>
    </li>

    <li class="odd">
        <div class="box-content">
            <div>Introduce un c&oacute;digo de cr&eacute;ditos:</div>
            <input type="text" name="voucherCode" value="" id="purse-habblet-redeemcode-string" class="redeemcode" />
            <a href="#" id="purse-redeemcode-button" class="new-button purse-icon" style="float:left"><b><span></span>Go!</b><i></i></a>
        </div>
    </li>
</ul>
<div id="purse-redeem-result">
</div>	</form>
        <?php } else { ?>
        <div class="box-content">Debes estar conectado para ver tu monedero.</div>
        <?php } ?>
</div>

<script type="text/javascript">
	new PurseHabblet();
</script>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
				<div class="habblet-container ">		
	
						
	
						
					
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>			 

			 
				<d<div class="habblet-container ">		
						<div class="cbb clearfix orange ">
	
							<h2 class="title">&iquest;Qu&eacute; son los cr&eacute;ditos de <?php echo $shortname; ?>?
							</h2>

						<div id="credits-promo" class="box-content credits-info">
    <div class="credit-info-text clearfix">
        <img class="credits-image" src="./web-gallery/v2/images/credits/poor.png" alt="" width="77" height="105" />
        <p class="credits-text">Los cr&eacute;ditos de <?php echo $shortname; ?> son la moneda del hotel. Sirven para comprar todo tipo de cosas, desde un pato de goma hasta un teletransportador.
</p>
    </div>
    <p class="credits-text-2">Si tienes alg&uacute;n problema con tus compras, contacta con el staff.</p>
</div>
	
						
					</div>

				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
							 
</div>

<script type="text/javascript">
HabboView.run();
</script>	
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
<?php

include('templates/community/footer.php');

?>
