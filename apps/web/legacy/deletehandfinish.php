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

<center>
<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="column1" class="column">
				<div class="habblet-container " id="collectible-current">		
						<div class="cbb clearfix blue ">
	
							<h2 class="title">Mano vaciada</h2>

						<div id="collectible-current-content" class="clearfix">
			

<p id="collectibles-purchase">
			<p id="collectibles-purchase">
                 <center>
                  <?php
$loeschen = "DELETE FROM furniture
WHERE roomid= '0' AND ownerid= '$my_id'";
$loesch = mysql_query($loeschen);
echo "&iexcl;Tu mano ha sido vaciada! Recarga el hotel si no es el caso.";
?></center>
<p id="collectibles-purchase">
			<p id="collectibles-purchase">
                 <center>
                   <div class="habblet-button-row clearfix"><a class="new-button" id="delete_hand" href="me.php"><b>&iexcl;Volver al inicio!</b><i></i></a></div>
<Br><br></center>
				
			</p>
	</div>


	
						
					</div>
				</div>
				
</center>
<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
    </div>
    
	<?php include('templates/community/footer.php');

?>
</body>
</html>
