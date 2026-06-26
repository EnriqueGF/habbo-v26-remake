<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright � 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind.
+---------------------------------------------------*/

if (!defined("IN_HOLOCMS")) { header("Location: ../../index.php"); exit; }

?>
<?php if(getContent('enable-flash-promo') == "1"){ ?>
			<div id="process-content">
	        	<div id="column1" class="column">
				<div class="habblet-container " id="create-habbo">

						<div id="create-habbo-flash">
	<div id="create-habbo-nonflash">
        <div id="landing-register-text"><a href="register.php"><span>&iexcl;Crea tu Habbo, es gratis!</span></a></div>
        <div id="landing-promotional-text"><span><?php echo $shortname; ?> es un mundo virtual donde podr&aacute;s conocer gente nueva.</span></div>
    </div>
	<div class="cbb clearfix green" id="habbo-intro-nonflash">
		<h2 class="title">Para sacar el m&aacute;ximo partido a <?php echo $shortname; ?>, haz esto:</h2>
		<div class="box-content">
			<ul>
				<li id="habbo-intro-install" style="display:none"><a href="http://www.adobe.com/go/getflashplayer">Instala Flash Player 8 o superior</a></li>
				<noscript><li>Activa JavaScript</li></noscript>
			</ul>
		</div>
	</div>
</div>

<script type="text/javascript" language="JavaScript">
var swfobj = new SWFObject("./web-gallery/flash/intro/habbos.swf", "ch", "396", "378", "8");
swfobj.addParam("AllowScriptAccess", "always");
swfobj.addParam("wmode", "transparent");
swfobj.addVariable("base_url", "./web-gallery/flash/intro");
swfobj.addVariable("habbos_url", "./xml/promo_habbos.php");
swfobj.addVariable("create_button_text", "<?php echo $locale['register_today']; ?>");
swfobj.addVariable("in_hotel_text", "&iexcl;En l&iacute;nea ahora!");
swfobj.addVariable("slogan", "<?php echo $locale['slogan']; ?>");
swfobj.addVariable("video_start", "<?php echo $locale['play']; ?>");
swfobj.addVariable("video_stop", "<?php echo $locale['stop']; ?>");
swfobj.addVariable("button_link", "register.php");
swfobj.addVariable("localization_url", "./xml/landing_intro.xml");
swfobj.addVariable("video_link", "http://habbo.co.uk/flash_22_12/intro/Habbo_intro.swf");
swfobj.write("create-habbo-flash");
HabboView.add(function() {
	if (deconcept.SWFObjectUtil.getPlayerVersion()["major"] >= 8) {
		try { $("habbo-intro-nonflash").hide(); } catch (e) {}
	} else {
		$("habbo-intro-install").show();
	}
});
var PromoHabbos = { track:function(n) { if (!!n && window.pageTracker) { pageTracker._trackPageview("/landingpromo/" + n); } } }
</script>
<?php } else { ?>
			<div id="process-content">
	        	<div id="column1" class="column">
				<div class="habblet-container " id="create-habbo">

						<div id="create-habbo">
	<div id="create-habbo-nonflash">
        <div id="landing-register-text"><a href="register.php"><span>&iexcl;Crea tu Habbo, es gratis!</span></a></div>
        <div id="landing-promotional-text"><span><?php echo $shortname; ?> es un mundo virtual donde podr&aacute;s conocer gente nueva.</span></div>
    </div>
</div>
<?php } ?>



				</div>
<div class="habblet-container ">

						<div class="habblet box-content" id="tag-cloud-slim">
    <span class="tags-habbos-like">A los <?php echo $shortname; ?>s les gusta..</span>

	<?php include('tagcloud.php'); ?>

</div>



				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>


				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>