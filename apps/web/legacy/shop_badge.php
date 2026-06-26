<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright &copy; 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================+
|| # HoloShop 1.0 Coded By Yifan Lu
|+===================================================*/

$allow_guests = true;

include('core.php');
include('includes/session.php');

$pagename = "Tienda de placas";
$pageid = "bshop";

include('templates/community/subheader.php');
include('templates/community/header.php');

$search = FilterText($_POST['search']);
$category = FilterText($_GET['category']);
if($category == ""){ $category = 1; }
$page = FilterText($_GET['page']);
if($page == "" || $page < 1){ $page = 1; }
$page = $page - 1;

$userrow = mysql_fetch_assoc(mysql_query("SELECT rank FROM users WHERE id = '".$my_id."' LIMIT 1"));
$rank = $userrow['rank'];

?><script src="web-gallery/static/js/shop_badge.js" type="text/javascript"></script><?php
$pagessql = mysql_query("SELECT * FROM cms_badge_shop GROUP BY category ORDER BY category ASC");
?>

<div id="container">
	<div id="content" style="position: relative; left: 0px; top: 0px;" class="clearfix">

<div class="column" style="width: 212px">
	<div class="habblet-container ">
		<div class="cbb clearfix blue ">
			<h2 class="title">Buscar</h2>
			<div id="credits-safety" class="box-content credits-info">
				<div style="text-align: center">
					<form action="./shop_badge.php" method="post">
						<input type="text" name="search" value="" />
						<input type="submit" value="Buscar" class="submit" id="submit-button" />
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="habblet-container ">
		
		<div class="cbb clearfix blue ">

			<h2 class="title">Categor&iacute;as</h2>
			<div id="credits-safety" class="box-content credits-info">
				<div class="credit-info-text clearfix">
		<?php
		while($row = mysql_fetch_assoc($pagessql)) {
			if($category == $row['category']){
				echo "<strong>".$row['category']."</strong><br />\n";
			}else{
				echo "<a href=\"./shop_badge.php?category=".$row['category']."\">".$row['category']."</a><br />\n";
			}
		}
		?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="column" style="width: 527px">
				<div class="habblet-container ">
						<div class="cbb clearfix notitle ">

<?php if($search == ""){ ?>
<?php if($category == 1){ ?>
		<center>
			<br /><img src="./web-gallery/images/catalogue/headers/Frontpage.gif" />
			<br />Selecciona una categor&iacute;a a la izquierda para comprar placas.
		</center>
<?php }else{ ?>
<div id="credits-safety" class="box-content credits-info">
	<div class="credit-info-text clearfix">
		<center>
<?php
$catname = $category;
$sql = mysql_query("SELECT * FROM cms_badge_shop WHERE category = '".$category."' AND enabled = '1' AND minrank <= '".$rank."' ORDER BY id ASC LIMIT 20 OFFSET ".$page*20);
$i = 0;
		while($row = mysql_fetch_assoc($sql)) { 
		$i++;
        if(IsEven($i)){
            $even = "even";
        } else {
            $even = "odd";
        }		?>
			<li class="<?php echo $even; ?>">
				<table style="width: 100%">
					<tr align="center">
						<td style="width: 25%"><img src="<?php echo $cimagesurl.$badgesurl.$row['image'].".gif"; ?>" /></td>
							<td style="width: 35%"><b><?php echo $row['name']; ?></b><br /><?php echo $row['description']; ?></td>
							<td style="width: 15%"><?php echo $row['cost']; ?></td>
							<td style="width: 25%">
							<a class="new-button fill" onclick="habboclub.buttonClick(<?php echo $row['id']; ?>,'Confirmar compra'); return false;" href="#"><b>Comprar</b><i></i></a>
							</td>
					</tr>
				</table>
			</li>
		<?php } 
$sql = mysql_query("SELECT * FROM cms_badge_shop WHERE category = '".$category."' AND enabled = '1' AND minrank <= '".$rank."'");
$numofitems = mysql_num_rows($sql);
$pages = ceil($numofitems / 20);
$i = 1;
?><center><?php
while($i <= $pages){
if($i == $pages){
	if($page + 1 == $i){
		echo "<strong>".$i."</strong>";
	}else{
		echo "<a href=\"./shop_badge.php?category=".$category."&page=".$i."\">".$i."</a>";
	}
}else{
	if($page + 1 == $i){
		echo "<strong>".$i."</strong> | ";
	}else{
		echo "<a href=\"./shop_badge.php?category=".$category."&page=".$i."\">".$i."</a> | ";
	}
}
$i++;
} ?></center></div>
<?php } ?>
<?php }else{ #Search ?>
<div id="credits-safety" class="box-content credits-info">
	<div class="credit-info-text clearfix">
<?php
$sql = mysql_query("SELECT * FROM cms_badge_shop WHERE (name LIKE '".$search."%' OR description LIKE '%".$search."%') AND enabled = '1' AND minrank <= '".$rank."' ORDER BY id ASC LIMIT 20");
$i = 0;
		while($row = mysql_fetch_assoc($sql)) { 
		$i++;
        if(IsEven($i)){
            $even = "even";
        } else {
            $even = "odd";
        }		?>
			<li class="<?php echo $even; ?>">
				<table style="width: 100%">
					<tr align="center">
					<tr align="center">
						<td style="width: 25%"><img src="<?php echo $cimagesurl.$badgesurl.$row['image'].".gif"; ?>" /></td>
							<td style="width: 35%"><b><?php echo $row['name']; ?></b><br /><?php echo $row['description']; ?></td>
							<td style="width: 15%"><?php echo $row['cost']; ?></td>
							<td style="width: 25%">
							<a class="new-button fill" onclick="habboclub.buttonClick(<?php echo $row['id']; ?>,'Confirmar compra'); return false;" href="#"><b>Comprar</b><i></i></a>
							</td>
					</tr>
				</table>
			</li>
<?php } ?>
<center>Resultados de tu b&uacute;squeda.</center>
</div>
<?php
}
?>
							
						</div>
					</div>
				</div>
	
	<script type="text/javascript">
		HabboView.run();
	</script>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>


</div>
<?php

include('templates/community/footer.php');

?>