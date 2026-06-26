<?php
/*
 * The V26 Shockwave client is superseded by the in-browser DirPlayer (WASM)
 * client. Every entry point (enter hotel, room links, create room, reload)
 * is forwarded to client_dp.php, preserving the query string so room
 * forwards (?forwardId=2&roomId=N) keep working.
 *
 * The original Shockwave loader is preserved untouched in client_sw.php
 * (usable with PaleMoon + the Shockwave plugin if ever needed).
 */
$qs = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
header('Location: client_dp.php' . ($qs !== '' ? ('?' . $qs) : ''));
exit;
