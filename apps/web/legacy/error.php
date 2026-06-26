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

$allow_guests = true;

if($cored != true){
include('./core.php');
include('./includes/session.php');
}
$pageid = "profile";
$pagename = "Page not found";
include('templates/community/subheader.php');
include('templates/community/header.php');
?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="column1" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix red ">

							<h2 class="title">&iexcl;P&aacute;gina no encontrada!
							</h2>
						<div id="notfound-content" class="box-content">
    <p class="error-text">Lo sentimos, pero la p&aacute;gina que buscabas no se ha encontrado.</p> <img id="error-image" src="./web-gallery/v2/images/error.gif" />
    <p class="error-text">Usa el bot&oacute;n 'Atr&aacute;s' para volver a donde estabas.</p>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>
<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix green ">

							<h2 class="title">&iquest;Buscabas...?
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p><b>&iquest;La p&aacute;gina de un amigo o un grupo?</b><br/>
    Comp&uacute;ebalo en la p&aacute;gina de <a href="community.php">Comunidad</a>.</p>

    <p><b>&iquest;Salas interesantes?</b><br/>
    Explora la lista de <a href="community.php">Salas recomendadas</a>.</p>

    <p><b>&iquest;Qu&eacute; les gusta a otros usuarios?</b><br/>
    Echa un vistazo a la lista de <a href="tags.php">Etiquetas populares</a>.</p>

     <p><b>&iquest;C&oacute;mo conseguir cr&eacute;ditos?</b><br/>
    Visita la p&aacute;gina de <a href="credits.php">Cr&eacute;ditos</a>.</p>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>

<?php
include('templates/community/footer.php');
?>