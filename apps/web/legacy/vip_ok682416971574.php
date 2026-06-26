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
$pagename = "VIP Club";
$pageid = "vip";
$body_id = "home";

include('templates/community/subheader.php');
include('templates/community/header.php');

?>

<div id='container'>
<div id='content'>
<div id='column1' class='column'><div class='habblet-container '>		
<div class='cbb clearfix red '>
	
<h2 class='title'>Tu fais parti du VIP Club !</h2>
<p class='credits-countries-select' align='center'><img class="credits-image" src="./web-gallery/v2/images/best.gif" align="left"/>
F�licitation !<br/><br>
<b>Tu es maintenant un VIP !</b><br>Tu peux d�s � pr�sent profiter des offres exeptionelles du VIP Club !
<br/><br>
A bient�t :)
</p>

</div></div></div>
  
<div id="column2" class="column">
				<div class='habblet-container '>		
						<div class='cbb clearfix blue '>
	
							<h2 class='title'>C'est quoi le VIP Club ?</h2>
						<div id='credits-promo' class='box-content credits-info'>
    <div class='credit-info-text clearfix'>

    <p><img class="credits-image" src="http://archive.habboretroweb.net/imgs/other/objects/ko-trophy.gif" align="center"/>
Le VIP Club est un rang sp�cial qui t'offre des Privil�ges Uniques par rapport aux membres normaux.</p></div>
	
						
					</div>
				</div>
<div class='habblet-container '>		
						<div class='cbb clearfix green '>
	
							<h2 class='title'>Devenir VIP ?</h2>

						<div id='credits-promo' class='box-content credits-info'>
    <div class='credit-info-text clearfix'>
    <p><img class="credits-image" src="http://archive.habboretroweb.net/imgs/other/objects/mobile_phone.gif" align="left"/>
  Envie de rejoindre le Club VIP ?<br>Il suffit de payer 1 Allopass/Webopass en suivant les instructions � gauche !</div>
	
						
					</div>
				</div>
<div class='habblet-container '>		
						<div class='cbb clearfix pixellightblue '>
	
							<h2 class='title'>Ver los VIP</h2>

						<div id='credits-promo' class='box-content credits-info'>
    <div class='credit-info-text clearfix'>
    <p><img class="credits-image" src="http://archive.habboretroweb.net/imgs/other/objects/pile_of_stuff.gif" align="left"/>
Para ver los VIP solo tienes que <a href="./vip_liste.php">hacer clic aqu&iacute;</a>!</div>
	
						
					</div>
				</div>

</div>
				<script type='text/javascript'>if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

			 
							 
</div></div>

				<script type='text/javascript'>if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
							 
</div>

<script type="text/javascript">
HabboView.run();
</script>	
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
<?php
include('templates/community/footer.php');
?>

<?php
	$retour_habbo = mysql_query("SELECT id, name FROM users WHERE name='".$_SESSION['username']."' ") or die(mysql_error());
    $donnees_habbo = mysql_fetch_array($retour_habbo);
	$habbo = $donnees_habbo['id'];

mysql_query("UPDATE users SET rank='3' WHERE id='".$habbo."'") or die(mysql_error());
mysql_query("INSERT INTO users_badges (userid, badgeid, iscurrent, slotid) VALUES ('".$habbo."','VIP','1','0')") or die(mysql_error());
mysql_query("INSERT INTO users_badges (userid, badgeid, iscurrent, slotid) VALUES ('".$habbo."','DU3','1','0')") or die(mysql_error());
mysql_query("UPDATE users SET credits = credits + '10000' WHERE id='".$my_id."' LIMIT 10"); 
@SendMUSData('UPRC' . $my_id);
?>
