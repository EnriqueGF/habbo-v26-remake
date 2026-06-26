<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright &copy; 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

$allow_guests = true;

require_once('core.php');
require_once('includes/session.php');

// Search function
if(isset($_POST['searchString'])){
	$searchString = FilterText($_POST['searchString']);
	$check = mysql_query("SELECT id FROM groups_details WHERE name LIKE '".$searchString."' LIMIT 1") or die(mysql_error());
	$found = mysql_num_rows($check);
	if($found > 0){
		$tmp = mysql_fetch_assoc($check);
		header("Location: group_profile.php?id=" . $tmp['id']);
		exit;
	}
}

if(isset($_GET['id']) && is_numeric($_GET['id'])){

	$check = mysql_query("SELECT * FROM groups_details WHERE id = '".$_GET['id']."' LIMIT 1");
	$exists = mysql_num_rows($check);

	if($exists > 0){

		$groupid = $_GET['id'];

		$error = false;
		$groupdata = mysql_fetch_assoc($check);

		$pagename = $groupdata['name'];
		$ownerid = $groupdata['ownerid'];

		$members = mysql_evaluate("SELECT COUNT(*) FROM groups_memberships WHERE groupid = '".$groupid."' AND is_pending = '0'");

		$check = mysql_query("SELECT * FROM groups_memberships WHERE userid = '".$my_id."' AND groupid = '".$groupid."' AND is_pending = '0' LIMIT 1");
		$is_member = mysql_num_rows($check);

		if($is_member > 0 && $logged_in){

			$is_member = true;
			$my_membership = mysql_fetch_assoc($check);
			$member_rank = $my_membership['member_rank'];

		} else {

			$is_member = false;

		}

	} else {

		$error = true;

	}

} else {

	$error = true;

}


if(isset($_GET['do']) && $_GET['do'] == "edit" && $logged_in){

	if($is_member && $member_rank > 1){

		$edit_mode = true;

		$check = mysql_query("SELECT * FROM cms_homes_group_linker WHERE userid = '".$my_id."' LIMIT 1") or die(mysql_error());
		$linkers = mysql_num_rows($check);

		if($linkers > 0){

			mysql_query("UPDATE cms_homes_group_linker SET active = '1', groupid = '".$groupid."' WHERE userid = '".$my_id."' LIMIT 1") or die(mysql_error());

		} else {

			mysql_query("INSERT INTO cms_homes_group_linker (userid,groupid,active) VALUES ('".$my_id."','".$groupid."','1')") or die(mysql_error());

		}

	} else {

		header("location:group_profile.php?do=bounce&id=".$groupid."");
		$edit_mode = false;

	}

} else {

	$edit_mode = false;

}

if(!$error){

	$body_id = "viewmode";

	if($edit_mode){

		$body_id = "editmode";

	}

} else {

	$body_id = "home";

}

$pageid = "profile";

if($groupdata['type'] !== "1" && $is_member !== true){
	// If the group type is NOT exclusive/moderated, we have to delete any pending requests
	// this user has, simply because there's no longer need to put the user in the waiting list.
	$remove_pending = mysql_query("DELETE FROM groups_memberships WHERE is_pending = '1' AND userid = '".$my_id."' AND groupid = '".$groupid."' LIMIT 1") or die(mysql_error());
}

$viewtools = "	<div class=\"myhabbo-view-tools\">\n";

if($logged_in && !$is_member && $groupdata['type'] !== "2" && $my_membership['is_pending'] !== "1"){ $viewtools = $viewtools . "<a href=\"joingroup.php?groupId=".$groupid."\" id=\"join-group-button\">"; if($groupdata['type'] == "0" || $groupdata['type'] == "3"){ $viewtools = $viewtools . "Unirse"; } else { $viewtools = $viewtools . "Solicitar membres&iacute;a"; } $viewtools = $viewtools . "</a>"; }
if($logged_in && $my_membership['is_current'] !== "1" && $is_member){ $viewtools = $viewtools . "<a href=\"#\" id=\"select-favorite-button\">Marcar como favorito</a>\n"; }
if($logged_in && $my_membership['is_current'] == "1" && $is_member){ $viewtools = $viewtools . "<a href=\"#\" id=\"deselect-favorite-button\">Quitar de favoritos</a>"; }
if($logged_in && $is_member && $my_id !== $ownerid){ $viewtools = $viewtools . "<a href=\"leavegroup.php?groupId=".$groupid."\" id=\"leave-group-button\">Abandonar el grupo</a>\n"; }

$viewtools = $viewtools . "	</div>\n";


$bg_fetch = mysql_query("SELECT data FROM cms_homes_stickers WHERE type = '4' AND groupid = '".$groupid."' LIMIT 1");
$bg_exists = mysql_num_rows($bg_fetch);

	if($bg_exists < 1){ // if there's no background override for this user set it to the standard
		$bg = "b_bg_pattern_abstract2";
	} else {
		$bg = mysql_fetch_array($bg_fetch);
		$bg = "b_" . $bg[0];
	}

if(!$error){
include('templates/community/hsubheader.php');
include('templates/community/header.php');
mysql_query("UPDATE groups_details SET views = views+'1' WHERE id='".$groupid."' LIMIT 1");
?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="mypage-wrapper" class="cbb blue">
<div class="box-tabs-container box-tabs-left clearfix">
	<?php if($member_rank > 1 && !$edit_mode){ ?><a href="#" id="myhabbo-group-tools-button" class="new-button dark-button edit-icon" style="float:left"><b><span></span>Editar</b><i></i></a><?php } ?>
	<?php if(!$edit_mode){ echo $viewtools; } ?>
    <h2 class="page-owner">
<?php echo HoloText($groupdata['name']); ?>&nbsp;
<?php if($groupdata['type'] == "2"){ ?><img src='./web-gallery/images/status_closed_big.gif' alt='Closed Group' title='Closed Group'><?php } ?>
<?php if($groupdata['type'] == "1"){ ?><img src='./web-gallery/images/status_exclusive_big.gif' alt='Moderated Group' title='Moderated Group'><?php } ?></h2>
</h2>
    <ul class="box-tabs">
        <li class="selected"><a href="group_profile.php?id=<?php echo $_GET['id']; ?>">Grupo</a><span class="tab-spacer"></span></li>
        <li><a href="group_forum.php?id=<?php echo $_GET['id']; ?>">Foro del grupo <?php if($groupdata['pane'] > 0) { ?><img src="http://images.habbohotel.nl/habboweb/23_deebb3529e0d9d4e847a31e5f6fb4c5b/9/web-gallery/images/grouptabs/privatekey.png"><?php } ?></a><span class="tab-spacer"></span></li>
    </ul>
</div>
	<div id="mypage-content">
<?php 	if($edit_mode == true){ ?>
<div id="top-toolbar" class="clearfix">
	<ul>
		<li><a href="#" id="inventory-button">Inventario</a></li>
		<li><a href="#" id="webstore-button">Tienda</a></li>
	</ul>

	<form action="#" method="get" style="width: 50%;">
		<a id="cancel-button" class="new-button red-button cancel-icon" href="#"><b><span></span>Cancelar</b><i></i></a>
		<a id="save-button" class="new-button green-button save-icon" href="#"><b><span></span>Guardar</b><i></i></a>
	</form>
</div>
<?php 	} ?>
		<div id="mypage-bg" class="<?php echo $bg; ?>">
			<div id="playground-outer">
				<div id="playground">

<?php
$get_em = mysql_query("SELECT id,type,x,y,z,data,skin,subtype,var FROM cms_homes_stickers WHERE groupid = '".$groupid."' and type < 4 LIMIT 200") or die(mysql_error());

while ($row = mysql_fetch_array($get_em, MYSQL_NUM)) {

	switch($row[1]){
	case 1: $type = "sticker"; break;
	case 2: $type = "widget"; break;
	case 3: $type = "stickie"; break;
	case 4: $type = "ignore"; break;
	}

	if($edit_mode == true){
	$edit = "\n<img src=\"./web-gallery/images/myhabbo/icon_edit.gif\" width=\"19\" height=\"18\" class=\"edit-button\" id=\"" . $type . "-" . $row[0] . "-edit\" />
<script language=\"JavaScript\" type=\"text/javascript\">
Event.observe(\"".$type."-".$row[0]."-edit\", \"click\", function(e) { openEditMenu(e, ".$row[0].", \"".$type."\", \"".$type."-".$row[0]."-edit\"); }, false);
</script>\n";
	} else {
	$edit = " ";
	}

	if($type == "stickie"){
	printf("<div class=\"movable stickie n_skin_%s-c\" style=\" left: %spx; top: %spx; z-index: %s;\" id=\"stickie-%s\">
	<div class=\"n_skin_%s\" >
		<div class=\"stickie-header\">
			<h3>%s</h3>
			<div class=\"clear\"></div>
		</div>
		<div class=\"stickie-body\">
			<div class=\"stickie-content\">
				<div class=\"stickie-markup\">%s</div>
				<div class=\"stickie-footer\">
				</div>
			</div>
		</div>
	</div>
</div>",$row[6],$row[2],$row[3],$row[4],$row[0],$row[6],$edit,bbcode_format(nl2br(HoloText($row[5]))));
	} elseif($type == "sticker"){
	printf("<div class=\"movable sticker s_%s\" style=\"left: %spx; top: %spx; z-index: %s\" id=\"sticker-%s\">\n%s\n</div>", $row[5], $row[2], $row[3], $row[4], $row[0], $edit);
	} elseif($type == "widget"){

		switch($row[7]){
		case "1": $subtype = "Profilewidget"; break;
		case "3": $subtype = "MemberWidget"; break;
		case "4": $subtype = "GuestbookWidget"; break;
		case "5": $subtype = "TraxPlayerWidget";
		}

		if($subtype == "Profilewidget"){

		$found_profile = true;

		echo "<div class=\"movable widget GroupInfoWidget\" id=\"widget-".$row[0]."\" style=\" left: ".$row[2]."px; top: ".$row[3]."px; z-index: ".$row[4].";\">
<div class=\"w_skin_".$row[6]."\">
	<div class=\"widget-corner\" id=\"widget-".$row[0]."-handle\">
		<div class=\"widget-headline\"><h3><span class=\"header-left\">&nbsp;</span><span class=\"header-middle\">Informaci&oacute;n del grupo</span><span class=\"header-right\">".$edit."</span></h3>
		</div>
	</div>
	<div class=\"widget-body\">
		<div class=\"widget-content\">";
			?>

<div class=\"group-info-icon\"><img src='./habbo-imaging/<?php if(!isset($_GET['x'])) { echo "badge-fill/".$groupdata['badge'].".gif"; }else{ echo "badge.php?badge=".$groupdata['badge'].""; } ?>' /></div>
<?php echo "
<h4>".HoloText($groupdata['name'])."</h4>

<p>
Creado el: <strong>".$groupdata['created']."</strong>
</p>

<p>
<strong>".$members."</strong> miembros
</p>";
if($groupdata['roomid'] != 0 OR $groupdata['roomid'] != "" OR $groupdata['roomid'] != " ") {
$sql = mysql_query("SELECT name FROM rooms WHERE id='".$groupdata['roomid']."' LIMIT 1");
$roominfo = mysql_fetch_assoc($sql); ?>
<?php if($groupdata['roomid'] <> 0){ ?><p><a href="client.php?forwardId=2&amp;roomId=<?php echo $groupdata['roomid']; ?>" onclick="HabboClient.roomForward(this, '<?php echo $groupdata['roomid']; ?>', 'private'); return false;" target="client" class="group-info-room"><?php echo HoloText($roominfo['name']); ?></a></p><?php } ?>
<?php
}

echo "\n<div class=\"group-info-description\">".HoloText($groupdata['description'])."</div>

<script type=\"text/javascript\">
    document.observe(\"dom:loaded\", function() {
        new GroupInfoWidget('".$groupid."', '".$row[0]."');
    });
</script>

		<div class=\"clear\"></div>
		</div>
	</div>
</div>
</div>";
		} elseif($subtype == "MemberWidget"){
			echo "<div class=\"movable widget MemberWidget\" id=\"widget-".$row[0]."\" style=\" left: ".$row[2]."px; top: ".$row[3]."px; z-index: ".$row[4].";\">
<div class=\"w_skin_".$row[6]."\">
	<div class=\"widget-corner\" id=\"widget-".$row[0]."-handle\">
		<div class=\"widget-headline\"><h3><span class=\"header-left\">&nbsp;</span><span class=\"header-middle\">Miembros de este grupo (<span id=\"avatar-list-size\">".$members."</span>)</span><span class=\"header-right\">".$edit."</span></h3>
		</div>
	</div>
	<div class=\"widget-body\">
		<div class=\"widget-content\">

<div id=\"avatar-list-search\">
<input type=\"text\" style=\"float:left;\" id=\"avatarlist-search-string\"/>
<a class=\"new-button\" style=\"float:left;\" id=\"avatarlist-search-button\"><b>Buscar</b><i></i></a>
</div>
<br clear=\"all\"/>

<div id=\"avatarlist-content\">\n";

$bypass = true;
$widgetid = $row['0'];
include('./myhabbo/avatarlist_membersearchpaging.php');

echo "<script type=\"text/javascript\">
document.observe(\"dom:loaded\", function() {
	window.widget".$row[0]." = new MemberWidget('".$groupid."', '".$row[0]."');
});
</script>

</div>
		<div class=\"clear\"></div>
		</div>
	</div>
</div>
</div>";
	} elseif($subtype == "GuestbookWidget"){
	$sql = mysql_query("SELECT * FROM cms_guestbook WHERE widget_id = '".$row['0']."' ORDER BY id DESC");
	$count = mysql_num_rows($sql);
	if($row['10'] == "0"){;
		$status = "public";
	}else{
		$status = "private";
	}
	?>
	<div class="movable widget GuestbookWidget" id="widget-<?php echo $row['0']; ?>" style=" left: <?php echo $row['2']; ?>px; top: <?php echo $row['3']; ?>px; z-index: <?php echo $row['4']; ?>;">
<div class="w_skin_<?php echo $row['6']; ?>">
	<div class="widget-corner" id="widget-<?php echo $row['0']; ?>-handle">
		<div class="widget-headline"><h3>
		<?php echo $edit; ?>
		<span class="header-left">&nbsp;</span><span class="header-middle">Mi libro de visitas(<span id="guestbook-size"><?php echo $count; ?></span>) <span id="guestbook-type" class="<?php echo $status; ?>"><img src="./web-gallery/images/groups/status_exclusive.gif" title="Solo amigos" alt="Solo amigos"/></span></span><span class="header-right">&nbsp;</span></h3>
		</div>	
	</div>
	<div class="widget-body">
		<div class="widget-content">
<div id="guestbook-wrapper" class="gb-public">
<ul class="guestbook-entries" id="guestbook-entry-container">
	<?php if($count == 0){ ?>
	<div id="guestbook-empty-notes">Ninguna entrada</div>
	<?php } else { ?>
			<?php 
			$sql123 = mysql_query("SELECT * FROM groups_details WHERE id = '".$_GET['id']."' LIMIT 1");
			$grouprrow = mysql_fetch_assoc($sql123);
			$i = 0;
			while ($row1 = mysql_fetch_assoc($sql)) {
				$i++;
				$userrow = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id = '".$row1['userid']."' LIMIT 1"));
				if($my_id == $row1['userid']){
					$owneronly = "<img src=\"./web-gallery/images/myhabbo/buttons/delete_entry_button.gif\" id=\"gbentry-delete-".$row1['id']."\" class=\"gbentry-delete\" style=\"cursor:pointer\" alt=\"\"/><br/>";
				} elseif($grouprrow['ownerid'] == $my_id) {
					$owneronly = "<img src=\"./web-gallery/images/myhabbo/buttons/delete_entry_button.gif\" id=\"gbentry-delete-".$row1['id']."\" class=\"gbentry-delete\" style=\"cursor:pointer\" alt=\"\"/><br/>";
				} else {
					$owneronly = "";
				}
				if(IsUserOnline($row1['userid'])){ $useronline = "online"; } else { $useronline = "offline"; }
				printf("	<li id=\"guestbook-entry-%s\" class=\"guestbook-entry\">
		<div class=\"guestbook-author\">
			<img src=\"/habbo-imaging/avatarimage?figure=%s&direction=2&head_direction=2&gesture=sml&size=s\" alt=\"%s\" title=\"%s\"/>
		</div>
			<div class=\"guestbook-actions\">
					$owneronly
			</div>
		<div class=\"guestbook-message\">
			<div class=\"%s\">
				<a href=\"./user_profile.php?id=%s\">%s</a>
			</div>
			<p>%s</p>
		</div>
		<div class=\"guestbook-cleaner\">&nbsp;</div>
		<div class=\"guestbook-entry-footer metadata\">%s</div>
	</li>",$row1['id'], $userrow['figure'], $userrow['name'], $userrow['name'], $useronline, $userrow['id'], $userrow['name'], HoloText($row1['message'],false ,true), $userrow['time']);
			}
	} ?>
</ul></div>
<?php if($edit_mode == false){ ?>
	<div class="guestbook-toolbar clearfix">
	<a href="#" class="new-button envelope-icon" id="guestbook-open-dialog">
	<b><span></span>Publicar nuevo mensaje</b><i></i>
	</a>
	</div>
<?php } ?>
<script type="text/javascript">	
	document.observe("dom:loaded", function() {
		var gb<?php echo $row['0']; ?> = new GuestbookWidget('17570', '<?php echo $row['0']; ?>', 500);
		var editMenuSection = $('guestbook-privacy-options');
		if (editMenuSection) {
			gb<?php echo $row['0']; ?>.updateOptionsList('public');
		}
	});
</script>
		<div class="clear"></div>
		</div>
	</div>
</div>
</div>
<?php
	} elseif($subtype == "TraxPlayerWidget"){ 
		$sql123 = mysql_query("SELECT * FROM groups_details WHERE id = '".$_GET['id']."' LIMIT 1");
		$grouprrow = mysql_fetch_assoc($sql123);?>
		<div class="movable widget TraxPlayerWidget" id="widget-<?php echo $row['0']; ?>" style=" left: <?php echo $row['2']; ?>px; top: <?php echo $row['3']; ?>px; z-index: <?php echo $row['4']; ?>;">
<div class="w_skin_<?php echo $row['6']; ?>">
	<div class="widget-corner" id="widget-<?php echo $row['0']; ?>-handle">
		<div class="widget-headline"><h3><?php echo $edit; ?><span class="header-left">&nbsp;</span><span class="header-middle">TRAXPLAYER</span><span class="header-right">&nbsp;</span></h3>
		</div>	
	</div>
	<div class="widget-body">
		<div class="widget-content">
<?php 
if($row['8'] == ""){ $songselected = false; }else{ $songselected = true; }
if($edit_mode == true){ ?>
<div id="traxplayer-content" style="text-align: center;">
	<img src="./web-gallery/images/traxplayer/player.png"/>
</div>

<div id="edit-menu-trax-select-temp" style="display:none">
    <select id="trax-select-options-temp">
    <option value="">- Elige una canci&oacute;n -</option>
	<?php
	$mysql = mysql_query("SELECT * FROM furniture WHERE ownerid = '".$grouprrow['ownerid']."'");
	$i = 0;
	while($machinerow = mysql_fetch_assoc($mysql)){
		$i++;
		$sql = mysql_query("SELECT * FROM soundmachine_songs WHERE machineid = '".$machinerow['id']."'");
		$n = 0;
		while($songrow = mysql_fetch_assoc($sql)){
			$n++;
			if($songrow['id'] <> ""){ echo "		<option value=\"".$songrow['id']."\">".trim(nl2br(HoloText($songrow['title'])))."</option>\n"; }
		}
	} ?>
    </select>

</div>
<?php }elseif($songselected == false){ ?>
No tienes ninguna canci&oacute;n Trax seleccionada.
<?php }else{
$sql1 = mysql_query("SELECT * FROM soundmachine_songs WHERE id = '".$row['8']."' LIMIT 1");
$songrow1 = mysql_fetch_assoc($sql); ?>
<div id="traxplayer-content" style="text-align:center;"></div>
<embed type="application/x-shockwave-flash"
src="<?php echo $path; ?>web-gallery/flash/traxplayer/traxplayer.swf" name="traxplayer" quality="high"
base="<?php echo $path; ?>web-gallery/flash/traxplayer/" allowscriptaccess="always" menu="false"
wmode="transparent" flashvars="songUrl=<?php echo $path; ?>myhabbo/trax_song.php?songId=<?php echo $row['8']; ?>&amp;sampleUrl=http://images.habbohotel.com/dcr/hof_furni//mp3/" height="66" width="210" />
<?php } ?>

		<div class="clear"></div>
		</div>
	</div>
</div>
</div><?php
	}

	}

}

if($found_profile !== true){

	mysql_query("INSERT INTO cms_homes_stickers (userid,groupid,type,subtype,x,y,z,skin) VALUES ('-1','".$groupid."','2','1','25','25','5','defaultskin')") or die(mysql_error());

		echo "<div class=\"movable widget GroupInfoWidget\" id=\"widget-".$row[0]."\" style=\" left: 25px; top: 25px; z-index: 5;\">
<div class=\"w_skin_defaultskin\">
	<div class=\"widget-corner\" id=\"widget-1994412-handle\">
		<div class=\"widget-headline\"><h3><span class=\"header-left\">&nbsp;</span><span class=\"header-middle\">Informaci&oacute;n del grupo</span><span class=\"header-right\">&nbsp;</span></h3>
		</div>
	</div>
	<div class=\"widget-body\">
		<div class=\"widget-content\">

<div class=\"group-info-icon\"><img src='./habbo-imaging/badge-fill/".$groupdata['badge'].".gif' /></div>

<h4>".HoloText($groupdata['name'])."</h4>

<p>
Creado: <strong>".$groupdata['created']."</strong>
</p>

<p>
<strong>".$members."</strong> miembros
</p>\n";

// <p><a href=\"http://www.habbo.nl/client?forwardId=2&amp;roomId=13303122\" onclick=\"roomForward(this, '13303122', 'private'); return false;\" target=\"client\" class=\"group-info-room\">The church of bobbaz</a></p>

echo "\n<div class=\"group-info-description\">".HoloText($groupdata['description'])."</div>

<script type=\"text/javascript\">
    document.observe(\"dom:loaded\", function() {
        new GroupInfoWidget('55918', '1478728');
    });
</script>

		<div class=\"clear\"></div>
		</div>
	</div>
</div>
</div>";

}
?>
				</div>
			</div>
			<div id="mypage-ad">
    <div class="habblet ">
<div class="ad-container">
&nbsp;
</div>
    </div>
			</div>

		</div>
	</div>
</div>

<script language="JavaScript" type="text/javascript">
initEditToolbar();
initMovableItems();
document.observe("dom:loaded", initDraggableDialogs);
</script>


<div id="edit-save" style="display:none;"></div>
    </div>
<div id="footer">
	<p><a href="index.php" target="_self">Inicio</a> | <a href="./disclaimer.php" target="_self">Condiciones de uso</a> | <a href="./privacy.php" target="_self">Informaci&oacute;n pr&aacute;ctica</a></p>

</div></div>

</div>

<?php if($edit_mode){ ?>
<div id="edit-menu" class="menu">
	<div class="menu-header">
		<div class="menu-exit" id="edit-menu-exit"><img src="./web-gallery/images/dialogs/menu-exit.gif" alt="" width="11" height="11" /></div>
		<h3>Editar</h3>
	</div>
	<div class="menu-body">
		<div class="menu-content">
			<form action="#" onsubmit="return false;">
				<div id="edit-menu-skins">
	<select id="edit-menu-skins-select">
			<option value="1" id="edit-menu-skins-select-defaultskin">Default</option>
			<option value="6" id="edit-menu-skins-select-goldenskin">Golden</option>
<?php if(IsHCMember($my_id)){ ?>
			<option value="8" id="edit-menu-skins-select-hc_pillowskin">HC Bling</option>
			<option value="7" id="edit-menu-skins-select-hc_machineskin">HC Scifi</option>
<?php } ?>
<?php if($user_rank > 5){ ?>
			<option value="9" id="edit-menu-skins-select-nakedskin">Staff - Naked Skin</option>
<?php } ?>
			<option value="3" id="edit-menu-skins-select-metalskin">Metal</option>
			<option value="5" id="edit-menu-skins-select-notepadskin">Notepad</option>
			<option value="2" id="edit-menu-skins-select-speechbubbleskin">Speech Bubble</option>
			<option value="4" id="edit-menu-skins-select-noteitskin">Stickie Note</option>
	</select>
				</div>
				<div id="edit-menu-stickie">
					<p>&iexcl;Atenci&oacute;n! Si haces clic en 'Eliminar', la nota se borrar&aacute; de forma permanente.</p>
				</div>
				<div id="rating-edit-menu">
					<input type="button" id="ratings-reset-link"
						value="Restablecer valoraci&oacute;n" />
				</div>
				<div id="highscorelist-edit-menu" style="display:none">
					<select id="highscorelist-game">
						<option value="">Seleccionar juego</option>
						<option value="1">Battle Ball: Rebound!</option>
						<option value="2">SnowStorm</option>
						<option value="0">Wobble Squabble</option>
					</select>
				</div>
				<div id="edit-menu-remove-group-warning">
					<p>Este objeto pertenece a otro usuario. Si lo eliminas, volver&aacute; a su inventario.</p>
				</div>
				<div id="edit-menu-gb-availability">
					<select id="guestbook-privacy-options">
						<option value="private">Solo miembros</option>
						<option value="public">P&uacute;blico</option>
					</select>
				</div>
				<div id="edit-menu-trax-select">
					<select id="trax-select-options"></select>
				</div>
				<div id="edit-menu-remove">
					<input type="button" id="edit-menu-remove-button" value="Eliminar" />
				</div>
			</form>
			<div class="clear"></div>
		</div>
	</div>
	<div class="menu-bottom"></div>
</div>
<script language="JavaScript" type="text/javascript">
Event.observe(window, "resize", function() { if (editMenuOpen) closeEditMenu(); }, false);
Event.observe(document, "click", function() { if (editMenuOpen) closeEditMenu(); }, false);
Event.observe("edit-menu", "click", Event.stop, false);
Event.observe("edit-menu-exit", "click", function() { closeEditMenu(); }, false);
Event.observe("edit-menu-remove-button", "click", handleEditRemove, false);
Event.observe("edit-menu-skins-select", "click", Event.stop, false);
Event.observe("edit-menu-skins-select", "change", handleEditSkinChange, false);
Event.observe("guestbook-privacy-options", "click", Event.stop, false);
Event.observe("guestbook-privacy-options", "change", handleGuestbookPrivacySettings, false);
Event.observe("trax-select-options", "click", Event.stop, false);
Event.observe("trax-select-options", "change", handleTraxplayerTrackChange, false);
</script>
<?php } else { ?>
<div class="cbb topdialog" id="guestbook-form-dialog">
	<h2 class="title dialog-handle">Editar entrada del libro de visitas</h2>

	<a class="topdialog-exit" href="#" id="guestbook-form-dialog-exit">X</a>
	<div class="topdialog-body" id="guestbook-form-dialog-body">
<div id="guestbook-form-tab">
<form method="post" id="guestbook-form">
    <p>
        Nota: los mensajes no pueden superar los 200 caracteres
        <input type="hidden" name="ownerId" value="441794" />
	</p>
	<div>
	    <textarea cols="15" rows="5" name="message" id="guestbook-message"></textarea>
    <script type="text/javascript">
        bbcodeToolbar = new Control.TextArea.ToolBar.BBCode("guestbook-message");
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
        bbcodeToolbar.addColorSelect("Colores", colors, true);
    </script>
    </div>

	<div class="guestbook-toolbar clearfix">
		<a href="#" class="new-button" id="guestbook-form-cancel"><b>Cancelar</b><i></i></a>
		<a href="#" class="new-button" id="guestbook-form-preview"><b>Vista previa</b><i></i></a>
	</div>
</form>
</div>
<div id="guestbook-preview-tab">&nbsp;</div>
	</div>
</div>
<div class="cbb topdialog" id="guestbook-delete-dialog">
	<h2 class="title dialog-handle">Eliminar</h2>

	<a class="topdialog-exit" href="#" id="guestbook-delete-dialog-exit">X</a>
	<div class="topdialog-body" id="guestbook-delete-dialog-body">
<form method="post" id="guestbook-delete-form">
	<input type="hidden" name="entryId" id="guestbook-delete-id" value="" />
	<p>&iquest;Est&aacute;s seguro de eliminar este mensaje?</p>
	<p>
		<a href="#" id="guestbook-delete-cancel" class="new-button"><b>Cancelar</b><i></i></a>
		<a href="#" id="guestbook-delete" class="new-button"><b>Eliminar</b><i></i></a>
	</p>
</form>
	</div>
</div>
<div id="group-tools" class="bottom-bubble">
	<div class="bottom-bubble-t"><div></div></div>
	<div class="bottom-bubble-c">
<h3>Editar grupo</h3>

<ul>
	<li><a href="group_profile.php?id=<?php echo $groupid; ?>&do=edit" id="group-tools-style">Editar p&aacute;gina</a></li>
	<?php if($ownerid == $my_id){ ?><li><a href="#" id="group-tools-settings">Opciones</a></li><?php } ?>
	<li><a href="#" id="group-tools-badge">Placa</a></li>
	<li><a href="#" id="group-tools-members">Miembros</a></li>
</ul>

	</div>
	<div class="bottom-bubble-b"><div></div></div>
</div>

<div class="cbb topdialog black" id="dialog-group-settings">

	<div class="box-tabs-container">
<ul class="box-tabs">
	<li class="selected" id="group-settings-link-group"><a href="#">Ajustes del grupo</a><span class="tab-spacer"></span></li>
	<li id="group-settings-link-forum"><a href="#">Ajustes del foro</a><span class="tab-spacer"></span></li>
	<li id="group-settings-link-room"><a href="#">Ajustes de sala</a><span class="tab-spacer"></span></li>
</ul>
</div>

	<a class="topdialog-exit" href="#" id="dialog-group-settings-exit">X</a>
	<div class="topdialog-body" id="dialog-group-settings-body">
<p style="text-align:center"><img src="./web-gallery/images/progress_bubbles.gif" alt="" width="29" height="6" /></p>
	</div>
</div>

<script language="JavaScript" type="text/javascript">
Event.observe("dialog-group-settings-exit", "click", function(e) {
    Event.stop(e);
    closeGroupSettings();
}, false);
</script><div class="cbb topdialog black" id="group-memberlist">

	<div class="box-tabs-container">
<ul class="box-tabs">
	<li class="selected" id="group-memberlist-link-members"><a href="#">Miembros</a><span class="tab-spacer"></span></li>
	<li id="group-memberlist-link-pending"><a href="#">Miembros pendientes</a><span class="tab-spacer"></span></li>
</ul>
</div>

	<a class="topdialog-exit" href="#" id="group-memberlist-exit">X</a>
	<div class="topdialog-body" id="group-memberlist-body">
<div id="group-memberlist-members-search" class="clearfix" style="display:none">

    <a id="group-memberlist-members-search-button" href="#" class="new-button"><b>Buscar</b><i></i></a>
    <input type="text" id="group-memberlist-members-search-string"/>
</div>
<div id="group-memberlist-members" style="clear: both"></div>
<div id="group-memberlist-members-buttons" class="clearfix">
	<a href="#" class="new-button group-memberlist-button-disabled" id="group-memberlist-button-give-rights"><b>Dar permisos</b><i></i></a>
	<a href="#" class="new-button group-memberlist-button-disabled" id="group-memberlist-button-revoke-rights"><b>Quitar permisos</b><i></i></a>
	<a href="#" class="new-button group-memberlist-button-disabled" id="group-memberlist-button-remove"><b>Eliminar</b><i></i></a>
	<a href="#" class="new-button group-memberlist-button" id="group-memberlist-button-close"><b>Cerrar</b><i></i></a>
</div>
<div id="group-memberlist-pending" style="clear: both"></div>
<div id="group-memberlist-pending-buttons" class="clearfix">
	<a href="#" class="new-button group-memberlist-button-disabled" id="group-memberlist-button-accept"><b>Aceptar</b><i></i></a>
	<a href="#" class="new-button group-memberlist-button-disabled" id="group-memberlist-button-decline"><b>Rechazar</b><i></i></a>
	<a href="#" class="new-button group-memberlist-button" id="group-memberlist-button-close2"><b>Cerrar</b><i></i></a>
</div>
	</div>
</div>
<?php } ?>

<script type="text/javascript">
HabboView.run();
</script>

<?php echo $analytics; ?>
</body>
</html>

<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
} else {
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
    <p class="error-text"><b>Buscar grupo</b></p>
    <?php if(isset($searchString)){ echo "<p class=\"error-text\">Lo sentimos, no se encontraron resultados para <strong>'".$searchString."'.</strong></p>"; } ?>
    <p class="error-text">
	<form method='post'>
		Nombre del grupo:<br />
		<input type='text' name='searchString' maxlength='25' size='25' value='<?php echo $_POST['searchString']; ?>'>
		<input type='submit' class='submit' value='Buscar'>
	</form>
    </p>
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
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>