<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright � 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind.
+---------------------------------------------------*/

$is_maintenance = true;

include('core.php');
include("locale/".$language."/maintenance.php");

if($maintenance !== "1"){
header("location:index.php");
exit;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $shortname; ?></title>
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

	
	<link href="web-gallery/v2/styles/maintenance.css" rel="stylesheet" type="text/css" />
	

</head>
<body>

<div id="container">
	<div id="content">
		<div id="header" class="clearfix">
			<h1><span></span></h1>
		</div>
		<div id="process-content">

<div class="fireman">

<h1>&iexcl;Mantenimiento!</h1>

<p>
Lo sentimos, pero no puedes conectarte a <?php echo $shortname; ?> por ahora.<br>
Estamos actualizando <?php echo $shortname; ?>.
</p>

</div>
<div class="tweet-container">

<h2>Donaciones</h2>
<center><a href="maintenance_2.html" target="077dafb0833721abf6ee9921496e73e0118af0c1" onclick="HabboClient.openOrFocus(this); return false;">Haz clic aqu&iacute; para hacer una donaci&oacute;n<i></i></a></center>

<div class="tweet"></div>

</div>

<div id="footer">
<p class="copyright"><p>Powered by HoloCMS &copy; 2008 Meth0d &amp; Parts by Yifan, sisija.<br/>
HABBO es una marca registrada de Sulake Corporation LTD. Todos los derechos reservados.<br />BioCMS es un CMS traducido y modificado por Kiiwi.</br> Por favor, respeta su trabajo y no copies ni elimines este copyright.<br />2008/2009</p></p>
</div>

		</div>
	</div>
</div>

<script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-448325-15";
urchinTracker('/error500');

</script>


<script type='text/javascript'>
$(document).ready(function(){
  $(".tweet").tweet({
    username: "HabboFR",
    count: 5
  });
});
</script>

<?php echo $analytics; ?>
</body>
</html>