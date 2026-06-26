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

include('core.php');

if($logged_in){ $key = $_GET['key']; } else { $key = "LogInPlease"; }

if($key == "cacheCheck" || $key == "cache"){
echo "true";
} elseif($key == "connection_failed"){
include('./templates/client/subheader.php');
?>
<body id="popup" class="process-template client_error">
<div id="container">
    <div id="content">
	    <div id="process-content" class="centered-client-error">
	       	<div id="column1" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix orange ">
	
							<h2 class="title">No se pudo conectar a <?php echo $sitename; ?>.
							</h2>
						<div class="box-content">
    <p>Lo sentimos, no podemos conectarte a <?php echo $sitename; ?>. Puede deberse a que tu ordenador est&aacute; bloqueando las conexiones mediante un cortafuegos.
Comprueba con la persona responsable de tu conexi&oacute;n a Internet que las siguientes direcciones est&aacute;n permitidas por el cortafuegos:
Direcci&oacute;n IP: <?php echo $ip; ?>, puerto TCP: <?php echo $port; ?></p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<script type="text/javascript">
HabboView.run();
</script>
<div id="column2" class="column">
</div>

<!--[if lt IE 7]>
<script type="text/javascript">
Pngfix.doPngImageFix();
</script>
<![endif]-->
		</div>
    </div>
</div>

</body>
</html>
<?php } elseif($key == "error"){
include('./templates/client/subheader.php');
?>
<body id="popup" class="process-template client_error">
<div id="container">
    <div id="content">
	    <div id="process-content" class="centered-client-error">
	       	<div id="column1" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix orange ">
	
							<h2 class="title">Error grave
							</h2>
						<div class="box-content">
    <p>Ha ocurrido un error desconocido. Int&eacute;ntalo de nuevo dentro de unos minutos. Si el error persiste, usa la Herramienta de Ayuda para solicitar soporte.</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<script type="text/javascript">
HabboView.run();
</script>
<div id="column2" class="column">
</div>

<!--[if lt IE 7]>
<script type="text/javascript">
Pngfix.doPngImageFix();
</script>
<![endif]-->
		</div>
    </div>
</div>

</body>
</html>
<?php } elseif($key == "LogInPlease" && !$logged_in){
include('./templates/client/subheader.php');
?>
<body id="popup" class="process-template client_error">
<div id="container">
    <div id="content">
	    <div id="process-content" class="centered-client-error">
	       	<div id="column1" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix orange ">
	
							<h2 class="title">Inicia sesi&oacute;n
							</h2>
						<div class="box-content">
    <p align='center'>Para usar el cliente del juego y toda la web, por favor <a href='index.php' target='_self'><strong>inicia sesi&oacute;n</strong></a>! Si has olvidado tu nombre o contrase&ntilde;a, haz clic <a href='forgot.php'>aqu&iacute;</a>.</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<script type="text/javascript">
HabboView.run();
</script>
<div id="column2" class="column">
</div>

<!--[if lt IE 7]>
<script type="text/javascript">
Pngfix.doPngImageFix();
</script>
<![endif]-->
		</div>
    </div>
</div>

</body>
</html>
<?php } ?>