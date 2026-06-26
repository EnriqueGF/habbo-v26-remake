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

$pagename = "Tienda de muebles";
$pageid = "fshop";

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

if(isset($_POST['quantity']) == true){
$quantity = FilterText($_POST['quantity']);
if(is_numeric($quantity) == false){ $quantity = 1; }
$boughtid = FilterText($_POST['furniID']);
if(function_exists(SendMUSData) !== true){ include('./includes/mus.php'); }
$furnirow = mysql_fetch_assoc(mysql_query("SELECT * FROM catalogue_items WHERE tid = '".$boughtid."' LIMIT 1"));
$price = $furnirow['catalogue_cost'] * $quantity;
$furnicat = mysql_fetch_assoc(mysql_query("SELECT minrank FROM catalogue_pages WHERE indexid = '".$furnirow['catalogue_id_page']."' LIMIT 1"));
if($furnicat['minrank'] <= $user_rank){
if($price <= $myrow['credits']){
mysql_query("UPDATE users SET credits = credits - ".$price." WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
$i = 0;
while($i < $quantity){
$i++;
mysql_query("INSERT INTO furniture (ownerid,roomid,tid) VALUES ('".$my_id."','0','".$boughtid."')") or die(mysql_error());
}
mysql_query("INSERT INTO cms_transactions (userid,amount,date,descr) VALUES ('".$my_id."','".$price."','".$date_full."','Bought ".$quantity." ".$furnirow['catalogue_name']."')") or die(mysql_error());
// katsjing sound
@SendMUSData('UPRC' . $my_id);
// reload hand
@SendMUSData('UPRH' . $my_id);
// Now we say in a message he has the furniture!
$text = "Has comprado ".$quantity." ".$furnirow['catalogue_name'].".";
$color = "green";
}else{
$text = "No tienes suficientes cr&eacute;ditos para comprar esto.";
$color = "red";
}
}else{
$text = "No tienes suficientes cr&eacute;ditos para comprar esto.";
$color = "red";
} 
} 

?><script src="web-gallery/static/js/shop_furni.js" type="text/javascript"></script><?php
$pagessql = mysql_query("SELECT * FROM catalogue_pages WHERE minrank <= '".$rank."' ORDER BY indexid ASC");
?>

<div id="container">
	<div id="content" style="position: relative; left: 0px; top: 0px;" class="clearfix">

<div class="column" style="width: 212px">
	<div class="habblet-container ">
		<div class="cbb clearfix blue ">
			<h2 class="title">Buscar</h2>
			<div id="credits-safety" class="box-content credits-info">
				<div style="text-align: center">
					<form action="./shop_furni.php" method="post">
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
			if($category == $row['indexid']){
				echo "<strong>".$row['displayname']."</strong><br />\n";
			}else{
				echo "<a href=\"./shop_furni.php?category=".$row['indexid']."\">".$row['displayname']."</a><br />\n";
			}
		}
		?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="column" style="width: 527px">
<?php if(isset($text)){ ?>
<div class="rounded rounded-<?php echo $color; ?>">
	<?php echo $text; ?>
</div>
<?php } ?>
				<div class="habblet-container ">
						<div class="cbb clearfix notitle ">

<?php if($search == ""){ ?>
<?php if($category == 1){ ?>
<div id="credits-safety" class="box-content credits-info">
	<div class="credit-info-text clearfix">
		<center>
			<br /><img src="./web-gallery/images/catalogue/headers/Frontpage.gif" />
			<br />Selecciona una categor&iacute;a a la izquierda para comprar muebles.
		</center>
	</div>
</div>
<?php }else{ ?>
<div id="credits-safety" class="box-content credits-info">
	<div class="credit-info-text clearfix">
		<center>
<?php
$currentcat = mysql_fetch_assoc(mysql_query("SELECT displayname FROM catalogue_pages WHERE indexid = '".$category."' LIMIT 1"));
$catname = $currentcat['displayname'];
?>
			<img src="./web-gallery/images/catalogue/headers/<?php echo $catname; ?>.gif" />
		</center>
<?php
$sql = mysql_query("SELECT * FROM catalogue_items WHERE catalogue_id_page = '".$category."' ORDER BY catalogue_name ASC LIMIT 20 OFFSET ".$page*20);
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
						<td style="width: 25%"><img src="./web-gallery/images/catalogue/<?php echo $row['picture']; ?>" /></td>
							<td style="width: 35%"><b><?php echo $row['catalogue_name']; ?></b><br /><?php echo $row['catalogue_description']; ?></td>
							<td style="width: 15%"><?php echo $row['catalogue_cost']; ?></td>
							<td style="width: 25%">
							<a class="new-button fill" onclick="habboclub.buttonClick(<?php echo $row['tid']; ?>,'Confirmar compra'); return false;" href="#"><b>Comprar</b><i></i></a>
							</td>
					</tr>
				</table>
			</li>
		<?php } 
$sql = mysql_query("SELECT * FROM catalogue_items WHERE catalogue_id_page = '".$category."'");
$numofitems = mysql_num_rows($sql);
$pages = ceil($numofitems / 20);
$i = 1;
?><center><?php
while($i <= $pages){
if($i == $pages){
	if($page + 1 == $i){
		echo "<strong>".$i."</strong>";
	}else{
		echo "<a href=\"./shop_furni.php?category=".$category."&page=".$i."\">".$i."</a>";
	}
}else{
	if($page + 1 == $i){
		echo "<strong>".$i."</strong> | ";
	}else{
		echo "<a href=\"./shop_furni.php?category=".$category."&page=".$i."\">".$i."</a> | ";
	}
}
$i++;
} ?></center></div>
<?php } ?>
<?php }else{ #Search ?>
<div id="credits-safety" class="box-content credits-info">
	<div class="credit-info-text clearfix">
<?php
$sql = mysql_query("SELECT * FROM catalogue_items,catalogue_pages WHERE catalogue_items.catalogue_id_page = catalogue_pages.indexid AND (catalogue_name LIKE '%".$search."%' OR catalogue_description LIKE '%".$search."%' OR name_cct LIKE '%".$search."%') AND minrank <= '".$rank."' LIMIT 20");
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
						<td style="width: 25%"><img src="./web-gallery/images/catalogue/<?php echo $row['picture']; ?>" /></td>
							<td style="width: 35%"><b><?php echo $row['catalogue_name']; ?></b><br /><?php echo $row['catalogue_description']; ?></td>
							<td style="width: 15%"><?php echo $row['catalogue_cost']; ?></td>
							<td style="width: 25%">
							<a class="new-button fill" onclick="habboclub.buttonClick(<?php echo $row['tid']; ?>,'Confirmar compra'); return false;" href="#"><b>Comprar</b><i></i></a>
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