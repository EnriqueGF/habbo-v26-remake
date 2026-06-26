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
require_once('../includes/session.php');
if(function_exists(SendMUSData) !== true){ include('../includes/mus.php'); }

$check = mysql_query("SELECT groupid,active FROM cms_homes_group_linker WHERE userid = '".$my_id."' AND active = '1' LIMIT 1") or die(mysql_error());
$linked = mysql_num_rows($check);

$refer = $_SERVER['HTTP_REFERER'];

if($linked > 0){
	$linkdata = mysql_fetch_assoc($check);
	$groupid = $linkdata['groupid'];
	$pos = strrpos($refer, "group_profile.php");
} else {
	$pos = strrpos($refer, "user_profile.php");
}

if ($pos === false) {
	echo "<strong>Tu sesi&oacute;n de edici&oacute;n ha expirado</strong>";
	exit;
}

/** Quick function to format the type stuff
*
* eg. formatThing(1,"geniefirehead",true); would return
* s_geniefirehead_pre
*
* formatThing(4,"bg_rain",false); would return
* b_bg_rain
*
*/

function formatThing($type,$data,$pre)
{
	$str = "";

	switch($type){
		case 1: $str = $str . "s_"; break;
		case 2: $str = $str . "w_"; break;
		case 3: $str = $str . "commodity_"; break; // =S
		case 4: $str = $str . "b_"; break;
	}

	$str = $str . $data;

	if($pre == true){ $str = $str . "_pre"; }

	return $str;
}

/** Quick function to insert or update the user's inventory
*
* UpdateOrInsert(type,amount,data,userid);
* always returns true or cuts the script off with a mysql error
*
*/

function UpdateOrInsert($type,$amount,$data,$my_id)
{
	$data = FilterText($data);
	$type = FilterText($type);
	$amount = FilterText($amount);

	$check = mysql_query("SELECT id FROM cms_homes_inventory WHERE data = '".$data."' AND userid = '".$my_id."' AND type = '".$type."' LIMIT 1") or die(mysql_error());
	$exists = mysql_num_rows($check);

	if($exists > 0){
		mysql_query("UPDATE cms_homes_inventory SET amount = amount + ".$amount." WHERE userid = '".$my_id."' AND type = '".$type."' AND data = '".$data."' LIMIT 1") or die(mysql_error());
	} else {
		mysql_query("INSERT INTO cms_homes_inventory (userid,type,subtype,data,amount) VALUES ('".$my_id."','".$type."','0','".$data."','".$amount."')") or die(mysql_error());
	}

	return true;
}

/** Quick function to delete or update something from the user's inventory
*
* always returns true or cuts the script off with a mysql error
*
*/

function UpdateOrDelete($id,$my_id)
{
	$id = FilterText($id);
	$type = FilterText($type);

	$check = mysql_query("SELECT amount FROM cms_homes_inventory WHERE id = '".$id."' AND userid = '".$my_id."' LIMIT 1") or die(mysql_error());
	$exists = mysql_num_rows($check);

	if($exists > 0){
	$row = mysql_fetch_assoc($check);

		if($row['amount'] > 1){
			mysql_query("UPDATE cms_homes_inventory SET amount = amount - 1 WHERE id = '".$id."' AND userid = '".$my_id."' LIMIT 1") or die(mysql_error());
		} else {
			mysql_query("DELETE FROM cms_homes_inventory WHERE id = '".$id."' AND userid = '".$my_id."' LIMIT 1") or die(mysql_error());
		}

	}

	return true;
}

$mode = $_GET['key'];

if($mode == "inventory"){

// Look for the first inventory sticker in the DB for the header
$tmp = mysql_query("SELECT data FROM cms_homes_inventory WHERE type = '1' AND userid = '".$my_id."' LIMIT 1");
$valid = mysql_num_rows($tmp);

if($valid > 0){
	$row = mysql_fetch_assoc($tmp);
	header("X-JSON: [[\"Inventory\",\"Webstore\"],[\"" . formatThing(1,$row['data'],true) . "\",\"" . formatThing(1,$row['data'],false) . "\",\"".$row['data']."\",\"Sticker\",null,1]]");
} else {
	header("X-JSON: [[\"Inventory\",\"Webstore\"],[]]");
}

?>
<div style="position: relative;">
<div id="webstore-categories-container">
	<h4>Categor&iacute;as:</h4>
	<div id="webstore-categories">
<ul class="purchase-main-category">
		<li id="maincategory-1-stickers" class="selected-main-category webstore-selected-main">
			<div>Pegatinas</div>
			<ul class="purchase-subcategory-list" id="main-category-items-1">
				<?php if($user_rank > 5){ ?>
				<li id="subcategory-1-50-stickers" class="subcategory">
					<div><strong><font color='red'><?php echo $shortname; ?> Staff</strong></font></div>
				</li>
				<?php } ?>
				<li id="subcategory-1-214-stickers" class="subcategory">
					<div>Advertisment</div>
				</li>
				<li id="subcategory-1-205-stickers" class="subcategory">
					<div>Alhambra</div>
				</li>
				<li id="subcategory-1-211-stickers" class="subcategory">
					<div>Alpha Bling</div>
				</li>
				<li id="subcategory-1-203-stickers" class="subcategory">
					<div>Alpha Plastic</div>
				</li>
				<li id="subcategory-1-227-stickers" class="subcategory">
					<div>Alpha Wood</div>
				</li>
				<li id="subcategory-1-242-stickers" class="subcategory">
					<div>Alpha Blue Diner</div>
				</li>
				<li id="subcategory-1-244-stickers" class="subcategory">
					<div>Alpha Green Diner</div>
				</li>
				<li id="subcategory-1-246-stickers" class="subcategory">
					<div>Alpha Red Diner</div>
				</li>
				<li id="subcategory-1-236-stickers" class="subcategory">
					<div>Bank</div>
				</li>
				<li id="subcategory-1-206-stickers" class="subcategory">
					<div>Birthday</div>
				</li>
				<li id="subcategory-1-215-stickers" class="subcategory">
					<div>Borders</div>
				</li>
				<li id="subcategory-1-204-stickers" class="subcategory">
					<div>Buttons</div>
				</li>
				<li id="subcategory-1-223-stickers" class="subcategory">
					<div>Celebration</div>
				</li>
				<li id="subcategory-1-217-stickers" class="subcategory">
					<div>Chinese</div>
				</li>
				<li id="subcategory-1-201-stickers" class="subcategory">
					<div>Clubber</div>
				</li>
				<li id="subcategory-1-245-stickers" class="subcategory">
					<div>Dark Knight</div>
				</li>
				<li id="subcategory-1-243-stickers" class="subcategory">
					<div>Diner</div>
				</li>
				<li id="subcategory-1-235-stickers" class="subcategory">
					<div>Eco</div>
				</li>
				<li id="subcategory-1-240-stickers" class="subcategory">
					<div>FX</div>
				</li>
				<li id="subcategory-1-208-stickers" class="subcategory">
					<div>Costume</div>
				</li>
				<li id="subcategory-1-219-stickers" class="subcategory">
					<div>Goth</div>
				</li>
				<li id="subcategory-1-238-stickers" class="subcategory">
					<div>Highlighter</div>
				</li>
				<li id="subcategory-1-213-stickers" class="subcategory">
					<div>Hocky</div>
				</li>
				<li id="subcategory-1-239-stickers" class="subcategory">
					<div>Inked</div>
				</li>
				<li id="subcategory-1-224-stickers" class="subcategory">
					<div>Japanese</div>
				</li>
				<li id="subcategory-1-225-stickers" class="subcategory">
					<div>Keep it Real (NOT!)</div>
				</li>
				<li id="subcategory-1-226-stickers" class="subcategory">
					<div>Love</div>
				</li>
				<li id="subcategory-1-216-stickers" class="subcategory">
					<div><?echo $shortname; ?>s</div>
				</li>
				<li id="subcategory-1-220-stickers" class="subcategory">
					<div><?echo $shortname; ?>ween</div>
				</li>
				<li id="subcategory-1-221-stickers" class="subcategory">
					<div><?echo $shortname; ?>wood</div>
				</li>
				<li id="subcategory-1-247-stickers" class="subcategory">
					<div>Olympics</div>
				</li>
				<li id="subcategory-1-228-stickers" class="subcategory">
					<div>Others 1</div>
				</li>
				<li id="subcategory-1-229-stickers" class="subcategory">
					<div>Others 2</div>
				</li>
				<li id="subcategory-1-230-stickers" class="subcategory">
					<div>Others 3</div>
				</li>
				<li id="subcategory-1-212-stickers" class="subcategory">
					<div>Paper Mario</div>
				</li>
				<li id="subcategory-1-222-stickers" class="subcategory">
					<div>Pointers</div>
				</li>
				<li id="subcategory-1-232-stickers" class="subcategory">
					<div>Soccer</div>
				</li>
				<li id="subcategory-1-237-stickers" class="subcategory">
					<div>Sparkle</div>
				</li>
				<li id="subcategory-1-210-stickers" class="subcategory">
					<div>Spring</div>
				</li>
				<li id="subcategory-1-241-stickers" class="subcategory">
					<div>St. Patricks</div>
				</li>
				<li id="subcategory-1-207-stickers" class="subcategory">
					<div>Summer</div>
				</li>
				<li id="subcategory-1-209-stickers" class="subcategory">
					<div>Wrestlers</div>
				</li>
				<?php if($user_rank > 5){ ?>
				<li id="subcategory-1-1000-stickers" class="subcategory">
					<div>Not Working</div>
				</li>
				<?php } ?>
			</ul>
		</li>
		<li id="maincategory-2-backgrounds" class="main-category">
			<div>Fondos</div>
			<ul class="purchase-subcategory-list" id="main-category-items-2">
				<li id="subcategory-2-127-stickers" class="subcategory">
					<div>Background 1</div>
				</li>
				<li id="subcategory-2-128-stickers" class="subcategory">
					<div>Background 2</div>
				</li>
				<li id="subcategory-2-129-stickers" class="subcategory">
					<div>Background 3</div>
				</li>
				<li id="subcategory-2-130-stickers" class="subcategory">
					<div>Background 4</div>
				</li>
				<li id="subcategory-2-131-stickers" class="subcategory">
					<div>Background 5</div>
				</li>
				<li id="subcategory-2-132-stickers" class="subcategory">
					<div>Background 6</div>
				</li>
				<li id="subcategory-2-248-stickers" class="subcategory">
					<div>Background 7</div>
				</li>
			</ul>
		</li>
		<li id="maincategory-6-stickie_notes" class="main-category-no-subcategories">
			<div>Notas</div>
			<ul class="purchase-subcategory-list" id="main-category-items-6">
				<li id="subcategory-6-29-stickie_notes" class="subcategory">
					<div>store.subcategory.all</div>
				</li>
			</ul>
		</li>
</ul>
	</div>
</div>

<div id="webstore-content-container">
	<div id="webstore-items-container">
		<h4>Selecciona un art&iacute;culo haciendo clic</h4>
		<div id="webstore-items"><ul id="webstore-item-list">
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
</ul></div>
	</div>
	<div id="webstore-preview-container">
		<div id="webstore-preview-default"></div>
		<div id="webstore-preview"></div>
	</div>
</div>

<div id="inventory-categories-container">
	<h4>Categor&iacute;as:</h4>
	<div id="inventory-categories">
<ul class="purchase-main-category">
	<li id="inv-cat-stickers" class="selected-main-category-no-subcategories">
		<div>Pegatinas</div>
	</li>
	<li id="inv-cat-backgrounds" class="main-category-no-subcategories">
		<div>Fondos</div>
	</li>
	<li id="inv-cat-widgets" class="main-category-no-subcategories">
		<div>Widgets</div>
	</li>
	<li id="inv-cat-notes" class="main-category-no-subcategories">
		<div>Notas</div>
	</li>
</ul>

	</div>
</div>

<div id="inventory-content-container">
	<div id="inventory-items-container">
		<h4>Haz clic en un art&iacute;culo para seleccionarlo:</h4>
		<div id="inventory-items"><ul id="inventory-item-list">
<?php
	$get_em = mysql_query("SELECT * FROM cms_homes_inventory WHERE userid = '".$my_id."' AND type = '1'") or die(mysql_error());
	$typ = "sticker";
	$number = mysql_num_rows($get_em);

	if($number < 1){
	echo "<div class=\"webstore-frank\">
	<div class=\"blackbubble\">
		<div class=\"blackbubble-body\">

<p><b>&iexcl;Tu inventario para esta categor&iacute;a est&aacute; completamente vac&iacute;o!</b></p>
<p>Para comprar pegatinas, fondos y notas, haz clic en la pesta&ntilde;a Tienda y explora las categor&iacute;as. Cuando encuentres algo que te guste, haz clic en &lsquo;Comprar&rsquo;.</p>

		<div class=\"clear\"></div>
		</div>
	</div>
	<div class=\"blackbubble-bottom\">
		<div class=\"blackbubble-bottom-body\">
			<img src=\"./web-gallery/images/box-scale/bubble_tail_small.gif\" alt=\"\" width=\"12\" height=\"21\" class=\"invitation-tail\" />
		</div>
	</div>
	<div class=\"webstore-frank-image\"><img src=\"./web-gallery/images/frank/sorry.gif\" alt=\"\" width=\"57\" height=\"88\" /></div>
</div>";
	}

	while ($row = mysql_fetch_assoc($get_em)) {

	if($row['amount'] > 1){
		$specialcount = "<div class=\"webstore-item-count\"><div>x".$row['amount']."</div></div>";
	} else {
		$specialcount = "";
	}

	printf("	<li id=\"inventory-item-%s\" title=\"%s\">
		<div class=\"webstore-item-preview %s\">

			<div class=\"webstore-item-mask\">
				%s
			</div>
		</div>
	</li>", $row['id'], $row['data'], FormatThing($row['type'],$row['data'],true), $specialcount);
	}

	// We want at least 20 empty slots. If the user has less than 20 items for this type
	// we echo the necessary empty slots to fill it up to 20 slots using a for() loop.
	if($number < 20){
	$empty_slots = 20 - $number;
		for ($i = 1; $i <= $empty_slots; $i++) {
		echo "<li class=\"webstore-item-empty\"></li>";
		}
	}

?>
</ul></div>
	</div>
	<div id="inventory-preview-container">
		<div id="inventory-preview-default"></div>
		<div id="inventory-preview"><h4>&nbsp;</h4>

<div id="inventory-preview-box"></div>

<div id="inventory-preview-place" class="clearfix">
	<div class="clearfix">
		<a href="#" class="new-button" id="inventory-place"><b>Colocar</b><i></i></a>
	</div>
</div>

</div>
	</div>
</div>

<div id="webstore-close-container">
	<div class="clearfix"><a href="#" id="webstore-close" class="new-button"><b>Close</b><i></i></a></div>
</div>
<?php } else if($mode == "inventory_items"){
$type = $_POST['type'];

	if($type == "stickers"){
	$get_em = mysql_query("SELECT * FROM cms_homes_inventory WHERE userid = '".$my_id."' AND type = '1'") or die(mysql_error());
	$typ = "sticker";
	} else if($type == "notes"){
	$get_em = mysql_query("SELECT * FROM cms_homes_inventory WHERE userid = '".$my_id."' AND type = '3'") or die(mysql_error());
	$typ = "note";
	} else if($type == "widgets"){
	$typ = "widget";
	} else if($type == "backgrounds"){
	$get_em = mysql_query("SELECT * FROM cms_homes_inventory WHERE userid = '".$my_id."' AND type = '4'") or die(mysql_error());
	$typ = "background";
	} else {
	$get_em = mysql_query("SELECT * FROM cms_homes_inventory WHERE userid = '".$my_id."' AND type = '1'") or die(mysql_error());
	$typ = "sticker";
	}

	if($typ !== "widget"){
		$number = mysql_num_rows($get_em);
		echo "		<ul id=\"webstore-item-list\">";

		if($number < 1){
			echo "<div class=\"webstore-frank\">
	<div class=\"blackbubble\">
		<div class=\"blackbubble-body\">

<p><b>&iexcl;Tu inventario para esta categor&iacute;a est&aacute; completamente vac&iacute;o!</b></p>
<p>Para comprar pegatinas, fondos y notas, haz clic en la pesta&ntilde;a Tienda y explora las categor&iacute;as. Cuando encuentres algo que te guste, haz clic en &lsquo;Comprar&rsquo;.</p>

		<div class=\"clear\"></div>
		</div>
	</div>
	<div class=\"blackbubble-bottom\">
		<div class=\"blackbubble-bottom-body\">
			<img src=\"./web-gallery/images/box-scale/bubble_tail_small.gif\" alt=\"\" width=\"12\" height=\"21\" class=\"invitation-tail\" />
		</div>
	</div>
	<div class=\"webstore-frank-image\"><img src=\"./web-gallery/images/frank/sorry.gif\" alt=\"\" width=\"57\" height=\"88\" /></div>
</div>";
	}

		while ($row = mysql_fetch_assoc($get_em)) {

		if($row['amount'] > 1){
			$specialcount = "<div class=\"webstore-item-count\"><div>x".$row['amount']."</div></div>";
		} else {
			$specialcount = "";
		}

		printf("	<li id=\"inventory-item-%s\" title=\"%s\">
		<div class=\"webstore-item-preview %s\">

			<div class=\"webstore-item-mask\">
				%s
			</div>
		</div>
	</li>", $row['id'], $row['data'], FormatThing($row['type'],$row['data'],true), $specialcount);
	}

	// We want at least 20 empty slots. If the user has less than 20 items for this type
	// we echo the necessary empty slots to fill it up to 20 slots using a for loop.
		if($number < 20){
		$empty_slots = 20 - $number;
			for ($i = 1; $i <= $empty_slots; $i++) {
			echo "<li class=\"webstore-item-empty\"></li>";
			}
		}

		echo "</ul>";
	} elseif($typ == "widget"){
		if($linked > 0){ // Group Mode
			$check = mysql_query("SELECT id FROM cms_homes_stickers WHERE groupid = '".$groupid."' AND type = '2' AND subtype = '3' LIMIT 1") or die(mysql_error());
			$placed_memberwidget = mysql_num_rows($check);
			$check = mysql_query("SELECT id FROM cms_homes_stickers WHERE groupid = '".$groupid."' AND type = '2' AND subtype = '4' LIMIT 1") or die(mysql_error());
			$placed_guestbookwidget = mysql_num_rows($check);
			$check = mysql_query("SELECT id FROM cms_homes_stickers WHERE groupid = '".$groupid."' AND type = '2' AND subtype = '5' LIMIT 1") or die(mysql_error());
			$placed_traxwidget = mysql_num_rows($check);

			echo "<ul id=\"inventory-item-list\">";
			echo "<li id=\"inventory-item-p-3\"
		title=\"Mis grupos\" class=\"webstore-widget-item"; if($placed_memberwidget > 0){ echo " webstore-widget-disabled"; } echo "\">
		<div class=\"webstore-item-preview w_memberwidget_pre\" >
			<div class=\"webstore-item-mask\">

			</div>
		</div>
		<div class=\"webstore-widget-description\">
			<h3>Miembros del grupo</h3>
			<p>Muestra los miembros de tu grupo</p>
		</div>
	</li>";
	echo "<li id=\"inventory-item-p-4\"
		title=\"Libro de visitas\" class=\"webstore-widget-item"; if($placed_guestbookwidget > 0){ echo " webstore-widget-disabled"; } echo "\">
		<div class=\"webstore-item-preview w_guestbookwidget_pre\" >
			<div class=\"webstore-item-mask\">

			</div>
		</div>
		<div class=\"webstore-widget-description\">
			<h3>Libro de visitas</h3>
			<p>Libro de visitas</p>
		</div>
	</li>
	<li id=\"inventory-item-p-5\"
		title=\"Traxplayer\" class=\"webstore-widget-item" ; if($placed_traxwidget > 0){ echo " webstore-widget-disabled"; } echo "\">
		<div class=\"webstore-item-preview w_traxplayerwidget_pre\" >
			<div class=\"webstore-item-mask\">

			</div>
		</div>
		<div class=\"webstore-widget-description\">
			<h3>Traxplayer</h3>
			<p>Reproduce tus temas de ".$shortname." en tu p&aacute;gina</p>
		</div>
	</li>";
			echo "</ul>";
		} else { // User profile

			$check = mysql_query("SELECT id FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '2' AND subtype = '2' LIMIT 1") or die(mysql_error());
			$placed_groupwidget = mysql_num_rows($check);
			$check = mysql_query("SELECT id FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '2' AND subtype = '3' LIMIT 1") or die(mysql_error());
			$placed_roomwidget = mysql_num_rows($check);
			$check = mysql_query("SELECT id FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '2' AND subtype = '4' LIMIT 1") or die(mysql_error());
			$placed_guestbookwidget = mysql_num_rows($check);
			$check = mysql_query("SELECT id FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '2' AND subtype = '5' LIMIT 1") or die(mysql_error());
			$placed_friendswidget = mysql_num_rows($check);
			$check = mysql_query("SELECT id FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '2' AND subtype = '6' LIMIT 1") or die(mysql_error());
			$placed_traxwidget = mysql_num_rows($check);
			$check = mysql_query("SELECT id FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '2' AND subtype = '7' LIMIT 1") or die(mysql_error());
			$placed_scoreswidget = mysql_num_rows($check);
			$check = mysql_query("SELECT id FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '2' AND subtype = '8' LIMIT 1") or die(mysql_error());
			$placed_badgeswidget = mysql_num_rows($check);

			echo "<ul id=\"inventory-item-list\">";
	echo "<li id=\"inventory-item-p-7\"
		title=\"Widget de puntuaciones altas\" class=\"webstore-widget-item"; if($placed_scoreswidget > 0){ echo " webstore-widget-disabled"; } echo "\">
		<div class=\"webstore-item-preview w_highscoreswidget_pre\" >
			<div class=\"webstore-item-mask\">

			</div>
		</div>
		<div class=\"webstore-widget-description\">
			<h3>Widget de puntuaciones altas</h3>
			<p>Muestra tus puntuaciones altas</p>
		</div>
	</li>
	<li id=\"inventory-item-p-2\"
		title=\"Mis grupos\" class=\"webstore-widget-item"; if($placed_groupwidget > 0){ echo " webstore-widget-disabled"; } echo "\">
		<div class=\"webstore-item-preview w_groupswidget_pre\" >
			<div class=\"webstore-item-mask\">

			</div>
		</div>
		<div class=\"webstore-widget-description\">
			<h3>Mis grupos</h3>
			<p>Muestra tus grupos</p>
		</div>
	</li>
	<li id=\"inventory-item-p-3\"
		title=\"Widget de habitaciones\" class=\"webstore-widget-item"; if($placed_roomwidget > 0){ echo " webstore-widget-disabled"; } echo "\">
		<div class=\"webstore-item-preview w_roomswidget_pre\" >
			<div class=\"webstore-item-mask\">

			</div>
		</div>
		<div class=\"webstore-widget-description\">
			<h3>Widget de habitaciones</h3>
			<p>Muestra tus habitaciones en tu p&aacute;gina</p>
		</div>
	</li>
	<li id=\"inventory-item-p-4\"
		title=\"Libro de visitas\" class=\"webstore-widget-item"; if($placed_guestbookwidget > 0){ echo " webstore-widget-disabled"; } echo "\">
		<div class=\"webstore-item-preview w_guestbookwidget_pre\" >
			<div class=\"webstore-item-mask\">

			</div>
		</div>
		<div class=\"webstore-widget-description\">
			<h3>Libro de visitas</h3>
			<p>Libro de visitas</p>
		</div>
	</li>
	<li id=\"inventory-item-p-5\"
		title=\"Mis amigos\" class=\"webstore-widget-item"; if($placed_friendswidget > 0){ echo " webstore-widget-disabled"; } echo "\">
		<div class=\"webstore-item-preview w_friendswidget_pre\" >
			<div class=\"webstore-item-mask\">

			</div>
		</div>
		<div class=\"webstore-widget-description\">
			<h3>Mis amigos</h3>
			<p>Muestra todos tus amigos</p>
		</div>
	</li>
	<li id=\"inventory-item-p-6\"
		title=\"Traxplayer\" class=\"webstore-widget-item" ; if($placed_traxwidget > 0){ echo " webstore-widget-disabled"; } echo "\">
		<div class=\"webstore-item-preview w_traxplayerwidget_pre\" >
			<div class=\"webstore-item-mask\">

			</div>
		</div>
		<div class=\"webstore-widget-description\">
			<h3>Traxplayer</h3>
			<p>Reproduce tus temas de ".$shortname." en tu p&aacute;gina</p>
		</div>
	</li>
	<li id=\"inventory-item-p-8\"
		title=\"Mis insignias\" class=\"webstore-widget-item" ; if($placed_badgeswidget > 0){ echo " webstore-widget-disabled"; } echo "\">
		<div class=\"webstore-item-preview w_badgeswidget_pre\" >
			<div class=\"webstore-item-mask\">

			</div>
		</div>
		<div class=\"webstore-widget-description\">
			<h3>Mis insignias</h3>
			<p>Muestra tus insignias en tu p&aacute;gina.</p>
		</div>
	</li>";
echo "</ul>";
		}
	}
} elseif($mode == "main"){

// Look for the first thing in this category
$tmp = mysql_query("SELECT * FROM cms_homes_catalouge WHERE category = '19' ORDER BY id ASC LIMIT 1");
$valid = mysql_num_rows($tmp);

if($valid > 0){
	$row = mysql_fetch_assoc($tmp);
	header("X-JSON: [[\"Inventory\",\"Webstore\"],[{\"itemCount\":1,\"titleKey\":\"".$row['name']."\",\"previewCssClass\":\"".formatThing($row['type'],$row['data'],true)."\"}]]");
} else {
	header("X-JSON: [[\"Inventory\",\"Webstore\"],[]]");
}

?>
<div style="position: relative;">
<div id="webstore-categories-container">
	<h4>Categor&iacute;as:</h4>
	<div id="webstore-categories">
<ul class="purchase-main-category">
		<li id="maincategory-1-stickers" class="selected-main-category webstore-selected-main">
			<div>Pegatinas</div>
			<ul class="purchase-subcategory-list" id="main-category-items-1">
				<?php if($user_rank > 5){ ?>
				<li id="subcategory-1-50-stickers" class="subcategory">
					<div><strong><font color='red'><?php echo $shortname; ?> Staff</strong></font></div>
				</li>
				<?php } ?>
				<li id="subcategory-1-214-stickers" class="subcategory">
					<div>Advertisment</div>
				</li>
				<li id="subcategory-1-205-stickers" class="subcategory">
					<div>Alhambra</div>
				</li>
				<li id="subcategory-1-211-stickers" class="subcategory">
					<div>Alpha Bling</div>
				</li>
				<li id="subcategory-1-203-stickers" class="subcategory">
					<div>Alpha Plastic</div>
				</li>
				<li id="subcategory-1-227-stickers" class="subcategory">
					<div>Alpha Wood</div>
				</li>
				<li id="subcategory-1-242-stickers" class="subcategory">
					<div>Alpha Blue Diner</div>
				</li>
				<li id="subcategory-1-244-stickers" class="subcategory">
					<div>Alpha Green Diner</div>
				</li>
				<li id="subcategory-1-246-stickers" class="subcategory">
					<div>Alpha Red Diner</div>
				</li>
				<li id="subcategory-1-236-stickers" class="subcategory">
					<div>Bank</div>
				</li>
				<li id="subcategory-1-206-stickers" class="subcategory">
					<div>Birthday</div>
				</li>
				<li id="subcategory-1-215-stickers" class="subcategory">
					<div>Borders</div>
				</li>
				<li id="subcategory-1-204-stickers" class="subcategory">
					<div>Buttons</div>
				</li>
				<li id="subcategory-1-223-stickers" class="subcategory">
					<div>Celebration</div>
				</li>
				<li id="subcategory-1-217-stickers" class="subcategory">
					<div>Chinese</div>
				</li>
				<li id="subcategory-1-201-stickers" class="subcategory">
					<div>Clubber</div>
				</li>
				<li id="subcategory-1-245-stickers" class="subcategory">
					<div>Dark Knight</div>
				</li>
				<li id="subcategory-1-243-stickers" class="subcategory">
					<div>Diner</div>
				</li>
				<li id="subcategory-1-235-stickers" class="subcategory">
					<div>Eco</div>
				</li>
				<li id="subcategory-1-240-stickers" class="subcategory">
					<div>FX</div>
				</li>
				<li id="subcategory-1-208-stickers" class="subcategory">
					<div>Costume</div>
				</li>
				<li id="subcategory-1-219-stickers" class="subcategory">
					<div>Goth</div>
				</li>
				<li id="subcategory-1-238-stickers" class="subcategory">
					<div>Highlighter</div>
				</li>
				<li id="subcategory-1-213-stickers" class="subcategory">
					<div>Hocky</div>
				</li>
				<li id="subcategory-1-239-stickers" class="subcategory">
					<div>Inked</div>
				</li>
				<li id="subcategory-1-224-stickers" class="subcategory">
					<div>Japanese</div>
				</li>
				<li id="subcategory-1-225-stickers" class="subcategory">
					<div>Keep it Real (NOT!)</div>
				</li>
				<li id="subcategory-1-226-stickers" class="subcategory">
					<div>Love</div>
				</li>
				<li id="subcategory-1-216-stickers" class="subcategory">
					<div><?echo $shortname; ?>s</div>
				</li>
				<li id="subcategory-1-220-stickers" class="subcategory">
					<div><?echo $shortname; ?>ween</div>
				</li>
				<li id="subcategory-1-221-stickers" class="subcategory">
					<div><?echo $shortname; ?>wood</div>
				</li>
				<li id="subcategory-1-247-stickers" class="subcategory">
					<div>Olympics</div>
				</li>
				<li id="subcategory-1-228-stickers" class="subcategory">
					<div>Others 1</div>
				</li>
				<li id="subcategory-1-229-stickers" class="subcategory">
					<div>Others 2</div>
				</li>
				<li id="subcategory-1-230-stickers" class="subcategory">
					<div>Others 3</div>
				</li>
				<li id="subcategory-1-212-stickers" class="subcategory">
					<div>Paper Mario</div>
				</li>
				<li id="subcategory-1-222-stickers" class="subcategory">
					<div>Pointers</div>
				</li>
				<li id="subcategory-1-232-stickers" class="subcategory">
					<div>Soccer</div>
				</li>
				<li id="subcategory-1-237-stickers" class="subcategory">
					<div>Sparkle</div>
				</li>
				<li id="subcategory-1-210-stickers" class="subcategory">
					<div>Spring</div>
				</li>
				<li id="subcategory-1-241-stickers" class="subcategory">
					<div>St. Patricks</div>
				</li>
				<li id="subcategory-1-207-stickers" class="subcategory">
					<div>Summer</div>
				</li>
				<li id="subcategory-1-209-stickers" class="subcategory">
					<div>Wrestlers</div>
				</li>
				<?php if($user_rank > 5){ ?>
				<li id="subcategory-1-1000-stickers" class="subcategory">
					<div>Not Working</div>
				</li>
				<?php } ?>
			</ul>
		</li>
		<li id="maincategory-2-backgrounds" class="main-category">
			<div>Fondos</div>
			<ul class="purchase-subcategory-list" id="main-category-items-2">
				<li id="subcategory-2-127-stickers" class="subcategory">
					<div>Background 1</div>
				</li>
				<li id="subcategory-2-128-stickers" class="subcategory">
					<div>Background 2</div>
				</li>
				<li id="subcategory-2-129-stickers" class="subcategory">
					<div>Background 3</div>
				</li>
				<li id="subcategory-2-130-stickers" class="subcategory">
					<div>Background 4</div>
				</li>
				<li id="subcategory-2-131-stickers" class="subcategory">
					<div>Background 5</div>
				</li>
				<li id="subcategory-2-132-stickers" class="subcategory">
					<div>Background 6</div>
				</li>
				<li id="subcategory-2-248-stickers" class="subcategory">
					<div>Background 7</div>
				</li>
			</ul>
		</li>
		<li id="maincategory-6-stickie_notes" class="main-category-no-subcategories">
			<div>Notas</div>
			<ul class="purchase-subcategory-list" id="main-category-items-6">
				<li id="subcategory-6-29-stickie_notes" class="subcategory">
					<div>store.subcategory.all</div>
				</li>
			</ul>
		</li>
</ul>

	</div>
</div>

<div id="webstore-content-container">
	<div id="webstore-items-container">
		<h4>Selecciona un art&iacute;culo haciendo clic</h4>
		<div id="webstore-items">

<?php
	$category = "19";

	$get_em = mysql_query("SELECT * FROM cms_homes_catalouge WHERE category = ".$category."") or die(mysql_error());
	$number = mysql_num_rows($get_em);

	echo "		<ul id=\"webstore-item-list\">";

	if($number < 1){
	echo "<div class=\"webstore-frank\">
	<div class=\"blackbubble\">
		<div class=\"blackbubble-body\">

<p><b>&iexcl;Esta categor&iacute;a est&aacute; vac&iacute;a!</b></p>
<p>&iexcl;Vuelve pronto, a&ntilde;adimos nuevos art&iacute;culos continuamente!</p>

		<div class=\"clear\"></div>
		</div>
	</div>
	<div class=\"blackbubble-bottom\">
		<div class=\"blackbubble-bottom-body\">
			<img src=\"./web-gallery/images/box-scale/bubble_tail_small.gif\" alt=\"\" width=\"12\" height=\"21\" class=\"invitation-tail\" />
		</div>
	</div>
	<div class=\"webstore-frank-image\"><img src=\"./web-gallery/images/frank/sorry.gif\" alt=\"\" width=\"57\" height=\"88\" /></div>
</div>";
	}

	while ($row = mysql_fetch_assoc($get_em)) {

	if($row['amount'] > 1){
		$specialcount = "<div class=\"webstore-item-count\"><div>x".$row['amount']."</div></div>";
	} else {
		$specialcount = "";
	}

	printf("	<li id=\"inventory-item-%s\" title=\"%s\">
		<div class=\"webstore-item-preview %s\">

			<div class=\"webstore-item-mask\">
				%s
			</div>
		</div>
	</li>", $row['id'], $row['data'], FormatThing($row['type'],$row['data'],true), $specialcount);
	}

	// We want at least 20 empty slots. If the user has less than 20 items for this type
	// we echo the necessary empty slots to fill it up to 20 slots using a for() loop.
	if($number < 20){
	$empty_slots = 20 - $number;
		for ($i = 1; $i <= $empty_slots; $i++) {
		echo "<li class=\"webstore-item-empty\"></li>";
		}
	}

	echo "</ul>";
?>

		</div>
	</div>
	<div id="webstore-preview-container">
		<div id="webstore-preview-default"></div>
		<div id="webstore-preview"><?php
$tmp = mysql_query("SELECT * FROM cms_homes_catalouge WHERE id = '1' AND category = '19' LIMIT 1");
$exists = mysql_num_rows($tmp);

	$row = mysql_fetch_assoc($tmp);
?>
<h4 title="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></h4>

<div id="webstore-preview-box"></div>

<div id="webstore-preview-price">
Precio:<br /><b>
	<?php echo $row['price']; ?> cr&eacute;ditos
</b>
</div>

<div id="webstore-preview-purse">
Tienes:<br /><b><?php echo $myrow['credits']; ?> cr&eacute;ditos</b><br />
<?php if($myrow['credits'] < $row['cost']){ ?><span class="webstore-preview-error">No tienes suficientes cr&eacute;ditos para comprar este art&iacute;culo.</span><br />
<a href="credits.php" target=_blank>Conseguir cr&eacute;ditos</a><?php } ?>
</div>

<div id="webstore-preview-purchase" class="clearfix">
	<div class="clearfix">
		<a href="#" class="new-button <?php if($myrow['credits'] < $row['cost']){ ?>disabled-button<?php } ?>" <?php if($myrow['credits'] < $row['cost']){ ?>disabled="disabled"<?php } ?> id="webstore-purchase<?php if($myrow['credits'] < $row['cost']){ ?>-disabled<?php } ?>"><b>Comprar</b><i></i></a>
	</div>
</div>

<span id="webstore-preview-bg-text" style="display: none">Vista previa</span>
</div>
	</div>
</div>

<div id="inventory-categories-container">
	<h4>Categor&iacute;as:</h4>
	<div id="inventory-categories">
<ul class="purchase-main-category">
	<li id="inv-cat-stickers" class="selected-main-category-no-subcategories">
		<div>Pegatinas</div>
	</li>
	<li id="inv-cat-backgrounds" class="main-category-no-subcategories">
		<div>Fondos</div>
	</li>
	<li id="inv-cat-widgets" class="main-category-no-subcategories">
		<div>Widgets</div>
	</li>
	<li id="inv-cat-notes" class="main-category-no-subcategories">
		<div>Notas</div>
	</li>
</ul>

	</div>
</div>

<div id="inventory-content-container">
	<div id="inventory-items-container">
		<h4>Haz clic en un art&iacute;culo para seleccionarlo:</h4>
		<div id="inventory-items"><ul id="inventory-item-list">
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
	<li class="webstore-item-empty"></li>
</ul></div>
	</div>
	<div id="inventory-preview-container">
		<div id="inventory-preview-default"></div>
		<div id="inventory-preview"><h4>&nbsp;</h4>

<div id="inventory-preview-box"></div>

<div id="inventory-preview-place" class="clearfix">
	<div class="clearfix">
		<a href="#" class="new-button" id="inventory-place"><b>Colocar</b><i></i></a>
	</div>
</div>

</div>
	</div>
</div>

<div id="webstore-close-container">
	<div class="clearfix"><a href="#" id="webstore-close" class="new-button"><b>Close</b><i></i></a></div>
</div>
</div>
<?php
} elseif($mode == "preview"){

$productId = FilterText($_POST['productId']);
$subCategoryId = FilterText($_POST['subCategoryId']);

$tmp = mysql_query("SELECT * FROM cms_homes_catalouge WHERE id = '".$productId."' AND category = '".$subCategoryId."' LIMIT 1");
$exists = mysql_num_rows($tmp);
$row = mysql_fetch_assoc($tmp);

if($row['type'] == "4"){
	$bg_pre = "\"bgCssClass\":\"" . FormatThing($row['type'],$row['data'],false) . "\",";
}

header("X-JSON: [{\"itemCount\":1,\"titleKey\":\"".$row['name']."\"," . $bg_pre . "\"previewCssClass\":\"" . FormatThing($row['type'],$row['data'],true) . "\"}]");

?>
<h4 title="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></h4>

<div id="webstore-preview-box"></div>

<?php if($exists > 0){ ?><div id="webstore-preview-price">
Precio:<br /><b>
	<?php echo $row['price']; ?> cr&eacute;ditos
</b>
</div>

<div id="webstore-preview-purse">
Tienes:<br /><b><?php echo $myrow['credits']; ?> cr&eacute;ditos</b><br />
<?php if($myrow['credits'] < $row['cost']){ ?><span class="webstore-preview-error">No tienes suficientes cr&eacute;ditos para comprar este art&iacute;culo.</span><br />
<a href="credits.php" target=_blank>Conseguir cr&eacute;ditos</a><?php } ?>
</div>

<div id="webstore-preview-purchase" class="clearfix">
	<div class="clearfix">
		<a href="#" class="new-button <?php if($myrow['credits'] < $row['cost']){ ?>disabled-button<?php } ?>" <?php if($myrow['credits'] < $row['cost']){ ?>disabled="disabled"<?php } ?> id="webstore-purchase<?php if($myrow['credits'] < $row['cost']){ ?>-disabled<?php } ?>"><b>Comprar</b><i></i></a>
	</div>
</div><?php } ?>

<span id="webstore-preview-bg-text" style="display: none">Vista previa</span>
<?php
} elseif($mode == "purchase_confirm"){
$productId = FilterText($_POST['productId']);
$subCategoryId = FilterText($_POST['subCategoryId']);

$tmp = mysql_query("SELECT * FROM cms_homes_catalouge WHERE id = '".$productId."' AND category = '".$subCategoryId."' LIMIT 1");
$exists = mysql_num_rows($tmp);
$row = mysql_fetch_assoc($tmp);

	if($exists > 0){
?>
		<div class="webstore-item-preview <?php echo formatThing($row['type'],$row['data'],true); ?>">
			<div class="webstore-item-mask">
			</div>
		</div>
		<p>&iquest;Seguro que quieres comprar <b><?php echo $row['name']; ?></b>? Esta compra te costar&aacute; <b><?php echo $row['price']; ?></b> cr&eacute;ditos.</p>

	<p class="new-buttons">
		<a href="#" class="new-button" id="webstore-confirm-cancel"><b>Cancelar</b><i></i></a>
		<a href="#" class="new-button" id="webstore-confirm-submit"><b>Continuar</b><i></i></a>
	</p>
	<div class="clear"></div>
<?php
	} else {
?>
		<p>Lo sentimos, no puedes comprar este art&iacute;culo.</p>

	<p class="new-buttons">
		<a href="#" class="new-button" id="webstore-confirm-cancel"><b>Aceptar</b><i></i></a>
	</p>
	<div class="clear"></div>
<?php
	}
} elseif($mode == "purchase_stickers"){
$productId = FilterText($_POST['selectedId']);

$tmp = mysql_query("SELECT * FROM cms_homes_catalouge WHERE id = '".$productId."' LIMIT 1");
$exists = mysql_num_rows($tmp);
$row = mysql_fetch_assoc($tmp);

if($row['category'] == "50" && $user_rank < 6){ exit; }

	if($exists > 0){
		if($myrow['credits'] < $row['price']){
		?>
		<p>No tienes suficientes cr&eacute;ditos para comprar este art&iacute;culo.</p>

	<p class="new-buttons">
		<a href="#" class="new-button" id="webstore-confirm-cancel"><b>Aceptar</b><i></i></a>
	</p>
	<div class="clear"></div>
		<?php
		} else {
			mysql_query("UPDATE users SET credits = credits - ".$row['price']." WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
			UpdateOrInsert($row['type'],$row['amount'],$row['data'],$my_id);
			mysql_query("INSERT INTO cms_transactions (userid,amount,date,descr) VALUES ('".$my_id."','-".$row['price']."','".$date_full."','Webstore purchase')");
			@SendMUSData('UPRC' . $my_id);
			echo "OK";
		}
	} else {
?>
		<p>Lo sentimos, no puedes comprar este art&iacute;culo.</p>

	<p class="new-buttons">
		<a href="#" class="new-button" id="webstore-confirm-cancel"><b>Aceptar</b><i></i></a>
	</p>
	<div class="clear"></div>
<?php
	}
} elseif($mode == "items"){

	$category = FilterText($_POST['subCategoryId']);

	if($category == "50" && $user_rank < 6){ exit; }

	$get_em = mysql_query("SELECT * FROM cms_homes_catalouge WHERE category = ".$category."") or die(mysql_error());
	$number = mysql_num_rows($get_em);

	echo "		<ul id=\"webstore-item-list\">";

	if($number < 1){
	echo "<div class=\"webstore-frank\">
	<div class=\"blackbubble\">
		<div class=\"blackbubble-body\">

<p><b>&iexcl;Esta categor&iacute;a est&aacute; vac&iacute;a!</b></p>
<p>&iexcl;Vuelve pronto, a&ntilde;adimos nuevos art&iacute;culos continuamente!</p>

		<div class=\"clear\"></div>
		</div>
	</div>
	<div class=\"blackbubble-bottom\">
		<div class=\"blackbubble-bottom-body\">
			<img src=\"./web-gallery/images/box-scale/bubble_tail_small.gif\" alt=\"\" width=\"12\" height=\"21\" class=\"invitation-tail\" />
		</div>
	</div>
	<div class=\"webstore-frank-image\"><img src=\"./web-gallery/images/frank/sorry.gif\" alt=\"\" width=\"57\" height=\"88\" /></div>
</div>";
	}

	while ($row = mysql_fetch_assoc($get_em)) {

	if($row['amount'] > 1){
		$specialcount = "<div class=\"webstore-item-count\"><div>x".$row['amount']."</div></div>";
	} else {
		$specialcount = "";
	}

	printf("	<li id=\"inventory-item-%s\" title=\"%s\">
		<div class=\"webstore-item-preview %s\">

			<div class=\"webstore-item-mask\">
				%s
			</div>
		</div>
	</li>", $row['id'], $row['data'], FormatThing($row['type'],$row['data'],true), $specialcount);
	}

	// We want at least 20 empty slots. If the user has less than 20 items for this type
	// we echo the necessary empty slots to fill it up to 20 slots using a for() loop.
	if($number < 20){
	$empty_slots = 20 - $number;
		for ($i = 1; $i <= $empty_slots; $i++) {
		echo "<li class=\"webstore-item-empty\"></li>";
		}
	}

	echo "</ul>";

} elseif($mode == "purchase_backgrounds"){
$productId = FilterText($_POST['selectedId']);

$tmp = mysql_query("SELECT * FROM cms_homes_catalouge WHERE id = '".$productId."' LIMIT 1");
$exists = mysql_num_rows($tmp);
$row = mysql_fetch_assoc($tmp);

if($row['category'] == "50" && $user_rank < 6){ exit; }

	if($exists > 0){
		if($myrow['credits'] < $row['price']){
		?>
		<p>No tienes suficientes cr&eacute;ditos para comprar este art&iacute;culo.</p>

	<p class="new-buttons">
		<a href="#" class="new-button" id="webstore-confirm-cancel"><b>Aceptar</b><i></i></a>
	</p>
	<div class="clear"></div>
		<?php
		} else {
			$tcheck = mysql_query("SELECT id FROM cms_homes_inventory WHERE userid = '".$my_id."' AND type = '4' AND data = '".$row['data']."' LIMIT 1") or die(mysql_error());
			$tnum = mysql_num_rows($tcheck);
			if($tnum > 0){ ?>
		<p>&iexcl;Ya tienes un fondo de este tipo en tu inventario!</p>

	<p class="new-buttons">
		<a href="#" class="new-button" id="webstore-confirm-cancel"><b>Aceptar</b><i></i></a>
	</p>
			<?php } else {
				mysql_query("UPDATE users SET credits = credits - ".$row['price']." WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
				UpdateOrInsert($row['type'],$row['amount'],$row['data'],$my_id);
				mysql_query("INSERT INTO cms_transactions (userid,amount,date,descr) VALUES ('".$my_id."','-".$row['price']."','".$date_full."','Webstore purchase')");
				@SendMUSData('UPRC' . $my_id);
				echo "OK";
			}
		}
	} else {
?>
		<p>Lo sentimos, no puedes comprar este art&iacute;culo.</p>

	<p class="new-buttons">
		<a href="#" class="new-button" id="webstore-confirm-cancel"><b>Aceptar</b><i></i></a>
	</p>
	<div class="clear"></div>
<?php
	}
} elseif($mode == "purchase_stickie_notes"){
$productId = FilterText($_POST['selectedId']); if(!is_numeric($productId)){ exit; }

$tmp = mysql_query("SELECT * FROM cms_homes_catalouge WHERE id = '".$productId."' LIMIT 1");
$exists = mysql_num_rows($tmp);
$row = mysql_fetch_assoc($tmp);

if($row['category'] == "50" && $user_rank < 6){ exit; }

	if($exists > 0){
		if($myrow['credits'] < $row['price']){
		?>
		<p>No tienes suficientes cr&eacute;ditos para comprar este art&iacute;culo.</p>

	<p class="new-buttons">
		<a href="#" class="new-button" id="webstore-confirm-cancel"><b>Aceptar</b><i></i></a>
	</p>
	<div class="clear"></div>
		<?php
		} else {
			mysql_query("UPDATE users SET credits = credits - ".$row['price']." WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
			UpdateOrInsert($row['type'],$row['amount'],$row['data'],$my_id);
			mysql_query("INSERT INTO cms_transactions (userid,amount,date,descr) VALUES ('".$my_id."','-".$row['price']."','".$date_full."','Webstore purchase')");
			@SendMUSData('UPRC' . $my_id);
			echo "OK";
		}
	} else {
?>
		<p>Lo sentimos, no puedes comprar este art&iacute;culo.</p>

	<p class="new-buttons">
		<a href="#" class="new-button" id="webstore-confirm-cancel"><b>Aceptar</b><i></i></a>
	</p>
	<div class="clear"></div>
<?php
	}
} elseif($mode == "inventory_preview"){

if($_POST['type'] == "widgets"){
	$widget = $_POST['itemId'];
	if($widget == "2"){
		$row['data'] = "groupswidget";
	} elseif($widget == "3"){
		$row['data'] = "memberwidget";
	} else {
		$row['data'] = "profilewidget";
	}
	$row['type'] = 2;
	$exists = 1;
} else {
	$productId = FilterText($_POST['itemId']); if(!is_numeric($productId)){ exit; }
	$tmp = mysql_query("SELECT * FROM cms_homes_inventory WHERE id = '".$productId."' AND userid = '".$my_id."' LIMIT 1");
	$exists = mysql_num_rows($tmp);
	$row = mysql_fetch_assoc($tmp);
}

header("X-JSON: [\"" . FormatThing($row['type'],$row['data'],true) . "\",\"" . FormatThing($row['type'],$row['data'],false) . "\",\"8\",\"".$_POST['type']."\",null,".$row['amount']."]");

?>
<h4>&nbsp;</h4>

<div id="inventory-preview-box"></div>

<div id="inventory-preview-place" class="clearfix">
	<div class="clearfix">
		<a href="#" class="new-button" id="inventory-place"><b>Colocar</b><i></i></a>
	</div>
<?php if($row['amount'] > 1 && $row['type'] == "1"){ ?>
	<div class="clearfix">
		<a href="#" class="new-button" id="inventory-place-all"><b>Todo</b><i></i></a>
	</div>
<?php } ?>
</div>
<?php
} elseif($mode == "noteeditor"){
?>
<form action="#" method="post" id="webstore-notes-form">

<input type="hidden" name="maxlength" id="webstore-notes-maxlength" value="500" />

<div id="webstore-notes-counter"><?php echo 500 - strlen(HoloText($_POST['noteText'])); ?></div>

<p>
	<select id="webstore-notes-skin" name="skin">
			<option value="1" id="webstore-notes-skin-defaultskin">Por defecto</option>
			<option value="6" id="webstore-notes-skin-goldenskin">Dorado</option>
			<option value="3" id="webstore-notes-skin-metalskin">Metal</option>
			<option value="5" id="webstore-notes-skin-notepadskin">Bloc de notas</option>
			<option value="2" id="webstore-notes-skin-speechbubbleskin">Bocadillo</option>
			<option value="4" id="webstore-notes-skin-noteitskin">Nota adhesiva</option>
	</select>
</p>

<p class="warning">&iexcl;Atenci&oacute;n! Este texto no se puede editar una vez colocada la nota en tu p&aacute;gina.</p>

<div id="webstore-notes-edit-container">
<textarea id="webstore-notes-text" rows="7" cols="42" name="noteText"><?php echo HoloText($_POST['noteText']); ?></textarea>
    <script type="text/javascript">
        bbcodeToolbar = new Control.TextArea.ToolBar.BBCode("webstore-notes-text");
        bbcodeToolbar.toolbar.toolbar.id = "bbcode_toolbar";
        var colors = { "red" : ["#d80000", "Red"],
            "orange" : ["#fe6301", "Orange"],
            "yellow" : ["#ffce00", "Yellow"],
            "green" : ["#6cc800", "Green"],
            "cyan" : ["#00c6c4", "Cyan"],
            "blue" : ["#0070d7", "Blue"],
            "gray" : ["#828282", "Grey"],
            "black" : ["#000000", "Black"]
        };
        bbcodeToolbar.addColorSelect("Colours", colors, true);
    </script>


</form>

<p>
<a href="#" class="new-button" id="webstore-confirm-cancel"><b>Cancelar</b><i></i></a>
<a href="#" class="new-button" id="webstore-notes-continue"><b>Continuar</b><i></i></a>
</p>

<div class="clear"></div>
<?php
} elseif($mode == "noteeditor-preview"){
?>
<div id="webstore-notes-container">
<?php
if($user_rank < 6){ $text = $_POST['noteText']; } else { $text = $_POST['noteText']; }
$newskin = $_POST['skin'];

	if($newskin == 1){ $skin = "defaultskin"; }
	else if($newskin == 2){ $skin = "speechbubbleskin"; }
	else if($newskin == 3){ $skin = "metalskin"; }
	else if($newskin == 4){ $skin = "noteitskin"; }
	else if($newskin == 5){ $skin = "notepadskin"; }
	else if($newskin == 6){ $skin = "goldenskin"; }
	else { $skin = "defaultskin"; }

	echo "<div class=\"movable stickie n_skin_".$skin."-c\" style=\" left: 0px; top: 0px; z-index: 1;\" id=\"stickie--1\">
	<div class=\"n_skin_".$skin."\" >
		<div class=\"stickie-header\">
			<h3></h3>
			<div class=\"clear\"></div>
		</div>
		<div class=\"stickie-body\">
			<div class=\"stickie-content\">
				<div class=\"stickie-markup\">" . bbcode_format(nl2br(HoloText($text))) . "</div>
				<div class=\"stickie-footer\">
				</div>
			</div>
		</div>
	</div>
</div></div>";
?>
<p class="warning">&iexcl;Atenci&oacute;n! Este texto no se puede editar una vez colocada la nota en tu p&aacute;gina.</p>

<p>
<a href="#" class="new-button" id="webstore-notes-edit"><b>Editar</b><i></i></a>
<a href="#" class="new-button" id="webstore-notes-add"><b>A&ntilde;adir a la p&aacute;gina</b><i></i></a>
</p>

<div class="clear"></div>
<?php
} elseif($mode == "noteeditor-place"){

	if($user_rank < 6){ $data = $_POST['noteText']; } else { $data = $_POST['noteText']; }
	$newskin = $_POST['skin'];

	if($newskin == 1){ $skin = "defaultskin"; }
	else if($newskin == 2){ $skin = "speechbubbleskin"; }
	else if($newskin == 3){ $skin = "metalskin"; }
	else if($newskin == 4){ $skin = "noteitskin"; }
	else if($newskin == 5){ $skin = "notepadskin"; }
	else if($newskin == 6){ $skin = "goldenskin"; }
	else { $skin = "defaultskin"; }

	if(strlen($data) < 501 && strlen($data) > 0){

		if($linked > 0){
			mysql_query("INSERT INTO cms_homes_stickers (userid,groupid,x,y,z,data,type,subtype,skin) VALUES ('".$my_id."','".$groupid."','10','10','18','".FilterText($data)."','3','0','".$skin."')") or die(mysql_error());
			$sql = mysql_query("SELECT id FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '".$groupid."' AND type = '3' AND data = '".FilterText($data)."' ORDER BY id DESC LIMIT 1") or die(mysql_error());
			$sql2 = mysql_query("SELECT id FROM cms_homes_inventory WHERE userid = '".$my_id."' AND type = '3' LIMIT 1") or die(mysql_error());
		} else {
			mysql_query("INSERT INTO cms_homes_stickers (userid,groupid,x,y,z,data,type,subtype,skin) VALUES ('".$my_id."','-1','10','10','18','".FilterText($data)."','3','0','".$skin."')") or die(mysql_error());
			$sql = mysql_query("SELECT id FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '3' AND data = '".FilterText($data)."' ORDER BY id DESC LIMIT 1") or die(mysql_error());
			$sql2 = mysql_query("SELECT id FROM cms_homes_inventory WHERE userid = '".$my_id."' AND type = '3' LIMIT 1") or die(mysql_error());
		}

		$row = mysql_fetch_assoc($sql);
		$row2 = mysql_fetch_assoc($sql2);

		UpdateOrDelete($row2['id'],$my_id);

		$id = $row['id'];
		header("X-JSON: " . $id );

		$edit = "\n<img src=\"./web-gallery/images/myhabbo/icon_edit.gif\" width=\"19\" height=\"18\" class=\"edit-button\" id=\"stickie-" . $id . "-edit\" />
<script language=\"JavaScript\" type=\"text/javascript\">
Event.observe(\"stickie-" . $id . "-edit\", \"click\", function(e) { openEditMenu(e, " . $id . ", \"stickie\", \"stickie-" . $id . "-edit\"); }, false);
</script>\n";

		echo "<div class=\"movable stickie n_skin_".$skin."-c\" style=\" left: 0px; top: 0px; z-index: 1;\" id=\"stickie-" . $id . "\">
	<div class=\"n_skin_".$skin."\" >
		<div class=\"stickie-header\">
			<h3>".$edit."</h3>
			<div class=\"clear\"></div>
		</div>
		<div class=\"stickie-body\">
			<div class=\"stickie-content\">
				<div class=\"stickie-markup\">" . bbcode_format(nl2br(HoloText($data))) . "</div>
				<div class=\"stickie-footer\">
				</div>
			</div>
		</div>
	</div>
</div></div>";

	}
} elseif($mode == "place_sticker"){

$id = FilterText($_POST['selectedStickerId']);
$z = FilterText($_POST['zindex']);
$placeAll = $_POST['placeAll'];

$check = mysql_query("SELECT data,amount FROM cms_homes_inventory WHERE userid = '".$my_id."' AND type = '1' AND id = '".$id."' LIMIT 1") or die(mysql_error());
$exists = mysql_num_rows($check);

	if($exists > 0){
		$row = mysql_fetch_assoc($check);

		if($placeAll == "true"){
			$amount = $row['amount'];
		} else {
			$amount = 1;
		}

		$header_pack = "X-JSON: [";

		for ($i = 1; $i <= $amount; $i++) {
			if($linked > 0){
				mysql_query("INSERT INTO cms_homes_stickers (userid,groupid,x,y,z,type,subtype,data,skin) VALUES ('".$my_id."','".$groupid."','10','10','".$z."','1','0','".$row['data']."','')") or die(mysql_error());
				$check = mysql_query("SELECT id FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '".$groupid."' AND type = '1' AND data = '".$row['data']."' ORDER BY id DESC LIMIT 1") or die(mysql_error());
			} else {
				mysql_query("INSERT INTO cms_homes_stickers (userid,groupid,x,y,z,type,subtype,data,skin) VALUES ('".$my_id."','-1','10','10','".$z."','1','0','".$row['data']."','')") or die(mysql_error());
				$check = mysql_query("SELECT id FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '1' AND data = '".$row['data']."' ORDER BY id DESC LIMIT 1") or die(mysql_error());
			}
			$assoc = mysql_fetch_assoc($check);
			$edit = "\n<img src=\"./web-gallery/images/myhabbo/icon_edit.gif\" width=\"19\" height=\"18\" class=\"edit-button\" id=\"sticker-" . $assoc['id'] . "-edit\" />
<script language=\"JavaScript\" type=\"text/javascript\">
Event.observe(\"sticker-" . $assoc['id'] . "-edit\", \"click\", function(e) { openEditMenu(e, " . $assoc['id'] . ", \"sticker\", \"sticker-" . $assoc['id'] . "-edit\"); }, false);
</script>\n";
			$sticker_pack = $sticker_pack . "<div class=\"movable sticker s_" . $row['data'] . "\" style=\"left: 10px; top: 10px; z-index: " . $z . "\" id=\"sticker-" . $assoc['id'] . "\">\n" . $edit . "\n</div>\n";
			if($i == 1){ // X-JSON: [1
				$header_pack = $header_pack . $assoc['id'];
			} else { // X-JSON [1,2
				$header_pack = $header_pack . "," . $assoc['id'];
			}
		}

		$header_pack = $header_pack . "]";

		if($placeAll == "true"){
			mysql_query("DELETE FROM cms_homes_inventory WHERE userid = '".$my_id."' AND id = '".$id."' AND type = '1' LIMIT 1");
		} else {
			UpdateOrDelete($id,$my_id);
		}

		header($header_pack);
		echo $sticker_pack;

 	}

} elseif($mode == "background_warning"){
?>
<p>
La imagen que has seleccionado se mantendr&aacute; como fondo de la p&aacute;gina hasta que selecciones otra imagen o cierres la Tienda. Si quieres conservarla como fondo, deber&aacute;s comprarla y seleccionarla desde tu inventario.
</p>

<p>
<a href="#" class="new-button" id="webstore-warning-ok"><b>Aceptar</b><i></i></a>
</p>

<div class="clear"></div>
<?php
} else {
//echo "<b>Error:</b> Unknown mode " . $mode . ".";
header("Location: index.php");
}
?>