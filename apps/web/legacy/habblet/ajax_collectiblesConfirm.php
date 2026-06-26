<?php
include('../core.php');
$sql = mysql_query("SELECT title FROM cms_collectables WHERE month='".date('n')."' OR month='".date('m')."' LIMIT 1");
$row = mysql_fetch_assoc($sql); ?>

<p>
&iquest;Seguro que quieres comprar <?php echo HoloText($row['title']); ?>? Cuesta 25 cr&eacute;ditos.
</p>

<p>
<a href="#" class="new-button" id="collectibles-purchase"><b>Comprar</b><i></i></a>
<a href="#" class="new-button" id="collectibles-close"><b>Cancelar</b><i></i></a>
</p>