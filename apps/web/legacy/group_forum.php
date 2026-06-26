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

if(HoloText(getContent('forum-enabled'), true) !== "1"){ header("Location: index.php"); exit; }

$pagename = "Discussion Board";
$pageid = "forum";
$body_id = "viewmode";

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

if($error != true){
include('templates/community/subheader.php');
include('templates/community/header.php');
mysql_query("UPDATE groups_details SET views = views+'1' WHERE id='".$groupid."' LIMIT 1");

$viewtools = "	<div class=\"myhabbo-view-tools\">\n";

$page = $_GET['page'];

$threads = mysql_evaluate("SELECT COUNT(*) FROM cms_forum_threads WHERE forumid='".$groupid."'");
$pages = ceil(($threads + 0) / 10);

if($page > $pages || $page < 1){
	$page = 1;
}

$key = 0;

mysql_query("UPDATE groups_details SET views = views+'1' WHERE id='".$groupid."' LIMIT 1");

?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="mypage-wrapper" class="cbb blue">
<div class="box-tabs-container box-tabs-left clearfix">
	<?php if($member_rank > 1 && !$edit_mode){ ?><a href="group_profile.php?id=<?php echo $groupid; ?>&do=edit" class="new-button dark-button edit-icon" style="float:left"><b><span></span>Edit</b><i></i></a><?php } ?>
    <h2 class="page-owner">
<?php echo HoloText($groupdata['name']); ?>&nbsp;
<?php if($groupdata['type'] == "2"){ ?><img src='./web-gallery/images/status_closed_big.gif' alt='Closed Group' title='Closed Group'><?php } ?>
<?php if($groupdata['type'] == "1"){ ?><img src='./web-gallery/images/status_exclusive_big.gif' alt='Moderated Group' title='Moderated Group'><?php } ?></h2>
</h2>
    <ul class="box-tabs">
        <li><a href="group_profile.php?id=<?php echo $groupid; ?>">P&aacute;gina principal</a><span class="tab-spacer"></span></li>
        <li class="selected"><a href="group_forum.php?id=<?php echo $groupid; ?>">Foro de discusi&oacute;n <?php if($groupdata['pane'] > 0) { ?><img src="http://images.habbohotel.nl/habboweb/23_deebb3529e0d9d4e847a31e5f6fb4c5b/9/web-gallery/images/grouptabs/privatekey.png"><?php } ?></a><span class="tab-spacer"></span></li>
<?php $viewtools = "	<div class=\"myhabbo-view-tools\">\n";

if($logged_in && !$is_member && $groupdata['type'] !== "2" && $my_membership['is_pending'] !== "1"){ $viewtools = $viewtools . "<a href=\"joingroup.php?groupId=".$groupid."\" id=\"join-group-button\">"; if($groupdata['type'] == "0" || $groupdata['type'] == "3"){ $viewtools = $viewtools . "Unirse"; } else { $viewtools = $viewtools . "Solicitar membres&iacute;a"; } $viewtools = $viewtools . "</a>"; }
if($logged_in && $my_membership['is_current'] !== "1" && $is_member){ $viewtools = $viewtools . "<a href=\"#\" id=\"select-favorite-button\">Marcar como favorito</a>\n"; }
if($logged_in && $my_membership['is_current'] == "1" && $is_member){ $viewtools = $viewtools . "<a href=\"#\" id=\"deselect-favorite-button\">Quitar de favoritos</a>"; }
if($logged_in && $is_member && $my_id !== $ownerid){ $viewtools = $viewtools . "<a href=\"leavegroup.php?groupId=".$groupid."\" id=\"leave-group-button\">Abandonar el grupo</a>\n"; }

$viewtools = $viewtools . "	</div>\n"; ?>
    </ul>
</div>	
	<div id="mypage-content">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="content-1col">
            <tr>
                <td valign="top" style="width: 750px;" class="habboPage-col rightmost">
                    <div id="discussionbox">
					<?php
					if($groupdata['pane'] > 0) {
					$sql = mysql_query("SELECT * FROM groups_memberships WHERE userid = '".$my_id."' AND is_pending <> '1' AND groupid='".$_GET['id']."'");
					$member = mysql_fetch_assoc($sql);
					if(mysql_num_rows($sql) > 0) { ?>
<div id="group-topiclist-container">
<div class="topiclist-header clearfix">
		
		<?php
		$sql = mysql_query("SELECT * FROM groups_details WHERE id='".$_GET['id']."' LIMIT 1");
		$row = mysql_fetch_assoc($sql);

		if($row['topics'] == 0) { ?>
		
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a><?php } else { echo "Debes estar conectado para responder o publicar nuevos temas."; }
		}elseif($row['topics'] == 1) {
		$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND is_pending <> '1' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a><?php } else { echo "Debes estar conectado para responder o publicar nuevos temas."; }
		}
	}elseif($row['topics'] == 2) {
	$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND member_rank='2' AND is_pending <> '1' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a><?php } else { echo "Debes estar conectado para responder o publicar nuevos temas."; }
	}
}
?>
		
    <div class="page-num-list">
    Ver p&aacute;gina:
<?php
	for ($i = 1; $i <= $pages; $i++){
		if($page == $i){
			echo $i . "\n";
		} else {
			echo "<a href=\"forum.php?page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
		}
	} 
?>
    </div>
</div>
<table class="group-topiclist" border="0" cellpadding="0" cellspacing="0" id="group-topiclist-list">
	<tr class="topiclist-columncaption">
		<td class="topiclist-columncaption-topic">Tema</td>
		<td class="topiclist-columncaption-lastpost">&Uacute;ltimo mensaje</td>
		<td class="topiclist-columncaption-replies">Respuestas</td>
		<td class="topiclist-columncaption-views">Vistas</td>
	</tr>
	
<?php

if($threads == 0){
echo "	<tr class=\"topiclist-row-1\">
		<td class=\"topiclist-rowtopic\" valign=\"top\">
			No hay temas que mostrar.
		</td>
		</tr>";
}

$sql = mysql_query("SELECT * FROM cms_forum_threads WHERE type > 2 AND forumid='".$groupid."' ORDER BY unix DESC") or die(mysql_error());
$stickies = mysql_num_rows($sql);

$query_min = ($page * 10) - 10;
$query_max = 10;

$query_max = $query_max - $stickies;
$query_min = $query_min - $stickies;

if($query_min < 0){ // Page 1
$query_min = 0;
}

while($row = mysql_fetch_assoc($sql)){

	$key++;

	if(IsEven($key)){
		$x = "odd";
	} else {
		$x = "even";
	}

	echo "<tr class=\"topiclist-row-" . $x . "\">
		<td class=\"topiclist-rowtopic\" valign=\"top\">
			<div class=\"topiclist-row-content\">
			<a class=\"topiclist-link icon icon-sticky\" href=\"viewthread.php?thread=".$row['id']."\">".HoloText($row['title'])."</a>";

			if($row['type'] == 4){
			echo "&nbsp;<span class=\"topiclist-row-topicsticky\"><img src=\"./web-gallery/images/groups/status_closed.gif\" title=\"Closed Thread\" alt=\"Closed Thread\"></span>";
			}

			echo "&nbsp;(p&aacute;g. ";

			$thread_pages = ceil(($row['posts'] + 1) / 10);

			for ($i = 1; $i <= $thread_pages; $i++){
				echo "<a href=\"viewthread.php?thread=" . $row['id'] . "&page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
			} 

            echo ")
			<br />
			<span><a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['author'] . "\">" . $row['author'] . "</a></span>";

			$date_bits = explode(" ", $row['date']);
			$date = $date_bits[0];
			$time = $date_bits[1];
			
				echo "&nbsp;<span class=\"latestpost\">" . $date . "</span>
			<span class=\"latestpost\">(" . $time . ")</span>
			</div>
		</td>
		<td class=\"topiclist-lastpost\" valign=\"top\">
		    <a class=\"lastpost-page-link\" href=\"viewthread.php?thread=" . $row['id'] . "&sp=JumpToLast\">";

			$date_bits = explode(" ", $row['lastpost_date']);
			$date = $date_bits[0];
			$time = $date_bits[1];

				echo "<span class=\"lastpost\">" . $date . "</span>
            <span class=\"lastpost\">(" . $time . ")</span></a><br />
			<span class=\"topiclist-row-writtenby\">por:</span> <a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['lastpost_author'] . "\">" . $row['lastpost_author'] . "</a>&nbsp;
		</td>
 		<td class=\"topiclist-replies\" valign=\"top\">" . $row['posts'] . "</td>
 		<td class=\"topiclist-views\" valign=\"top\">" . $row['views'] . "</td>
	</tr>";

}

$sql = mysql_query("SELECT * FROM cms_forum_threads WHERE type < 3 AND forumid='".$groupid."' ORDER BY unix DESC LIMIT ".$query_min.", ".$query_max."") or die(mysql_error());

while($row = mysql_fetch_assoc($sql)){

	$key++;

	if(IsEven($key)){
		$x = "odd";
	} else {
		$x = "even";
	}

	echo "<tr class=\"topiclist-row-" . $x . "\">
		<td class=\"topiclist-rowtopic\" valign=\"top\">
			<div class=\"topiclist-row-content\">
			<a class=\"topiclist-link \" href=\"viewthread.php?thread=".$row['id']."\">".HoloText($row['title'])."</a>";

			if($row['type'] == 2){
			echo "&nbsp;<span class=\"topiclist-row-topicsticky\"><img src=\"./web-gallery/images/groups/status_closed.gif\" title=\"Closed Thread\" alt=\"Closed Thread\"></span>";
			}

			echo "&nbsp;(p&aacute;g. ";

			$thread_pages = ceil(($row['posts'] + 1) / 10);

			for ($i = 1; $i <= $thread_pages; $i++){
				echo "<a href=\"viewthread.php?thread=" . $row['id'] . "&page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
			} 

            echo ")
			<br />
			<span><a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['author'] . "\">" . $row['author'] . "</a></span>";

			$date_bits = explode(" ", $row['date']);
			$date = $date_bits[0];
			$time = $date_bits[1];
			
				echo "&nbsp;<span class=\"latestpost\">" . $date . "</span>
			<span class=\"latestpost\">(" . $time . ")</span>
			</div>
		</td>
		<td class=\"topiclist-lastpost\" valign=\"top\">
		    <a class=\"lastpost-page-link\" href=\"viewthread.php?thread=" . $row['id'] . "&sp=JumpToLast\">";

			$date_bits = explode(" ", $row['lastpost_date']);
			$date = $date_bits[0];
			$time = $date_bits[1];

				echo "<span class=\"lastpost\">" . $date . "</span>
            <span class=\"lastpost\">(" . $time . ")</span></a><br />
			<span class=\"topiclist-row-writtenby\">por:</span> <a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['lastpost_author'] . "\">" . $row['lastpost_author'] . "</a>&nbsp;
		</td>
 		<td class=\"topiclist-replies\" valign=\"top\">" . $row['posts'] . "</td>
 		<td class=\"topiclist-views\" valign=\"top\">" . $row['views'] . "</td>
	</tr>";

}

?>	

	</table>
<div class="topiclist-footer clearfix">
		<?php
		$sql = mysql_query("SELECT * FROM groups_details WHERE id='".$_GET['id']."' LIMIT 1");
		$row = mysql_fetch_assoc($sql);

		if($row['topics'] == 0) { ?>
		
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a><?php } else { echo "Debes estar conectado para responder o publicar nuevos temas."; }
		}elseif($row['topics'] == 1) {
		$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a><?php } else { echo "Debes estar conectado para responder o publicar nuevos temas."; }
		}
	}elseif($row['topics'] == 2) {
	$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND member_rank='2' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a><?php } else { echo "Debes estar conectado para responder o publicar nuevos temas."; }
	}
}
?>
    <div class="page-num-list">
    Ver p&aacute;gina:
<?php
	for ($i = 1; $i <= $pages; $i++){
		if($page == $i){
			echo $i . "\n";
		} else {
			echo "<a href=\"forum.php?page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
		}
	}
?>
    </div>
<?php }else{ ?>
<h1>Vaya...</h1>

<p>
No puedes acceder a este foro, &iexcl;necesitas ser miembro!<br />

</p>
<?php }
	}else{ ?>
<div id="group-topiclist-container">
<div class="topiclist-header clearfix">
        <input type="hidden" id="email-verfication-ok" value="1"/>
		<?php
		$sql = mysql_query("SELECT * FROM groups_details WHERE id='".$_GET['id']."' LIMIT 1");
		$row = mysql_fetch_assoc($sql);

		if($row['topics'] == 0) { ?>
		
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a><?php } else { echo "Debes estar conectado para responder o publicar nuevos temas."; }
		}elseif($row['topics'] == 1) {
		$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a><?php } else { echo "Debes estar conectado para responder o publicar nuevos temas."; }
		}
	}elseif($row['topics'] == 2) {
	$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND member_rank='2' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a><?php } else { echo "Debes estar conectado para responder o publicar nuevos temas."; }
	}
}
?>
    <div class="page-num-list">
    Ver p&aacute;gina:
<?php
	for ($i = 1; $i <= $pages; $i++){
		if($page == $i){
			echo $i . "\n";
		} else {
			echo "<a href=\"forum.php?page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
		}
	} 
?>
    </div>
</div>
<table class="group-topiclist" border="0" cellpadding="0" cellspacing="0" id="group-topiclist-list">
	<tr class="topiclist-columncaption">
		<td class="topiclist-columncaption-topic">Tema</td>
		<td class="topiclist-columncaption-lastpost">&Uacute;ltimo mensaje</td>
		<td class="topiclist-columncaption-replies">Respuestas</td>
		<td class="topiclist-columncaption-views">Vistas</td>
	</tr>
	
<?php

if($threads == 0){
echo "	<tr class=\"topiclist-row-1\">
		<td class=\"topiclist-rowtopic\" valign=\"top\">
			No hay temas que mostrar.
		</td>
		</tr>";
}

$sql = mysql_query("SELECT * FROM cms_forum_threads WHERE type > 2 AND forumid='".$groupid."' ORDER BY unix DESC") or die(mysql_error());
$stickies = mysql_num_rows($sql);

$query_min = ($page * 10) - 10;
$query_max = 10;

$query_max = $query_max - $stickies;
$query_min = $query_min - $stickies;

if($query_min < 0){ // Page 1
$query_min = 0;
}

while($row = mysql_fetch_assoc($sql)){

	$key++;

	if(IsEven($key)){
		$x = "odd";
	} else {
		$x = "even";
	}

	echo "<tr class=\"topiclist-row-" . $x . "\">
		<td class=\"topiclist-rowtopic\" valign=\"top\">
			<div class=\"topiclist-row-content\">
			<a class=\"topiclist-link icon icon-sticky\" href=\"viewthread.php?thread=".$row['id']."\">".HoloText($row['title'])."</a>";

			if($row['type'] == 4){
			echo "&nbsp;<span class=\"topiclist-row-topicsticky\"><img src=\"./web-gallery/images/groups/status_closed.gif\" title=\"Closed Thread\" alt=\"Closed Thread\"></span>";
			}

			echo "&nbsp;(p&aacute;g. ";

			$thread_pages = ceil(($row['posts'] + 1) / 10);

			for ($i = 1; $i <= $thread_pages; $i++){
				echo "<a href=\"viewthread.php?thread=" . $row['id'] . "&page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
			} 

            echo ")
			<br />
			<span><a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['author'] . "\">" . $row['author'] . "</a></span>";

			$date_bits = explode(" ", $row['date']);
			$date = $date_bits[0];
			$time = $date_bits[1];
			
				echo "&nbsp;<span class=\"latestpost\">" . $date . "</span>
			<span class=\"latestpost\">(" . $time . ")</span>
			</div>
		</td>
		<td class=\"topiclist-lastpost\" valign=\"top\">
		    <a class=\"lastpost-page-link\" href=\"viewthread.php?thread=" . $row['id'] . "&sp=JumpToLast\">";

			$date_bits = explode(" ", $row['lastpost_date']);
			$date = $date_bits[0];
			$time = $date_bits[1];

				echo "<span class=\"lastpost\">" . $date . "</span>
            <span class=\"lastpost\">(" . $time . ")</span></a><br />
			<span class=\"topiclist-row-writtenby\">por:</span> <a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['lastpost_author'] . "\">" . $row['lastpost_author'] . "</a>&nbsp;
		</td>
 		<td class=\"topiclist-replies\" valign=\"top\">" . $row['posts'] . "</td>
 		<td class=\"topiclist-views\" valign=\"top\">" . $row['views'] . "</td>
	</tr>";

}

$sql = mysql_query("SELECT * FROM cms_forum_threads WHERE type < 3 AND forumid='".$groupid."' ORDER BY unix DESC LIMIT ".$query_min.", ".$query_max."") or die(mysql_error());

while($row = mysql_fetch_assoc($sql)){

	$key++;

	if(IsEven($key)){
		$x = "odd";
	} else {
		$x = "even";
	}

	echo "<tr class=\"topiclist-row-" . $x . "\">
		<td class=\"topiclist-rowtopic\" valign=\"top\">
			<div class=\"topiclist-row-content\">
			<a class=\"topiclist-link \" href=\"viewthread.php?thread=".$row['id']."\">".HoloText($row['title'])."</a>";

			if($row['type'] == 2){
			echo "&nbsp;<span class=\"topiclist-row-topicsticky\"><img src=\"./web-gallery/images/groups/status_closed.gif\" title=\"Closed Thread\" alt=\"Closed Thread\"></span>";
			}

			echo "&nbsp;(p&aacute;g. ";

			$thread_pages = ceil(($row['posts'] + 1) / 10);

			for ($i = 1; $i <= $thread_pages; $i++){
				echo "<a href=\"viewthread.php?thread=" . $row['id'] . "&page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
			} 

            echo ")
			<br />
			<span><a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['author'] . "\">" . $row['author'] . "</a></span>";

			$date_bits = explode(" ", $row['date']);
			$date = $date_bits[0];
			$time = $date_bits[1];
			
				echo "&nbsp;<span class=\"latestpost\">" . $date . "</span>
			<span class=\"latestpost\">(" . $time . ")</span>
			</div>
		</td>
		<td class=\"topiclist-lastpost\" valign=\"top\">
		    <a class=\"lastpost-page-link\" href=\"viewthread.php?thread=" . $row['id'] . "&sp=JumpToLast\">";

			$date_bits = explode(" ", $row['lastpost_date']);
			$date = $date_bits[0];
			$time = $date_bits[1];

				echo "<span class=\"lastpost\">" . $date . "</span>
            <span class=\"lastpost\">(" . $time . ")</span></a><br />
			<span class=\"topiclist-row-writtenby\">por:</span> <a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['lastpost_author'] . "\">" . $row['lastpost_author'] . "</a>&nbsp;
		</td>
 		<td class=\"topiclist-replies\" valign=\"top\">" . $row['posts'] . "</td>
 		<td class=\"topiclist-views\" valign=\"top\">" . $row['views'] . "</td>
	</tr>";

}

?>	

	</table>
<div class="topiclist-footer clearfix">
		<?php
		$sql = mysql_query("SELECT * FROM groups_details WHERE id='".$_GET['id']."' LIMIT 1");
		$row = mysql_fetch_assoc($sql);

		if($row['topics'] == 0) { ?>
		
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a><?php } else { echo "Debes estar conectado para responder o publicar nuevos temas."; }
		}elseif($row['topics'] == 1) {
		$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND is_pending <> '1' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a><?php } else { echo "Debes estar conectado para responder o publicar nuevos temas."; }
		}
	}elseif($row['topics'] == 2) {
	$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND member_rank='2' AND is_pending <> '1' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a><?php } else { echo "Debes estar conectado para responder o publicar nuevos temas."; }
	}
}
?>
    <div class="page-num-list">
    Ver p&aacute;gina:
<?php
	for ($i = 1; $i <= $pages; $i++){
		if($page == $i){
			echo $i . "\n";
		} else {
			echo "<a href=\"forum.php?page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
		}
	}
?>
    </div>
<?php }
?>
</div>
</div>

<script type="text/javascript" language="JavaScript">
L10N.put("myhabbo.discussion.error.topic_name_empty", "El t&iacute;tulo del tema no puede estar vac&iacute;o");
Discussions.initialize("<?php echo $_GET['id']; ?>", "forum.php", null);
</script>
                    </div>
					
                </td>
                <td style="width: 4px;"></td>
                <td valign="top" style="width: 164px;">
    <div class="habblet ">
    
    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<script type="text/javascript">
	Event.observe(window, "load", observeAnim);
	document.observe("dom:loaded", initDraggableDialogs);
</script>
    </div>
<div id="footer">
	<p><a href="index.php" target="_self">Inicio</a> | <a href="./disclaimer.php" target="_self">Condiciones de uso</a> | <a href="./privacy.php" target="_self">Pol&iacute;tica de privacidad</a></p>
	<?php /*@@* DO NOT EDIT OR REMOVE THE LINE BELOW WHATSOEVER! *@@*/ ?>
	<p>HoloCMS modificado por Bubble & Knock<br /><a href="http://nakedcms.idoo.com/group.php">NakedCMS</a>  es un CMS traducido y modificado por Bubble y Knock. Por favor, respeta su trabajo y no copies ni elimines este copyright.<br />NakedCMS by NakedGroup 2008/2009</p>

</div></div>

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
<p style="text-align:center"><img src="http://images.habbohotel.nl/habboweb/21_5527e6590eba8f3fb66348bdf271b5a2/17/web-gallery/images/progress_bubbles.gif" alt="" width="29" height="6" /></p>
	</div>
</div>	

<script language="JavaScript" type="text/javascript">
Event.observe("dialog-group-settings-exit", "click", function(e) {
    Event.stop(e);
    closeGroupSettings();
}, false);
</script><div class="cbb topdialog" id="postentry-verifyemail-dialog">
	<h2 class="title dialog-handle">Confirmar e-mail</h2>
	
	<a class="topdialog-exit" href="#" id="postentry-verifyemail-dialog-exit">X</a>
	<div class="topdialog-body" id="postentry-verifyemail-dialog-body">
	<p>Debes confirmar tu e-mail antes de publicar.</p>
	<p><a href="/profile?tab=3">Activa tu e-mail</a></p>
	<p class="clearfix">
		<a href="#" id="postentry-verifyemail-ok" class="new-button"><b>OK</b><i></i></a>
	</p>
	</div>
</div>	
					
<script type="text/javascript">
HabboView.run();
</script>

</body>
</html>
<?php
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