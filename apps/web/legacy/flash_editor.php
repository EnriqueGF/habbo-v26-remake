<?php
/**
 * Standalone host for the Flash look editor (HabboRegistration.swf), rendered
 * with Ruffle (WASM). Isolated in its own page/iframe because Ruffle's polyfill
 * conflicts with the CMS's legacy Prototype.js (which extends native prototypes).
 *
 * Receives figure/gender via query string. When the SWF reports a new look
 * (via ExternalInterface), we forward it to the parent window with postMessage
 * so account.php / register.php can fill their hidden figureData field.
 */
$figure = isset($_GET['figure']) ? preg_replace('/[^a-zA-Z0-9.\-]/', '', $_GET['figure']) : '';
$gender = (isset($_GET['gender']) && strtoupper($_GET['gender']) === 'F') ? 'F' : 'M';
$hc     = (isset($_GET['hc']) && $_GET['hc'] === '1') ? '1' : '0';
?><!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1" />
<style>
  html, body { margin: 0; padding: 0; background: transparent; overflow: hidden; }
  #editor { width: 435px; height: 400px; }
  ruffle-player, #editor > * { width: 435px !important; height: 400px !important; }
</style>
</head>
<body>
<div id="editor"></div>
<script>
  // Configure Ruffle BEFORE loading it. The Habbo editor uses BitmapData.draw to
  // compose the avatar, which the canvas backend doesn't support — force WebGL
  // (falling back to wgpu/webgpu). Also disable Ruffle's page polyfill (not needed,
  // and it conflicts with legacy frameworks).
  // Backend choice: WebGPU renders this SWF correctly AND supports BitmapData.draw
  // (used to compose the avatar). Ruffle's WebGL backend fails to draw this movie,
  // and the canvas backend lacks BitmapData.draw — so prefer wgpu when the browser
  // has WebGPU, otherwise fall back to canvas (shows the avatar, just logs a warning).
  window.RufflePlayer = window.RufflePlayer || {};
  window.RufflePlayer.config = {
    polyfills: false,
    preferredRenderer: (navigator.gpu ? "wgpu" : "canvas"),
    splashScreen: false,          // hide Ruffle's loading logo
    contextMenu: false,           // hide Ruffle's right-click menu
    autoplay: "on",
    unmuteOverlay: "hidden"       // no Ruffle audio-unmute overlay
  };
</script>
<script src="./ruffle/ruffle.js"></script>
<script>
  var EDITOR_PARAMS =
      "figuredata_url=./xml/figuredata.xml"
    + "&draworder_url=./xml/draworder.xml"
    + "&localization_url=./xml/figure_editor.xml"
    + "&figure=<?php echo $figure; ?>"
    + "&gender=<?php echo $gender; ?>"
    + "&showClubSelections=1<?php if($hc === '1'){ echo '&userHasClub=1'; } ?>";

  // Bridge: the SWF calls ExternalInterface functions on this window. We expose
  // the names Habbo's editor is known to use and relay the figure to the parent.
  function relayFigure(figure, gender) {
    try {
      window.parent.postMessage({ type: "habboFigure", figure: figure, gender: gender }, "*");
    } catch (e) {}
  }
  // HabboRegistration.swf reports look changes via ExternalInterface call
  // HabboEditor.setGenderAndFigure(gender, figure)  (gender first, figure second).
  window.HabboEditor = {
    setGenderAndFigure: function(gender, figure){ relayFigure(figure, gender); },
    showOldFigureNotice: function(){ /* no-op */ }
  };

  function initEditor() {
    var ruffle = window.RufflePlayer.newest();
    var player = ruffle.createPlayer();
    document.getElementById("editor").appendChild(player);
    player.style.width = "435px";
    player.style.height = "400px";
    player.load({
      url: "./flash/HabboRegistration.swf",
      parameters: EDITOR_PARAMS,
      base: "./flash/",
      allowScriptAccess: true,
      wmode: "transparent",
      autoplay: "on",
      unmuteOverlay: "hidden",
      letterbox: "off"
    }).catch(function (e) { console.log("Ruffle load error:", e); });
  }
  var _t = setInterval(function () {
    if (window.RufflePlayer && window.RufflePlayer.newest) { clearInterval(_t); initEditor(); }
  }, 100);
</script>
</body>
</html>
