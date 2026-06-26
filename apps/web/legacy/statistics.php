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

$pagename = "Estad&iacute;sticas del hotel";
$pageid = "10";

include('templates/community/subheader.php');
include('templates/community/header.php');

?>

<div id="container">
	<div id="content">
    <div id="column1" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix blue ">

							<h2 class="title">Estad&iacute;sticas de <?php echo $sitename; ?>
							</h2>
						<div class="habblet box-content">
<h3>Acerca de tu cuenta</h3>

<i>Tu nombre</i>: <?php echo "".$name.""; ?>
<br><br>


<i>Cr&eacute;ditos</i>: <?php echo $myrow['credits']; ?> Cr&eacute;ditos (<a href="transactions.php">Ver transacciones</a>)
<br><br>


<i><?php echo $shortname; ?> Club</i> : <?php if( !IsHCMember($my_id) ){ echo "No formas parte del Club."; } else { echo HCDaysLeft($my_id) . " d&iacute;as de Club"; }?>
<br><br>




<i>Tu rango</i>: <?php
                if($user_rank == "1"){
                $user_rank2 = "Usuario";
                }
                                if($user_rank == "2"){
                $user_rank2 = "Miembro del Club";
                }
                                if($user_rank == "3"){
                $user_rank2 = "Habbo X";
                }
                                if($user_rank == "4"){
                $user_rank2 = "Habbo Silver";
                }
                                if($user_rank == "5"){
                $user_rank2 = "Habbo Gold";
                }
                if($user_rank == "6"){
                $user_rank2 = "Moderador";
                }
                if($user_rank == "7"){
                $user_rank2 = "Administrador";
                 }
                 echo "$user_rank2"; ?>
                </li>
<br><br>







<i>&Uacute;ltima visita</i>: <?php echo $myrow['lastvisit']; ?>


<h3>Acerca del hotel</h3>

<i>Usuarios en l&iacute;nea</i>: <?php echo $online_count; ?> <?php echo $shortname; ?>s en l&iacute;nea.

<h3>Acerca del servidor</h3>

<i>Versi&oacute;n</i>: v<?php echo $holocms['version'] . "&nbsp;" . $holocms['stable']; ?>
<br><br>
<i>Usuarios registrados</i>: <?php echo mysql_evaluate("SELECT COUNT(*) FROM users") ?>
<br><br>
<i>Salas creadas</i>: <?php echo mysql_evaluate("SELECT COUNT(*) FROM rooms"); ?>
	(<?php echo mysql_evaluate("SELECT COUNT(*) FROM rooms WHERE owner IS NULL"); ?> espacio p&uacute;blico)
<br><br>
<i>Mobiliario</i>: <?php echo mysql_evaluate("SELECT COUNT(*) FROM furniture"); ?>
<br><br>
<i>Grupos</i>: <?php echo mysql_evaluate("SELECT COUNT(*) FROM groups_details"); ?>
<br><br>
<i>Usuarios baneados</i>: <?php echo mysql_evaluate("SELECT COUNT(*) FROM users_bans"); ?>
<br><br>
<i>Ecotron</i> : <?php echo FetchServerSetting('recycler_enable', true); ?>
<br><br>
<i>Comercio</i>: <?php echo FetchServerSetting('trading_enable', true); ?>
<br><br>
<i>M&aacute;ximo de conexiones</i>: <?php echo FetchServerSetting('server_game_maxconnections'); ?>
<br><br>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>
<div id="column2" class="column">





<div class='habblet-container '>		
						<div class='cbb clearfix blue '>
	










							<h2 class="title">&iquest;Qu&eacute; son las estad&iacute;sticas?</h2>
						<div id="notfound-looking-for" class="box-content">
    <p><img class="statistics-image" src="./web-gallery/v2/images/hotelicone.gif" align="left" width="60" height="65"/>Las estad&iacute;sticas son la informaci&oacute;n global del hotel: salas creadas, usuarios registrados, rango del usuario, etc.</p>
</div>


					</div>









				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class="habblet-container ">
						<div class="cbb clearfix blue ">

							<h2 class="title">Informaci&oacute;n sobre el CMS</h2>
						<div id="notfound-looking-for" class="box-content">
    <br><u>CMS instalado:</u> HRW Cms&copy;<br><u>Versi&oacute;n:</u> 2.0<br><br><small><i>HRW Cms&copy;, producci&oacute;n de <a href="http://habboretroweb.net">HabboRetroWeb</a>. Todos los derechos reservados.</i></small>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>








</div>



<?php

include('templates/community/footer.php');

?>
