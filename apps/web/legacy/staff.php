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

$allow_guests = true;

include('core.php');
include('includes/session.php');

$tmp = HoloText(getContent('mod_staff-enabled'), true);
if($tmp !== "1"){
	header("Location: index.php"); exit;
}

$pagename = "El equipo";
$pageid = "8";

include('templates/community/subheader.php');
include('templates/community/header.php');

?>

<div id="container">
	<div id="content">
		<div id="column1" class="column">
			<div class="habblet-container ">
				<div class="cbb clearfix blue">

					<h2 class="title">
						Informaci&oacute;n sobre los rangos
					</h2>
					<div id="notfound-looking-for" class="box-content">
						<p><i><?php echo $sitename ?> est&aacute; dirigido por un equipo de Staffs y moderadores. Estos se encargan de la gesti&oacute;n del hotel y de la animaci&oacute;n.
						</i><br>
						<br>
						<img src="./c_images/Badges/ADM.gif" alt="" align="left"><b>Los Staffs</b> son los responsables principales...
						<br>

						<img src="./c_images/Badges/ADM.gif" alt="" align="left"><b>Los Moderadores</b> moderan el hotel. Se encargan de la justicia y recorren las salas para verificar que se cumplan las normas. Pueden expulsar a cualquier <?php echo $shortname; ?> que no respete las reglas.
						<br>
						<br>
						<img src="./c_images/Badges/HBA.gif" alt="" align="left"><b>Los Golds y Silvers</b> ayudan a los Staffs y Moderadores en sus tareas.
						<br><br>
						<br>
						<img src="./c_images/Badges/XXX.gif" alt="" align="left"><b>Los X's</b> son usuarios normales que ayudan a los nuevos y responden preguntas de la comunidad.
						<br>
						<br>

						<u>Todo el equipo tiene una placa para cada rango, para que los puedas identificar en el hotel.</u></p>
					</div>


				</div>
			</div>
			<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

			<div class="habblet-container ">
				<div class="cbb clearfix green">

					<h2 class="title">&iquest;Unirte al equipo?
					</h2>

					<div id="notfound-looking-for" class="box-content">
					Para unirte al equipo tendr&aacute;s que tener paciencia y esperar los nuevos recrutamientos que se anunciar&aacute;n en las noticias.
				</div>


			</div>
		</div></div>


		<div id="column2" class="column">
			<div class="habblet-container ">		
				<div class="cbb clearfix brown ">

					
					<h2 class="title"><b>El equipo</b> 
					</h2> 
					<div id="notfound-looking-for" class="box-content"> 
					</div> 
					<div class="habblet box-content"> 
						<div class="clearfix red ">	
							
							<br>
							<center><h2 class="title">Los Administradores</h2></center>
							<?php
							$getem = mysql_query("SELECT name,mission,rank,lastvisit,figure,sex,id FROM users WHERE rank = 7 ORDER BY name") or die(mysql_error());
							$staff_members = mysql_num_rows($getem);

							if($staff_members == 7){
								echo "Ning&uacute;n Staff.";
							} else {
								while ($row = mysql_fetch_array($getem, MYSQL_NUM)) {

	if($row[2] == 7 || $row[2] > 7){ // = 7 or higher - Admin
		$row[2] = "<i><b><u>A</u>dministrador</b>";
	}

	if(empty($row[1])){
		$row[1] = "Administrador a tu servicio :)";
	}

	$badge = GetUserBadge($row[0]);
	if($badge !== false){
		$badge = "<img src=\"".$cimagesurl.$badgesurl.$badge.".gif\" /></a>";
	} else {
		$badge= "";
	}

	$groupbadge = GetUserGroupBadge($row[6]);
	if($groupbadge !== false){
		$gbadge = "<a href='group_profile.php?id=".GetUserGroup($row[6])."'><img src='./habbo-imaging/badge.php?badge=".$groupbadge."'></a>";
	} else {
		$gbadge = "";
	}

	if(IsUserOnline($row[6])){
		$online_img = "online_anim";
		$online_caption = "&iexcl;Conectado!";
	} else {
		$online_img = "offline";
		$online_caption = "Desconectado";
	}

	printf("<p><center><img src='/habbo-imaging/avatarimage?figure=%s&size=b&action=wlk,crr=9&direction=2&head_direction=3&gesture=sml&size=s' alt='%s' align='center' /><br/>
		<b><a href='user_profile.php?name=%s'>%s</a></b>&nbsp;<img src='./web-gallery/v2/images/habbo_%s.gif' title='%s' alt='%s' border='0'><br />
		<i>%s</i><br />
		<br />
		<i><b><u>R</u></b>ango</i>: %s<br />
		&Uacute;ltima visita: %s<br />
		<br />%s&nbsp;%s<br /><br /></p>
		",$row[4],$row[0],$row[0],$row[0],$online_img,$online_caption,$online_caption,stripslashes($row[1]),$row[2],$row[3], $badge, $gbadge);
}
}
?>
<br>
<center><h2 class="title">Los Moderadores</h2></center>							
<?php
$getem = mysql_query("SELECT name,mission,rank,lastvisit,figure,sex,id FROM users WHERE rank = 6 ORDER BY name") or die(mysql_error());
$staff_members = mysql_num_rows($getem);

if($staff_members == 6){
	echo "Ning&uacute;n moderador.";
} else {
	while ($row = mysql_fetch_array($getem, MYSQL_NUM)) {

	if($row[2] == 6 || $row[2] > 6){ // = 6 - MOD
		$row[2] = "<i><b><u>M</u>oderador</b>";
	}

	if(empty($row[1])){
		$row[1] = "Moderador a tu servicio :)";
	}

	$badge = GetUserBadge($row[0]);
	if($badge !== false){
		$badge = "<img src=\"".$cimagesurl.$badgesurl.$badge.".gif\" /></a>";
	} else {
		$badge= "";
	}

	$groupbadge = GetUserGroupBadge($row[6]);
	if($groupbadge !== false){
		$gbadge = "<a href='group_profile.php?id=".GetUserGroup($row[6])."'><img src='./habbo-imaging/badge.php?badge=".$groupbadge."'></a>";
	} else {
		$gbadge = "";
	}

	if(IsUserOnline($row[6])){
		$online_img = "online_anim";
		$online_caption = "&iexcl;Conectado!";
	} else {
		$online_img = "offline";
		$online_caption = "Desconectado";
	}

	printf("<p><center><img src='/habbo-imaging/avatarimage?figure=%s&size=b&action=wlk,crr=9&direction=2&head_direction=3&gesture=sml&size=s' alt='%s' align='center' /><br/>
		<b><a href='user_profile.php?name=%s'>%s</a></b>&nbsp;<img src='./web-gallery/v2/images/habbo_%s.gif' title='%s' alt='%s' border='0'><br />
		<i>%s</i><br />
		<br />
		<i><b><u>R</u></b>ango</i>: %s<br />
		&Uacute;ltima visita: %s<br />
		<br />%s&nbsp;%s<br /><br /></p>
		",$row[4],$row[0],$row[0],$row[0],$online_img,$online_caption,$online_caption,stripslashes($row[1]),$row[2],$row[3], $badge, $gbadge);
}
}
?>
<br>
<center><h2 class="title">Los X's</h2></center>
<?php
$getem = mysql_query("SELECT name,mission,rank,lastvisit,figure,sex,id FROM users WHERE rank = 5 ORDER BY name") or die(mysql_error());
$staff_members = mysql_num_rows($getem);

if($staff_members == 5){
	echo "Ning&uacute;n X's.";
} else {
	while ($row = mysql_fetch_array($getem, MYSQL_NUM)) {

	if($row[2] == 5 || $row[5] > 5){ // = 5 or higher - Gold
		$row[2] = "<i><b><u>X</u></b></b>";
	}

	if(empty($row[1])){
		$row[1] = "X a tu servicio :)";
	}

	$badge = GetUserBadge($row[0]);
	if($badge !== false){
		$badge = "<img src=\"".$cimagesurl.$badgesurl.$badge.".gif\" /></a>";
	} else {
		$badge= "";
	}

	$groupbadge = GetUserGroupBadge($row[5]);
	if($groupbadge !== false){
		$gbadge = "<a href='group_profile.php?id=".GetUserGroup($row[6])."'><img src='./habbo-imaging/badge.php?badge=".$groupbadge."'></a>";
	} else {
		$gbadge = "";
	}

	if(IsUserOnline($row[6])){
		$online_img = "online_anim";
		$online_caption = "&iexcl;Conectado!";
	} else {
		$online_img = "offline";
		$online_caption = "Desconectado";
	}

	printf("<p><center><img src='/habbo-imaging/avatarimage?figure=%s&size=b&action=wlk,crr=9&direction=2&head_direction=3&gesture=sml&size=s' alt='%s' align='center' /><br/>
		<b><a href='user_profile.php?name=%s'>%s</a></b>&nbsp;<img src='./web-gallery/v2/images/habbo_%s.gif' title='%s' alt='%s' border='0'><br />
		<i>%s</i><br />
		<br />
		<i><b><u>R</u></b>ango</i>: %s<br />
		&Uacute;ltima visita: %s<br />
		<br />%s&nbsp;%s<br /><br /></p>
		",$row[4],$row[0],$row[0],$row[0],$online_img,$online_caption,$online_caption,stripslashes($row[1]),$row[2],$row[3], $badge, $gbadge);
}
}
?>
<br>	
<center><h2 class="title">Los Gold/Silver</h2></center>
<?php
$getem = mysql_query("SELECT name,mission,rank,lastvisit,figure,sex,id FROM users WHERE rank = 4 ORDER BY name") or die(mysql_error());
$staff_members = mysql_num_rows($getem);

if($staff_members == 4){
	echo "Ning&uacute;n Gold/Silver.";
} else {
	while ($row = mysql_fetch_array($getem, MYSQL_NUM)) {

	if($row[2] == 4|| $row[2] > 4){ // = 4 or higher - Silver
		$row[2] = "<i><b><u>G</u>old</b>/<i><b><u>S</u>ilver</b></i>";
	}

	if(empty($row[1])){
		$row[1] = "Gold/Silver a tu servicio :)";
	}

	$badge = GetUserBadge($row[0]);
	if($badge !== false){
		$badge = "<img src=\"".$cimagesurl.$badgesurl.$badge.".gif\" /></a>";
	} else {
		$badge= "";
	}

	$groupbadge = GetUserGroupBadge($row[13]);
	if($groupbadge !== false){
		$gbadge = "<a href='group_profile.php?id=".GetUserGroup($row[6])."'><img src='./habbo-imaging/badge.php?badge=".$groupbadge."'></a>";
	} else {
		$gbadge = "";
	}

	if(IsUserOnline($row[13])){
		$online_img = "online_anim";
		$online_caption = "&iexcl;Conectado!";
	} else {
		$online_img = "offline";
		$online_caption = "Desconectado";
	}

	printf("<p><center><img src='/habbo-imaging/avatarimage?figure=%s&size=b&action=wlk,crr=9&direction=2&head_direction=3&gesture=sml&size=s' alt='%s' align='center' /><br/>
		<b><a href='user_profile.php?name=%s'>%s</a></b>&nbsp;<img src='./web-gallery/v2/images/habbo_%s.gif' title='%s' alt='%s' border='0'><br />
		<i>%s</i><br />
		<br />
		<i><b><u>R</u></b>ango</i>: %s<br />
		&Uacute;ltima visita: %s<br />
		<br />%s&nbsp;%s<br /><br /></p>
		",$row[4],$row[0],$row[0],$row[0],$online_img,$online_caption,$online_caption,stripslashes($row[1]),$row[2],$row[3], $badge, $gbadge);
}
}
?>
</div></div>
</div>

</div>


</p>

<p></p>

</center>


<?php include('templates/community/footer.php'); ?>
