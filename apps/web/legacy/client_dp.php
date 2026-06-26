<?php
/**
 * DirPlayer client page — runs the V26 Shockwave client in modern browsers
 * via the dirplayer-rs WASM emulator instead of the Shockwave plugin.
 *
 * Reuses core.php for helper functions and the connection config it loads from
 * the DB ($ip, $port, $fport, $dcr, $variables, $texts, $path, $shortname),
 * then handles login + SSO ticket itself and renders a DirPlayer <object>.
 */
include('core.php'); // also calls session_start(); defines GenerateTicket(), etc.

// --- Require an authenticated session -----------------------------------------
if (!isset($_SESSION['username']) || $_SESSION['username'] === '') {
    header('Location: index.php');
    exit;
}
$uname = mysql_real_escape_string($_SESSION['username']);
$ures  = mysql_query("SELECT id, ticket_sso FROM users WHERE name = '" . $uname . "' LIMIT 1") or die(mysql_error());
$urow  = mysql_fetch_assoc($ures);
if (!$urow) { header('Location: index.php'); exit; }
$my_id = $urow['id'];

// --- SSO ticket (the emulator authenticates the client with this) -------------
$myticket = $urow['ticket_sso'];
if (empty($myticket) || $myticket === '0' || strlen($myticket) < 39) {
    $myticket = GenerateTicket();
    mysql_query("UPDATE users SET ticket_sso = '" . $myticket . "', ipaddress_last = '" . $remote_ip . "' WHERE id = '" . $my_id . "' LIMIT 1") or die(mysql_error());
}

// Optional room-forward (links like client.php?forwardId=2&roomId=N).
$fwd_type   = isset($_GET['forwardId']) ? preg_replace('/[^0-9]/', '', $_GET['forwardId']) : '';
$fwd_id     = isset($_GET['roomId'])    ? (int)$_GET['roomId'] : 0;
$do_forward = ($fwd_id > 0 && $fwd_type !== '');

$width  = 960;
$height = 540;
function p($s) { echo htmlspecialchars($s, ENT_QUOTES); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="ISO-8859-1" />
<title><?php p($shortname); ?> - DirPlayer (WebAssembly)</title>
<style>
  html, body { margin: 0; padding: 0; height: 100%; background: #0a1422; font-family: Verdana, sans-serif; overflow: hidden; }
  #bar { color: #cdd7e6; font-size: 11px; text-align: center; height: 20px; line-height: 20px; padding: 0 8px; background: #0a1422; }
  #bar a { color: #7fb2ff; }
  /* Fills all the space below the thin top bar */
  #fit { position: fixed; left: 0; right: 0; top: 20px; bottom: 0; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #000; }
  /* The movie renders at its native 960x540 size; JS scales the whole stage with
     a CSS transform to fill the space. image-rendering:auto gives a smooth
     (anti-aliased) upscale → softer, less jagged text. DirPlayer's pointer
     mapping is transform-aware (patched) so clicks stay exact. */
  #stage { width: <?php echo $width; ?>px; height: <?php echo $height; ?>px; transform-origin: center center; }
  object { display: block; }
  /* Smooth (anti-aliased / bilinear) upscaling. */
  #stage canvas { image-rendering: auto !important; }
</style>
</head>
<body>
<div id="bar">
  <?php p($shortname); ?> en navegador moderno (DirPlayer / WASM) - usuario <b><?php p($_SESSION['username']); ?></b>
  · <a href="me.php">volver</a>
</div>
<div id="fit"><div id="stage">
  <object type="application/x-director" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
    <param name="src" value="<?php p($dcr); ?>">
    <param name="swStretchStyle" value="stage">
    <param name="bgColor" value="#000000">

    <!-- Individual external params: dirplayer's externalParamValue() does a
         direct key lookup, so each key the movie reads must be its own param. -->
    <param name="connection.info.host" value="<?php p($ip); ?>">
    <param name="connection.info.port" value="<?php p($port); ?>">
    <param name="connection.mus.host"  value="<?php p($ip); ?>">
    <param name="connection.mus.port"  value="<?php p($fport); ?>">
    <param name="external.variables.txt" value="<?php p($variables); ?>">
    <param name="external.texts.txt"     value="<?php p($texts); ?>">
    <param name="use.sso.ticket" value="1">
    <param name="sso.ticket"     value="<?php p($myticket); ?>">
    <param name="site.url"   value="<?php p($path); ?>">
    <param name="url.prefix" value="<?php p($path); ?>">
    <param name="client.allow.cross.domain"  value="1">
    <param name="client.notify.cross.domain" value="0">
    <param name="client.connection.failed.url" value="<?php p($path); ?>clientutils.php?key=connection_failed">
    <param name="client.reload.url"            value="<?php p($path); ?>client_dp.php">
    <param name="client.fatal.error.url"       value="<?php p($path); ?>clientutils.php?key=error">

    <!-- Also provide the packed sw* params, in case the movie reads those. -->
    <param name="sw1" value="client.allow.cross.domain=1;client.notify.cross.domain=0">
    <param name="sw2" value="connection.info.host=<?php p($ip); ?>;connection.info.port=<?php p($port); ?>">
    <param name="sw3" value="connection.mus.host=<?php p($ip); ?>;connection.mus.port=<?php p($fport); ?>">
    <param name="sw4" value="site.url=<?php p($path); ?>;url.prefix=<?php p($path); ?>">
    <param name="sw6" value="client.connection.failed.url=<?php p($path); ?>clientutils.php?key=connection_failed;external.variables.txt=<?php p($variables); ?>">
    <param name="sw7" value="external.texts.txt=<?php p($texts); ?>;user_isp=<?php p($remote_ip); ?>">
    <param name="sw8" value="use.sso.ticket=1;sso.ticket=<?php p($myticket); ?>">
<?php if ($do_forward): ?>
    <param name="forward.type" value="<?php p($fwd_type); ?>">
    <param name="forward.id"   value="<?php echo $fwd_id; ?>">
    <param name="sw9" value="forward.type=<?php p($fwd_type); ?>;forward.id=<?php echo $fwd_id; ?>;processlog.url=">
<?php endif; ?>
  </object>
</div></div>

<script>
  // The Shockwave movie talks to the host page through ClientMessageHandler
  // (gotoNetPage "javascript:ClientMessageHandler....()"). We don't load the full
  // legacy JS framework, so provide a permissive no-op stub so those bridge calls
  // succeed silently instead of throwing ReferenceErrors.
  window.ClientMessageHandler = window.ClientMessageHandler || new Proxy(function () {}, {
    get: function () { return function () {}; },
    apply: function () { return undefined; }
  });

  // Scale the whole native-size stage with a CSS transform to fill the space,
  // keeping 16:9. image-rendering:pixelated makes the upscale crisp, and
  // DirPlayer's pointer mapping is patched to be transform-aware so clicks land
  // where you click.
  (function () {
    function fit() {
      var box = document.getElementById('fit'), stage = document.getElementById('stage');
      if (!box || !stage) return;
      var s = Math.min(box.clientWidth / <?php echo $width; ?>, box.clientHeight / <?php echo $height; ?>);
      stage.style.transform = 'scale(' + s + ')';
    }
    window.addEventListener('resize', fit);
    var n = 0, iv = setInterval(function () { fit(); if (++n > 40) clearInterval(iv); }, 350);
    fit();
  })();

  // The Shockwave client opens a TCP socket to the game server (and MUS).
  // Browsers can't do raw TCP, so dirplayer maps host:port -> WebSocket URL
  // via window.__dirplayerFlashConfig.socketProxy, which our ws<->tcp proxy
  // bridges to the emulator:
  //   <?php echo $ip; ?>:<?php echo $port;  ?> -> ws://<host>:8092 -> emu:1232
  //   <?php echo $ip; ?>:<?php echo $fport; ?> -> ws://<host>:8093 -> emu:30000
  window.__dirplayerFlashConfig = window.__dirplayerFlashConfig || {};
  window.__dirplayerFlashConfig.socketProxy = [
    { host: "<?php echo $ip; ?>", port: <?php echo (int)$port;  ?>, proxyUrl: "ws://" + location.hostname + ":8092" },
    { host: "<?php echo $ip; ?>", port: <?php echo (int)$fport; ?>, proxyUrl: "ws://" + location.hostname + ":8093" }
  ];
  window.addEventListener('error', function (e) {
    console.log('[page] error:', e.message);
  });
</script>
<script src="dirplayer/dirplayer-polyfill.js?v=8" data-disable-flash></script>
</body>
</html>
