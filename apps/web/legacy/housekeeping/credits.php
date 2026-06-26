<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright © 2008 Meth0d. All rights reserved.
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
		 <div class='homepage_section'>HoloCMS - Cr&eacute;ditos de desarrollo</div>
			<div style='font-size:12px;padding:4px; text-align:left'>
				<p>
					<div align='center'>
						<img src='./images/holocms-logo.png' border='0' alt='HoloCMS'><br />
						v<?php echo $holocms['version'] . " " . $holocms['stable']; ?><br />
						Codename '<?php echo $holocms['title']; ?>'<br /><br />
					</div>
				</p>
				<p>
					<strong>Desarrollo y programaci&oacute;n</strong><br />
					Yifan Lu<br />
					<i>Desarrollador principal y programador</i>
				</p>
				<p>
					<strong>Colaboradores</strong><br />
					<a href='http://www.holographemulator.com'>Nillus / The Holograph Team</a><br />
					<i>Desarrollo de Holograph Emulator</i><br />
					<i>Apoyo e integraci&oacute;n con HoloCMS</i><br />
					<a href='#'>Kreechin (jaym)</a><br />
					<i>Desarrollo del script de insignias de grupo (./habbo-imaging/badge.php), nuevo sistema de vales y sistema de stickers.</i>
					<a href='http://www.daneyb.com'>Roy aka Meth0d</a><br />
					<i>Desarrollador original de HoloCMS y gran programador</i>
				</p>
				<p>
					<strong>Agradecimientos</strong><br />
					<a href='#'>Sisija</a><br />
					<i>Continuaci&oacute;n del proyecto cuando Meth0d se fue.</i><br />
					<a href='#'>All HoloCMS users and supporters</a><br />
					<i>&iexcl;Sin vosotros no estar&iacute;amos aqu&iacute;!</i>
					<!--
					<a href='#'>Aaron</a><br />
					<i>Biggest fag of the friggin' world.</i>
					-->
				</p>
				<p>
					<a href='index.php?p=holocms'>Volver a la <strong>p&aacute;gina de informaci&oacute;n de HoloCMS</a></strong>
				</p>
			<hr />
<center>
<p>
	<i><font color='black' size='1'>
It's like I don't care about nothin' man<br>
Role another blunt, Yeah cuz<br>
(Yeah x 2)<p>
I was gonna clean my room, until I got high<br>
I was gonna get up and find the broom, But then I got high<br>
My room is still messed up And I know why, (why man) 'cuz I got high<br>
Because I got high<br>
Because I got high<p>
I was gonna go to class, before I got high<br>
I coulda' cheated and I coulda passed, but I got high<br>
I'm taking it next semester and I know why, (why man) 'cuz I got high<br>
Because I got high<br>
Because I got high<p>
I was gonna go to work, but then I got high<br>
I just got a new promotion, but I got high<br>
Now I'm selling dope and I know why, (why man) 'cuz I got high<br>
Because I got high<br>
Because I got high<p>
I was gonna go to court, before I got high<br>
I was gonna pay my child support, but then I got high (No you weren't)<br>
They took my whole pay check, and I know why, (why man) 'cuz I got high,<br>
Because I got high<br>
Because I got high<p>
I wasn't gonna run from the cops but I was high, (I'm serious man)<br>
I was gonna pull right over and stop, but I was high<br>
Now I'm a paraplegic, and I know why, (why man) 'cuz I got high<br>
Because I got high<br>
Because I got high<p>
I was gonna pay my car a note, until I got high<br>
I wasn't gonna gamble on the boat, but then I got high<br>
Now the tow truck's pulling away, and I know why, (why man) 'cuz I got high, because I got high, because I got high<br>
Because I got high<br>
Because I got high<p>
I was gonna make love to you, but then I got high, I'm serious<br>
I was gonna eat your pussy to, but then I got high<br>
Now i'm jacking off and I know why, (turn this shit off) 'cuz I got high, because I got high, because I got high</p>
<p>I messed up my entire life, because I got high<br>
I lost my kids and wife , because I got high<br>
Now I'm sleeping on the sidewalk, and I know why, (why man) 'cuz I got high, because I got high, because I got high<p>
I'm gonna stop singing this song, because I'm high<br>
I'm singing this whole thing wrong, because I'm high<br>
And if I don't sell one copy I know why, (why man) 'cuz I'm high,<br>
because I'm high, because I'm high<p>
(Are you really high man?) (he really is high man!) get jiggy with it<br>
O bring it back (say what say what oh, Because I'm high<br>
Because I'm high, because I'm high<p>
Well my name is afroman and I'm from east palmdale,<br>
All the 'Dale weed i be smokin, is mama's hell<br>
I dont belive in Hitler thats what I say' (O my goodness)<br>
So all of you skins, please give me more head<br>
Mother fucker, afro mother fucker m-a-n</p>
<p>A-e-i-o-u and somtimes w<br>
We aint going to sell any of these mother fucking albums cuz<br>
Lets go back to marshal durbans and hang some more chickins cuz fuck it<br>
Fuck the corporate world bitch
	</i></font>
</p>
</center>
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