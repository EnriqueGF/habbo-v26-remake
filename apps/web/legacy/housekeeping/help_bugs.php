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

require_once('../core.php');
if($hkzone !== true){ header("Location: index.php?throwBack=true"); exit; }
if(!session_is_registered(acp)){ header("Location: index.php?p=login"); exit; }

$pagename = "Help Centre";

@include('subheader.php');
@include('header.php');
?>
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr> <td width='22%' valign='top' id='leftblock'>
 <div>
 <!-- LEFT CONTEXT SENSITIVE MENU -->
<?php @include('helpmenu.php'); ?>
 <!-- / LEFT CONTEXT SENSITIVE MENU -->
 </div>
 </td>
 <td width='78%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->
 
	<div id='acp-update-wrapper'>
		<div class='homepage_pane_border' id='acp-update-normal'>
		 <div class='homepage_section'>Centro de ayuda - Fallos</div>
			<div style='font-size:12px;padding:4px; text-align:left'>
				<p>
					Si has encontrado un fallo en HoloCMS, lo primero que debes hacer es verificar si es realmente un fallo; &iquest;no es un error que podr&iacute;as haber cometido t&uacute;? &iquest;Eres el &uacute;nico que experimenta el problema?<br />
					<br />
					Una vez que hayas verificado que se trata realmente de un fallo, dir&iacute;gete a <a href='http://forum.ragezone.com/f282' target='_blank'>RaGEZONE</a> y localiza el <a href='http://forum.ragezone.com/f353/rel-dev-holocms-revolution-new-holograph-emulator-net-374981/' target='_blank'>hilo de desarrollo de HoloCMS</a>.
					Lo primero que debes hacer es asegurarte de que el fallo no haya sido reportado anteriormente. Si es un fallo conocido, no es necesario reportarlo DE NUEVO. Si est&aacute;s seguro de que es un fallo genuino y que no ha sido reportado antes, simplemente responde en el hilo indicando claramente el fallo.<br />
					<br />
					Evidentemente, el fallo se resolver&aacute; lo antes posible una vez que seamos conscientes de &eacute;l. Adem&aacute;s, gracias de antemano si reportas un fallo.
				</p>
			</div>
		</div>
	</div>
	 </div><!-- / RIGHT CONTENT BLOCK -->
	 </td></tr>
</table>
</div><!-- / OUTERDIV -->
<div align='center'><br />
<?php
$mtime = explode(' ', microtime());
$totaltime = $mtime[0] + $mtime[1] - $starttime;
printf('Tiempo: %.3f', $totaltime);
?>
</div>