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

$allow_guests = true;

include('core.php');
include('includes/session.php');
include('badge_config.php');

$pagename = "Tienda de placas";
$pageid = "bshop";

include('templates/community/subheader.php');
include('templates/community/header.php');

?>

<div id='container'>
<div id='content'>
<div id='column1' class='column'><div class='habblet-container '>		
<div class='cbb clearfix red '>

	
                                                                                               <h2 class="title">Tienda de placas
							</h2>
	<p class='credits-countries-select' align='left'>
    
     			<p id="collectibles-purchase"><center>
<u>&iexcl;Bienvenido a la tienda de placas!<br>
Estas son las placas en venta actualmente:</u>
   </center><br>           
<div id='hc-buy-buttons' class='hc-buy-buttons rounded rounded-hcred'><center>
<form>
<table>
<tr><td><img src="<?php echo $badge_image1 ?>" width="50" height="50" border="174" alt="">
<a class="new-button fill" onclick="habboclub.buttonClick(1,'<?php echo strtoupper($shortname); ?> CLUB'); return false;" href="badge_1.php"><b>Comprar</b><i></i></a>
Precio: <?php echo $price1; ?> Cr&eacute;ditos</td></tr>

<tr><td><img src="<?php echo $badge_image2 ?>" width="50" height="50" border="174" alt="">
<a class="new-button fill" onclick="habboclub.buttonClick(1,'<?php echo strtoupper($shortname); ?> CLUB'); return false;" href="badge_2.php"><b>Comprar</b><i></i></a>
Precio: <?php echo $price2; ?> Cr&eacute;ditos</td></tr>

<td><img src="<?php echo $badge_image3 ?>" width="50" height="50" border="174" alt="">
<a class="new-button fill" onclick="habboclub.buttonClick(1,'<?php echo strtoupper($shortname); ?> CLUB'); return false;" href="badge_3.php"><b>Comprar</b><i></i></a>
Precio: <?php echo $price3; ?> Cr&eacute;ditos</td></tr>

<td><img src="<?php echo $badge_image4 ?>" width="50" height="50" border="174" alt="">
<a class="new-button fill" onclick="habboclub.buttonClick(1,'<?php echo strtoupper($shortname); ?> CLUB'); return false;" href="badge_4.php"><b>Comprar</b><i></i></a>
Precio: <?php echo $price4; ?> Cr&eacute;ditos</td>

</table>
</form>
</center>
<br>
</br>
<br>
</br>
<br>
</br>
</div></div></div></div>

<div id="column2" class="column">
				<div class='habblet-container '>		
						<div class='cbb clearfix blue '>
	
							<h2 class='title'>Informaci&oacute;n de la tienda</h2>
						<div id='credits-promo' class='box-content credits-info'>
    <div class='credit-info-text clearfix'>

    <p><img class="credits-image" src="./web-gallery/info_machine.gif" align="center"/>
Esta tienda te permite comprar placas por unos pocos cr&eacute;ditos. &iexcl;Se a&ntilde;aden nuevas placas constantemente!</p></div>
	
						
					</div>
				</div>

<div class='habblet-container '>		
						<div class='cbb clearfix green '>
	
							<h2 class='title'>&iquest;Comprar una placa?</h2>

						<div id='credits-promo' class='box-content credits-info'>
    <div class='credit-info-text clearfix'>
    <p><img class="credits-image" src="http://archive.habboretroweb.net/imgs/other/objects/mobile_phone.gif" align="left"/>
Para comprar una placa, nada m&aacute;s f&aacute;cil. Elige tu placa y haz clic en "Comprar". </div>
	
						
					</div>
				</div>
       
				<script type='text/javascript'>if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
     
   
<div id="purse-redeem-result">
</div>	</form>
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

			 
			
	

						
					</div>
				</div>
				<script type='text/javascript'>if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
							 
</div>

<?php

include('templates/community/footer.php');

?>
