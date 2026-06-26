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

include('../core.php');
include('../includes/session.php');

$groupid = $_POST['groupId'];

if(is_numeric($groupid) && $groupid > 0){

	$check = mysql_query("SELECT type FROM groups_details WHERE id = '".$groupid."' LIMIT 1") or die(mysql_error());
	$exists = mysql_num_rows($check);

	if($exists > 0){

		$check2 = mysql_query("SELECT groupid FROM groups_memberships WHERE userid = '".$my_id."' AND groupid = '".$groupid."' LIMIT 1") or die(mysql_errors());
		$already_member = mysql_num_rows($check2);

		$memberships = mysql_evaluate("SELECT COUNT(*) FROM groups_memberships WHERE userid = '".$my_id."'");
		if($memberships > 9){
			echo "<p>\nYa eres miembro o tienes solicitudes pendientes en 10 o m&aacute;s grupos.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" id=\"group-action-ok\"><b>OK</b><i></i></a>\n</p>\n\n\n<div class=\"clear\"></div>";
			exit;			
		}

		if($already_member > 0){

			echo "<p>\nYa eres miembro de este grupo o ya se ha realizado una solicitud para unirse.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" id=\"group-action-ok\"><b>OK</b><i></i></a>\n</p>\n\n\n<div class=\"clear\"></div>";
			exit;

		} else {

			$groupdata = mysql_fetch_assoc($check);
			$type = $groupdata['type'];
			$members = mysql_evaluate("SELECT COUNT(*) FROM groups_memberships WHERE groupid = '".$groupid."' AND is_pending = '0'");

			if($type == "0" || $type == "3"){ // we're free to join
				if($type == "0" && $members < 500 || $type == "3"){
					echo "<p>\nAhora eres miembro de este grupo.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" id=\"group-action-ok\"><b>OK</b><i></i></a>\n</p>\n\n\n<div class=\"clear\"></div>";
					mysql_query("INSERT INTO groups_memberships (userid,groupid,member_rank,is_current,is_pending) VALUES ('".$my_id."','".$groupid."','1','0','0')") or die(mysql_error());
					exit;
				} else {
					echo "<p>\nEste grupo est&aacute; lleno.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" id=\"group-action-ok\"><b>OK</b><i></i></a>\n</p>\n\n\n<div class=\"clear\"></div>";
					exit;
				}
			} elseif($type == "1"){ // we need to request join
				echo "<p>\nSe ha enviado una solicitud para unirse a este grupo. El propietario del grupo deber&aacute; aceptarte antes de que te unas.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" id=\"group-action-ok\"><b>OK</b><i></i></a>\n</p>\n\n\n<div class=\"clear\"></div>";
				mysql_query("INSERT INTO groups_memberships (userid,groupid,member_rank,is_current,is_pending) VALUES ('".$my_id."','".$groupid."','1','0','1')") or die(mysql_error());
				exit;
			} elseif($type == "2"){ // noone can join
				echo "<p>\nLo sentimos, pero este grupo est&aacute; cerrado. &iexcl;Nadie puede unirse!\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" id=\"group-action-ok\"><b>OK</b><i></i></a>\n</p>\n\n\n<div class=\"clear\"></div>";
				exit;
			}

		}

	} else {

		echo "1";
		exit;

	}

}

?>