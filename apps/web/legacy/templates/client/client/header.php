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

		if(getContent('client-widescreen') == "1"){
        $wide_enabled = true;
} else {
        $wide_enabled = false;
} ?>

<body id="client"<?php if($wide_enabled == true){ echo " class=\"wide\""; } ?>>
<div id="client-topbar" style="display:none">
  <div class="logo"><img src="web-gallery/v2/images/popup/popup_topbar_habbologo.gif" alt="" align="middle"/></div>
  <div class="habbocount"><div id="habboCountUpdateTarget">
<?php echo $online_count; ?> <?php echo $shortname; ?>s en l&iacute;nea
</div>
	<script language="JavaScript" type="text/javascript">
		setTimeout(function() {
			HabboCounter.init(600);
		}, 20000);
	</script>
</div>

   <div class="logout">
<a href="me.php" target="_blank" border="0"><img src="./web-gallery/client/home.gif" style="margin-top: 4px; margin-left: 0px; margin-right: 0px;" alt="Ir a: Inicio" /></a>
<a href="account.php?tab=1" target="_blank" border="0"><img src="./web-gallery/client/dress.gif" style="margin-top: 2px; margin-left: 0px; margin-right: 0px;" alt="Ir a: Ropa" /></a>
<a href="credits.php" target="_blank" border="0"><img src="./web-gallery/client/purse.gif" style="margin-top: 0px; margin-left: 0px; margin-right: 0px;" alt="Ir a: Monedero" /></a>
<a href="account.php?tab=2" target="_blank" border="0"><img src="./web-gallery/client/tools.gif" style="margin-top: 0px; margin-left: 0px; margin-right: 0px;" alt="Ir a: Herramientas" /></a>
<a href="iot/go.php" target="_blank" border="0"><img src="./web-gallery/client/help.gif" style="margin-top: 3px; margin-left: 0px; margin-right: 0px;" alt="Ir a: Ayuda" /></a>
<a href="forum.php" target="_blank" border="0"><img src="./web-gallery/client/board.gif" style="margin-top: 2px; margin-left: 0px; margin-right: 0px;" alt="Ir a: Foros" /></a>


</div>

  </div>
