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
		 <div class='homepage_section'>Centro de ayuda - P&aacute;gina principal</div>
			<div style='font-size:12px;padding:4px; text-align:left'>
				<p>
				   Bienvenido al <b>Centro de ayuda</b>. En las siguientes p&aacute;ginas trataremos varios temas con los que podr&iacute;as necesitar ayuda. La mayor&iacute;a de los temas son preguntas frecuentes u otros temas que consideramos &uacute;tiles al usar y configurar HoloCMS y su panel de administraci&oacute;n.<br /><br />Si el Centro de ayuda no proporciona suficiente informaci&oacute;n y/o soporte, siempre puedes hacer tu pregunta en <a href='http://forum.ragezone.com/f282' target='_blank'>RaGEZONE</a> -- all&iacute; es donde los expertos en el tema se re&uacute;nen... y tambi&eacute;n los novatos.<br /><br />Sea cual sea el motivo de tu visita a esta pesta&ntilde;a, espero que el Centro de ayuda pueda ayudarte.
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