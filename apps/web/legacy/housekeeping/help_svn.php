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
		 <div class='homepage_section'>Centro de ayuda - Versiones SVN</div>
			<div style='font-size:12px;padding:4px; text-align:left'>
				<p>
					Todas las versiones completas (y en su mayor&iacute;a estables) se proporcionan a los usuarios en formato RAR descargable y se recomiendan como soluci&oacute;n principal. Tambi&eacute;n ofrecemos Versiones de Desarrollo en un repositorio SVN (Subversion), que pueden descargarse f&aacute;cilmente con un cliente SVN.
					<br /><br />
					Aunque las versiones SVN suelen contener m&aacute;s funcionalidades y/o mejoras, son generalmente menos estables y a&uacute;n no est&aacute;n completamente terminadas. Por ello, recomendamos las versiones SVN &uacute;nicamente para desarrolladores y personas interesadas. Ninguna versi&oacute;n SVN est&aacute; pensada para uso en producci&oacute;n y no se ofrece soporte.
					<br /><br />
					Al usar versiones SVN, ten en cuenta lo siguiente:<br />
					- El n&uacute;mero de versi&oacute;n puede no incrementarse; no lo uses como referencia para comprobar actualizaciones<br />
					- No se ofrece soporte para SVN<br />
					- Las versiones SVN no est&aacute;n pensadas para uso en producci&oacute;n bajo ning&uacute;n concepto<br />
					- No podemos garantizar que todo funcione correctamente en versiones SVN, aunque lo mismo aplica a las versiones estables<br />
					<br />
					Si a&uacute;n te interesan las versiones SVN, consulta RaGEZONE para m&aacute;s informaci&oacute;n.
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