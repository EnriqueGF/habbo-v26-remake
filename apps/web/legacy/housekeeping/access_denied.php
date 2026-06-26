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

$pagename = "Access Denied";

@include('subheader.php');
@include('header.php');
?>
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr>
 <td width='100%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->

	<div id='acp-update-wrapper'>
		<div class='homepage_pane_border' id='acp-update-normal'>
		 <div class='homepage_section'>HoloCMS</div>
			<div style='font-size:12px;padding:4px; text-align:left'>
				<p>
					<h3>Acceso denegado</h3>
					Lo sentimos, pero no tienes acceso a esta p&aacute;gina. Esto puede deberse a una de las siguientes razones:<br />
					- &iquest;Est&aacute;s intentando acceder a una secci&oacute;n exclusiva del administrador del sistema?<br />
					- &iquest;Tu rango de usuario es insuficiente para acceder a esta p&aacute;gina?<br />
					<br />
					Si crees que est&aacute;s viendo esta p&aacute;gina por error, contacta con el administrador del sistema.
				</p>
				<p>
					<strong><a href='javascript:history.back(1);'>Volver a la p&aacute;gina anterior</a></strong>
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