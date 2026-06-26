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

$pagename = "HoloCMS";

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
				   <div align='center'>
					<img src='./images/holocms-logo.png' border='0' alt='HoloCMS'><br />
					v<?php echo $holocms['version'] . " " . $holocms['stable']; ?><br />
					Codename '<?php echo $holocms['title']; ?>'<br /><br />
				   </div>
					<br /><br />					
					HoloCMS es una interfaz web para Holograph Emulator o, para promocionarlo un poco: HoloCMS es una soluci&oacute;n gratuita, avanzada y completa de gesti&oacute;n de sitios web y contenidos para Holograph Emulator, publicada bajo la <a href='http://creativecommons.org/licenses/by-nc-nd/3.0/' target='_blank'>licencia Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Internacional</a>. El desarrollo actual est&aacute; a cargo de Yifan Lu.<br /><br />Si est&aacute;s interesado en colaborar con HoloCMS de cualquier forma, ponte en contacto con nosotros.<br /><br /><b>Si has pagado por esto, recupera tu dinero.</b>
				</p>
<?php echo "			<p><strong>Versi&oacute;n de HoloCMS:</strong> ".$holocms['version']." ".$holocms['stable']." [".$holocms['title']."]<br />\n<strong>Fecha de compilaci&oacute;n de HoloCMS:</strong> ".$holocms['date']."<br />\n<strong>Compatibilidad con Holograph Emulator:</strong> Compilado para <a href='http://trac2.assembla.com/holograph/changeset/".$holograph['revision']."' target='_blank'>Revisi&oacute;n ".$holograph['revision']."</a> (".$holograph['type'].")</p>\n"; ?>
				<p>
					<strong><a href='index.php?p=credits'>Cr&eacute;ditos de desarrollo</a></strong>
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