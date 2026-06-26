<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright &copy; 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================+
|| # Badge Shop - Copyright &copy; 2008 BobbaLodge,
|| # Of RaGEZONE ;; www.BobbaLodge.servegame.com
|+===================================================*/

$allow_guests = true;

include('core.php');
include('includes/session.php');
include('includes/news_headlines.php');

$pagename = "Vac&iacute;a tu mano!";
$pageid = "hand";

include('templates/community/subheader.php');
include('templates/community/header.php');

?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix ">
    <div id="column1" class="column">
				<div class="habblet-container " id="collectible-current">		
						<div class="cbb clearfix green ">
	
							<h2 class="title">Vaciar la mano</h2>
						<div class="habblet box-content">
<p><strong><?php echo $msg; ?></strong></p>
							<left><h4><?php echo $name; ?>,</h4><br> &iquest;Tienes demasiados muebles? &iquest;Te cansas de esperar 20 minutos para llegar al final de tu mano? &iexcl;STOP! Aqu&iacute; puedes vaciar tu mano.<br><br> Haz clic en "&iexcl;Vac&iacute;o mi mano!" para borrar todos los muebles.						
			

<p id="collectibles-purchase">
			<p id="collectibles-purchase">
                 <center>
                   <form action="deletehandfinish" method="post">
<div class="habblet-button-row clearfix"><a class="new-button" id="delete_hand" href="deletehandfinish.php"><b>&iexcl;Vac&iacute;o mi mano!</b><i></i></a></div>
</form><Br><br></center>

				
			</p>
	</div>


	
						
					</div>
				</div>
				

<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
    </div>
<div id="column2" class="column">
				<div class="habblet-container ">
										<div class="cbb clearfix red ">

											<h2 class="title">Atenci&oacute;n
											</h2>
										<div id="notfound-looking-for" class="box-content">
										Atenci&oacute;n: si haces clic en "Vaciar mi mano" se eliminar&aacute;n todos los muebles. Si lo haces por error, o si todav&iacute;a te quedaba un mueble en la mano, no podremos recuperarlo y no nos hacemos responsables de tu p&eacute;rdida.</b><br />

<br /><img src="./web-gallery/images/frank/sorry.gif" alt="" width="57" height="88" />
                                             




					</div>
				</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
    </div>
	<?php include('templates/community/footer.php');

?>
</body>
</html>
