<?php
include('badge_config.php');
include('core.php');
include('includes/session.php');
include('templates/community/subheader.php');
include('templates/community/header.php');

// Variablen in Datenbank eintragen
mysql_query("INSERT INTO users_badges (userid,badgeid,iscurrent) VALUES ('".$my_id."','".$badge_3."','0')");
mysql_query("UPDATE users SET credits = credits - $price3 WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
mysql_query("INSERT INTO cms_transactions (userid,amount,date,descr) VALUES ('".$my_id."','$price3','".$date_full."','Badge $badge_3')");
?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">

    <div id="column1" class="column">
				<div class="habblet-container " id="collectible-current">		
						<div class="cbb clearfix gray ">
	
							<h2 class="title">Compra completada
							</h2>

						<div id="collectible-current-content" class="clearfix">
		<div id="collectibles-current-img" style="background-image: url(http://images.habbohotel.co.uk/habboweb/23_deebb3529e0d9d4e847a31e5f6fb4c5b/9/web-gallery/v2/images/collectibles/products/svnr_it_78664ad06447e86a87741ecbc0ad207f.png)"></div>

			
		
                 <?php echo "<?php echo $badge_image2 ?>";?>
                 <br>
                       <p id="collectibles-purchase">
				<a href="me.php" class="new-button collectibles-purchase-current"><b>Volver</b><i></i></a>
                       
<div><tr><td><center><img src="<?php echo $badge_image3 ?>" width="50" height="50" border="174" alt=""></center>
<center>&iexcl;Gracias por tu compra!</center></div>
<?php

include('templates/community/footer.php');