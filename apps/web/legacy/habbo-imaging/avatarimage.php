<?php
/**
 * Local avatar image endpoint = caching proxy.
 *
 * The first time a given look is requested it is fetched from the upstream
 * imager (habbo.es) and written to ./cache/. Every subsequent request for the
 * same look is served straight from local disk, so the page stops calling
 * habbo.es. Pre-warm the cache for existing users with prewarm_avatars.php.
 *
 * Accepts both the modern form  (avatarimage?figure=...&size=...) and the old
 * pre-rendered form (avatar/<figure>,<params>,<hash>.gif) via .htaccess rewrite.
 */

$UPSTREAM = 'https://www.habbo.es/habbo-imaging';
$cachedir = __DIR__ . '/cache';

// --- Collect & sanitise params ------------------------------------------------
$figure    = isset($_GET['figure']) ? preg_replace('/[^a-zA-Z0-9.\-]/', '', $_GET['figure']) : '';
$size      = isset($_GET['size']) ? preg_replace('/[^a-z]/', '', $_GET['size']) : 'b';
$direction = isset($_GET['direction']) ? (int)$_GET['direction'] : 4;
$head      = isset($_GET['head_direction']) ? (int)$_GET['head_direction'] : 4;
$gesture   = isset($_GET['gesture']) ? preg_replace('/[^a-zA-Z0-9]/', '', $_GET['gesture']) : 'sml';
$action    = isset($_GET['action']) ? preg_replace('/[^a-zA-Z0-9.=]/', '', $_GET['action']) : '';
$img       = isset($_GET['img']) ? preg_replace('/[^a-zA-Z0-9.,\-]/', '', $_GET['img']) : '';

// Old format: ?img=<figure>,<params>,<hash>.gif  -> serve a gif via /avatar path
$is_gif = false;
if ($img !== '') {
    $is_gif = (substr($img, -4) === '.gif');
    $upstream_url = $UPSTREAM . '/avatar/' . rawurlencode($img);
    $cachekey = 'old_' . md5($img);
    $ext = $is_gif ? 'gif' : 'png';
} else {
    $upstream_url = $UPSTREAM . '/avatarimage?figure=' . urlencode($figure)
        . '&size=' . $size . '&direction=' . $direction
        . '&head_direction=' . $head . '&gesture=' . $gesture
        . ($action !== '' ? ('&action=' . $action) : '');
    $cachekey = md5($figure . '|' . $size . '|' . $direction . '|' . $head . '|' . $gesture . '|' . $action);
    $ext = 'png';
}

$cachefile = $cachedir . '/' . $cachekey . '.' . $ext;
$mime = $is_gif ? 'image/gif' : 'image/png';

// --- Serve from cache if present ----------------------------------------------
if (is_file($cachefile) && filesize($cachefile) > 0) {
    header('Content-Type: ' . $mime);
    header('X-Avatar-Cache: HIT');
    readfile($cachefile);
    exit;
}

// --- Otherwise fetch from upstream, cache, and serve --------------------------
$ctx = stream_context_create(array(
    'http'  => array('method' => 'GET', 'timeout' => 12, 'header' => "User-Agent: Mozilla/5.0\r\n"),
    'https' => array('method' => 'GET', 'timeout' => 12, 'header' => "User-Agent: Mozilla/5.0\r\n"),
    // The PHP 5.6 (jessie) container's CA bundle is too old to validate habbo.es's
    // modern cert, so skip peer verification — fine for fetching public avatar PNGs.
    'ssl'   => array('verify_peer' => false, 'verify_peer_name' => false),
));
$data = @file_get_contents($upstream_url, false, $ctx);

if ($data !== false && strlen($data) > 0) {
    if (!is_dir($cachedir)) { @mkdir($cachedir, 0775, true); }
    @file_put_contents($cachefile, $data);
    header('Content-Type: ' . $mime);
    header('X-Avatar-Cache: MISS');
    echo $data;
    exit;
}

// --- Upstream failed: 1x1 transparent PNG so layouts don't break --------------
header('Content-Type: image/png');
header('X-Avatar-Cache: FAIL');
echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M8AAAMBAQDJ/pLvAAAAAElFTkSuQmCC');
