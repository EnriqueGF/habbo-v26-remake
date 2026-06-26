<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright � 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

/** MUS SOCKET INCLUDE (INC.MUS)
* @author	Meth0d
* @desc		Send data to the Holograph MUS Socket
* @usage	@SendMUSData();
*/

if (!defined("IN_HOLOCMS")) { header("Location: ../index.php"); exit; }

function SendMUSData($data){

	// Notifying the emulator's MUS is best-effort: never let it break the page.
	if(!function_exists('socket_create')){ return false; }

	// The MUS lives in the emulator container; use its docker host (server_mus_host
	// = 'emu'), NOT cms_system.ip (which is the browser-facing 127.0.0.1).
	$mus_host = FetchServerSetting('server_mus_host');
	$mus_port = FetchServerSetting('server_mus_port');
	if(empty($mus_host)){ $mus_host = '127.0.0.1'; }
	if(!is_numeric($mus_port)){ return false; }

	$sock = @socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));
	if($sock === false){ return false; }

	// Don't hang the page if the emulator is down.
	@socket_set_option($sock, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 2, 'usec' => 0));
	if(@socket_connect($sock, $mus_host, (int)$mus_port) === false){ @socket_close($sock); return false; }

	@socket_send($sock, $data, strlen($data), MSG_DONTROUTE);
	@socket_close($sock);
	return true;
}

?>