<?php
/*===================================================+
|| # holocms - website and content management system
|+===================================================+
|| # copyright � 2008 meth0d. all rights reserved.
|| # http://www.meth0d.org
|| this file is powered by prepay
|+===================================================+
|| # holocms is provided "as is" and comes without
|| # warrenty of any kind. holocms is free software!
|+===================================================*/

include('core.php');
include('includes/session.php');

//$screen = $myrow['screen'];
//$language = $myrow['language'];
//$rea = $myrow['rea'];

//if($rea == "disabled"){
//	include('./includes/sso.php');
//}

$ssocheck = mysql_query("select * from users where name = '".$rawname."' and password = '".$rawpass."' limit 1") or die(mysql_error());
$ssocheck2 = mysql_fetch_assoc($ssocheck);
if($ssocheck2['ticket_sso'] == "") {
	if(isset($_get['roomid']) && $_get['forwardid'] == "2"){
		if(isset($_get['wide'])) {
			header("location:reauthenticate.php?forwardid=".$_get['forwardid']."&roomid=".$_get['roomid']."&wide=".$_get['wide']."&flash=habbu");
		} else {
			header("location:reauthenticate.php?forwardid=".$_get['forwardid']."&roomid=".$_get['roomid']."&flash=habbu");
		}
	} elseif(isset($_get['wide'])) {
			header("location:reauthenticate.php?wide=".$_get['wide']."&flash=habbu");
	} else {
			header("location:reauthenticate.php?flash=habbu");
	}
}

if($logged_in){
	require_once('includes/session.php');
} else {
	header("location: clientutils.php?key=loginplease");
	exit;
}

if($online !== "online" && $enable_status_image == "1"){
echo "<font color='white'><center><b>".$sitename." est&aacute; desconectado</b></center></font>";
exit();
}

if($remote_ip == "127.0.0.1" || $remote_ip == "localhost" && $server_on_localhost == 1){
$ip = "127.0.0.1";
}

if(isset($_get['roomid']) && $_get['forwardid'] == "2"){
$roomid = $_get['roomid'];
$checksql = mysql_query("select id from rooms where id = '".$roomid."' limit 1");
$roomexists = mysql_num_rows($checksql);
	if($roomexists > 0){
	$forward = "1";
	echo "<!-- forwarding to room ".$roomid." -->";
	} else {
	$forward = "0";
	echo "<!-- room doesn't exist; not forwarding -->";
	}
} else {
echo "<!-- no room forward requested, normal loader -->";
$forward = "0";
}
?>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Habbo: Baboh Emulator v36 (loader by carlo alberto)</title>

<script type="text/javascript">
var andSoItBegins = (new Date()).getTime();
</script>
    <link rel="shortcut icon" href="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/v2/favicon.ico" type="image/vnd.microsoft.icon" />
    <link rel="alternate" type="application/rss+xml" title="Habbo: RSS" href="http://www.habbo.it/articles/rss.xml" />
<script src="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/static/js/libs2.js" type="text/javascript"></script>

<script src="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/static/js/visual.js" type="text/javascript"></script>
<script src="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/static/js/libs.js" type="text/javascript"></script>
<script src="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/static/js/common.js" type="text/javascript"></script>
<script src="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/static/js/fullcontent.js" type="text/javascript"></script>
<link rel="stylesheet" href="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/v2/styles/style.css" type="text/css" />
<link rel="stylesheet" href="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/v2/styles/buttons.css" type="text/css" />
<link rel="stylesheet" href="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/v2/styles/boxes.css" type="text/css" />
<link rel="stylesheet" href="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/v2/styles/tooltips.css" type="text/css" />


<script type="text/javascript">
document.habboLoggedIn = true;
var habboName = "<?php echo $name ?>";
var ad_keywords = "style,lovable,fun,girls,beautiful,money,gender%3Am,age%3A15";
var habboReqPath = "";
var habboStaticFilePath = "http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery";
var habboImagerUrl = "/habbo-imaging/";
var habboPartner = "";
var habboDefaultClientPopupUrl = "http://www.habbo.co.uk/client";
window.name = "ec446f365e4dbd19d36211caee50f768a5f37094";
if (typeof HabboClient != "undefined") { HabboClient.windowName = "ec446f365e4dbd19d36211caee50f768a5f37094"; }

</script>

<noscript>
    <meta http-equiv="refresh" content="0;url=/client/nojs" />

</noscript>
<link rel="stylesheet" href="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/v2/styles/habboclient.css" type="text/css" />
<link rel="stylesheet" href="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/v2/styles/habboflashclient.css" type="text/css" />
<script src="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/static/js/habboflashclient.js" type="text/javascript"></script>
<script type="text/javascript">
    FlashExternalInterface.loginLogEnabled = true;
    
    FlashExternalInterface.logLoginStep("web.view.start");
    FlashHabboClient.cacheCheck();
    var flashvars = {
         "client.allow.cross.domain" : "0", 
            "client.notify.cross.domain" : "1", 
            "connection.info.host" : "<?php echo $ip ?>", 
            "connection.info.port" : "<?php echo $port?>", 
            "site.url" : "<?php echo $path; ?>", 
            "url.prefix" : "<?php echo $path; ?>", 
            "client.reload.url" : "https://www.habbo.co.uk/account/reauthenticate?page=/flash_client", 
            "client.fatal.error.url" : "http://www.habbo.co.uk/flash_client_error", 
            "client.connection.failed.url" : "http://www.habbo.co.uk/client_connection_failed", 
            "external.hash" : "56aa378e068f2437ba2495e61560534caab0beef", 
            "external.variables.txt" : "http://localhost/cms/vars.txt", 
            "external.texts.txt" : "http://hotel-uk.habbo.com/gamedata/external?id=external_flash_texts", 
            "use.sso.ticket" : "1", 
            "sso.ticket" : "<?php echo $myticket ?>", 
            "processlog.enabled" : "1", 
            "account_id" : "<?php echo $my_id ?>", 
            "client.starting" : "&iexcl;Espera un momento! Habbo se est&aacute; iniciando.",
            "flash.client.url" : "http://images.habbo.com/dcr/r36_20090729_0508_19770_cc6786636b12f1eac34a5217be4d025a/" 
      };
    var params = {
        "base" : "http://images.habbo.com/gordon/RELEASE36-20463-20456-200908241543_623f229365b82a69feda4b981fa1a1e9/",
        "allowScriptAccess" : "always",
        "menu" : "false"        
    };
    
    // workaround for the infamous flash keyboard layout issue
    Prototype.Browser.Win = navigator.userAgent.indexOf("Windows") > -1;
    if (!(Prototype.Browser.Win && (Prototype.Browser.Gecko || Prototype.Browser.WebKit))) {
        params["wmode"] = "opaque";
    }
    swfobject.embedSWF("http://images.habbo.com/gordon/RELEASE36-20463-20456-200908241543_623f229365b82a69feda4b981fa1a1e9/Habbo.swf", "flash-container", "100%", "100%", "9.0.115", "http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/flash/expressInstall.swf", flashvars, params);
</script>

<meta name="description" content="habbo, chat, amigo, amigos, avatar, expresi&oacute;n, virtual, mundo virtual, red social, jugar, juego, juegos, m&uacute;sica, famosos" />
<meta name="keywords" content="habbo, chat, amigo, amigos, avatar, expresi&oacute;n, virtual, mundo virtual, red social, jugar, juego, juegos, m&uacute;sica, famosos" />



<!--[if IE 8]>
<link rel="stylesheet" href="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/v2/styles/ie8.css" type="text/css" />
<![endif]-->
<!--[if lt IE 8]>
<link rel="stylesheet" href="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/v2/styles/ie.css" type="text/css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" href="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/v2/styles/ie6.css" type="text/css" />
<script src="http://images.habbo.com/habboweb/36_cc6786636b12f1eac34a5217be4d025a/8/web-gallery/static/js/pngfix.js" type="text/javascript"></script>
<script type="text/javascript">
try { document.execCommand('BackgroundImageCache', false, true); } catch(e) {}
</script>

<style type="text/css">
body { behavior: url(/js/csshover.htc); }
</style>
<![endif]-->
<meta name="build" content="36-BUILD52 - 06.08.2009 14:52 - it" />

</head>

<body id="client" class="flashclient">

<div id="flash-wrapper">
    <div id="flash-container">
        <div id="content" style="width: 400px; margin: 20px auto 0 auto; display: none">
<div class="cbb clearfix">
    <h2 class="title">Por favor, instala Adobe Flash Player.</h2>
    <div class="box-content">
            <p>Puedes descargar e instalar Adobe Flash Player <a href="http://get.adobe.com/flashplayer/">desde esta URL</a>. Puedes encontrar las instrucciones de instalaci&oacute;n <a href="http://www.adobe.com/products/flashplayer/productinfo/instructions/">haciendo clic aqu&iacute;</a>.</p>

            <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
    </div>
</div>
        </div>
        <script type="text/javascript">
            $('content').show();
        </script>
        <noscript>
            <div style="width: 400px; margin: 20px auto 0 auto; text-align: center">
                <p>Si no se te redirige autom&aacute;ticamente, haz <a href="/client/nojs">clic aqu&iacute;</a></p>
            </div>
        </noscript>
    </div>
    </div>

    <div style="display: none">
<div id="habboCountUpdateTarget">
4,090 Habbo in Hotel
</div>
	<script language="JavaScript" type="text/javascript">
		setTimeout(function() {
			HabboCounter.init(600);
		}, 20000);
	</script>
    </div>
    <script type="text/javascript">
        RightClick.init("flash-wrapper", "flash-container");
    </script>

<script src="http://www.google-analytics.com/ga.js" type="text/javascript"></script>
<script type="text/javascript">
pageTracker = _gat._getTracker("UA-448325-20");
pageTracker._trackPageview();
window.setTimeout("pageTracker._setVar('flash')",2000);

</script>

</body>
</html>