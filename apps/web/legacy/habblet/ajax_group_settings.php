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

// simple check to avoid most direct access
$refer = $_SERVER['HTTP_REFERER'];
$pos = strrpos($refer, "group_profile.php");
if ($pos === false) { exit; }

$groupid = $_POST['groupId'];
if(!is_numeric($groupid)){ exit; }

$check = mysql_query("SELECT member_rank FROM groups_memberships WHERE userid = '".$my_id."' AND groupid = '".$groupid."' AND member_rank > 1 AND is_pending = '0' LIMIT 1") or die(mysql_error());
$is_member = mysql_num_rows($check);

if($is_member > 0){
	$my_membership = mysql_fetch_assoc($check);
	$member_rank = $my_membership['member_rank'];
} else {
	exit;
}

$check = mysql_query("SELECT * FROM groups_details WHERE id = '".$groupid."' LIMIT 1") or die(mysql_error());
$valid = mysql_num_rows($check);

if($valid > 0){
	$groupdata = mysql_fetch_assoc($check);
	$ownerid = $groupdata['ownerid'];
} else {
	exit;
}

if($ownerid !== $my_id){ exit; }
?>
<form action="#" method="post" id="group-settings-form">

<div id="group-settings">
<div id="group-settings-data" class="group-settings-pane">
    <div id="group-logo">
        <img src="./habbo-imaging/badge-fill/<?php echo $groupdata['badge']; ?>.gif" />
	</div>
	<div id="group-identity-area">
        <div id="group-name-area">
            <div id="group_name_message_error" class="error"></div>
            <label for="group_name" id="group_name_text">Editar nombre del grupo:</label>
            <input type="text" name="group_name" id="group_name" onKeyUp="GroupUtils.validateGroupElements('group_name', 25, 'Has alcanzado el l&iacute;mite de longitud del nombre del grupo');" value="<?php echo HoloText($groupdata['name']); ?>"/><br />
        </div>

        <div id="group-url-area">
            <div id="group_url_message_error" class="error"></div>
                <label for="group_url" id="group_url_text"></label><br/>
                <?php /* <span id="group_url_text"><a href="group_profile.php?id=<?php echo $groupid; ?>ddsa">group_profile.php?id=<?php echo $groupid; ?></a></span><br/> */ ?>
                <input type="hidden" name="group_url" id="group_url" value="system"/>
                <input type="hidden" name="group_url_edited" id="group_url_edited" value="0"/>
        </div>
    </div>
    

	<div id="group-description-area">
	    <div id="group_description_message_error" class="error"></div>
	    <label for="group_description" id="description_text">Editar texto:</label>
	    <span id="description_chars_left">
	        <label for="characters_left">Caracteres restantes:</label>
	        <input id="group_description-counter" type="text" value="<?php echo 255 - strlen(HoloText($groupdata['description'])); ?>" size="3" readonly="readonly" class="amount" />
	    </span>
	    <textarea name="group_description" id="group_description" onKeyUp="GroupUtils.validateGroupElements('group_description', 255, 'Has alcanzado el l&iacute;mite de la descripci&oacute;n');"><?php echo HoloText($groupdata['description']); ?></textarea>
	</div>

</div>

<div id="group-settings-type" class="group-settings-pane group-settings-selection">
    <label for="group_type">Editar tipo de grupo:</label>
        <input type="radio" name="group_type" id="group_type" value="0" <?php if($groupdata['type'] == "0"){ echo "checked=\"checked\""; } elseif($groupdata['type'] == "3"){ echo "disabled=\"disabled\""; } ?> />
        <div class="description">
            <div class="group-type-normal">Normal</div>
            <p>Cualquiera puede unirse. L&iacute;mite de 500 miembros.</p>
        </div>
        <input type="radio" name="group_type" id="group_type" value="1" <?php if($groupdata['type'] == "1"){ echo "checked=\"checked\""; } elseif($groupdata['type'] == "3"){ echo "disabled=\"disabled\""; } ?> />
        <div class="description">
            <div class="group-type-exclusive">Exclusivo</div>
            <p>El administrador del grupo controla qui&eacute;n puede unirse.</p>
        </div>
        <input type="radio" name="group_type" id="group_type" value="2" <?php if($groupdata['type'] == "2"){ echo "checked=\"checked\""; } elseif($groupdata['type'] == "3"){ echo "disabled=\"disabled\""; } ?> />
        <div class="description">
            <div class="group-type-private">Privado</div>
            <p>Nadie puede unirse.</p>
        </div>
        <input type="radio" name="group_type" id="group_type" value="3" <?php if($groupdata['type'] == "3"){ echo "checked=\"checked\" disabled=\"disabled\""; } ?> />
        <div class="description">
            <div class="group-type-large">Ilimitado</div>
            <p>Cualquiera puede unirse. Sin l&iacute;mite de miembros. No se puede ver la lista de miembros.</p>
                <p class="description-note">Nota: si eliges la opci&oacute;n 'ilimitado' no podr&aacute;s cambiar el tipo m&aacute;s adelante!</p>
        </div>

    <input type="hidden" id="initial_group_type" value="<?php echo $groupdata['type']; ?>">
</div>
</div>


<div id="forum-settings" style="display: none;">
<div id="forum-settings-type" class="group-settings-pane group-settings-selection">
    <label for="forum_type">Editar tipo de foro:</label>
        <input type="radio" name="forum_type" id="forum_type" value="0" <?php if($groupdata['pane'] == 0) { echo 'checked="checked"'; } ?> />
        <div class="description">
            P&uacute;blico<br />
            <p>Cualquiera puede leer los mensajes del foro.</p>
        </div>
        <input type="radio" name="forum_type" id="forum_type" value="1" <?php if($groupdata['pane'] == 1) { echo 'checked="checked"'; } ?> />
        <div class="description">
            Privado<br />
            <p>Solo los miembros del grupo pueden leer los mensajes del foro.</p>
        </div>
</div>

<div id="forum-settings-topics" class="group-settings-pane group-settings-selection">
    <label for="new_topic_permission">Editar temas nuevos:</label>
        <input type="radio" name="new_topic_permission" id="new_topic_permission" value="2" <?php if($groupdata['topics'] == 2) { echo 'checked="checked"'; } ?> />
        <div class="description">
            Administradores<br />
            <p>Solo los administradores pueden crear temas nuevos.</p>
        </div>
        <input type="radio" name="new_topic_permission" id="new_topic_permission" value="1" <?php if($groupdata['topics'] == 1) { echo 'checked="checked"'; } ?> />
        <div class="description">
            Miembros<br />
            <p>Solo los miembros del grupo pueden crear temas nuevos.</p>
        </div>
        <input type="radio" name="new_topic_permission" id="new_topic_permission" value="0" <?php if($groupdata['topics'] == 0) { echo 'checked="checked"'; } ?> />
        <div class="description">
            Todos<br />
            <p>Cualquiera puede crear un tema nuevo.</p>
        </div>
</div>
</div>


<div id="room-settings" style="display: none;">
<?php $sql = mysql_query("SELECT id,name,description FROM rooms WHERE owner='".$rawname."'");
$row = mysql_fetch_assoc($sql);
$groupdetails = mysql_query("SELECT * FROM groups_details WHERE roomid='".$row['id']."' AND id='".$groupid."' LIMIT 1");
$group = mysql_fetch_assoc($groupdetails); ?>
<label>Elige una sala para tu grupo:</label>

<div id="room-settings-id" class="group-settings-pane-wide group-settings-selection">
    <ul><li><input type="radio" name="roomId" value=" " <?php if($group['roomid'] == 0 OR $group['roomid'] == "" OR $group['roomid'] == " ") { echo "CHECKED"; } ?> /><div>Sin sala</div></li>
	<?php while($row = mysql_fetch_assoc($sql)) {
		        $i++;

        if(IsEven($i)){
            $even = "even";
        } else {
            $even = "odd";
        }
		?>
    	<li class="<?php echo $even; ?>">
    		<input type="radio" name="roomId" value="<?php echo $row['id']; ?>" <?php if($group['roomid'] == $row['id']) { echo "CHECKED"; } ?> /><a href="client.php?forwardId=2&roomId=<?php echo $row['id']; ?>" onclick="HabboClient.roomForward(this, '<?php echo $row['id']; ?>', 'private'); return false;" target="client" class="room-enter">&iexcl;Ir a la sala!</a><div>
				<?php echo HoloText($row['name']); if($row['name'] == ""){ ?>&nbsp;<?php } ?><br />
				<span class="room-description"><?php echo HoloText($row['description']); if($row['description'] == "") { ?>&nbsp;<?php } ?></span>
			</div>
    	</li>
		<?php } ?>
    </ul>
</div>

</div>

<div id="group-button-area">
     <a href="#" id="group-settings-update-button" class="new-button"
     		onclick="showGroupSettingsConfirmation('<?php echo $groupid; ?>');">
        <b>Guardar cambios</b><i></i>
    </a>
    <a id="group-delete-button" href="#" class="new-button red-button" onclick="openGroupActionDialog('groups_confirm_delete_group.php', 'groups_delete_group.php', null , '<?php echo $groupid; ?>', null);">
        <b>Eliminar grupo</b><i></i>
    </a>
    <a href="#" id="group-settings-close-button" class="new-button" onclick="closeGroupSettings(); return false;"><b>Cancelar</b><i></i></a>
</div>
</form>

<div class="clear"></div>

<script type="text/javascript" language="JavaScript">
    L10N.put("group.settings.title.text", "Editar ajustes del grupo");
    L10N.put("group.settings.group_type_change_warning.normal", "&iquest;Seguro que quieres cambiar el tipo de grupo a <strong\>normal</strong\>?");
    L10N.put("group.settings.group_type_change_warning.exclusive", "&iquest;Seguro que quieres cambiar el tipo de grupo a <strong \>exclusivo</strong\>?");
    L10N.put("group.settings.group_type_change_warning.closed", "&iquest;Seguro que quieres cambiar el tipo de grupo a <strong\>privado</strong\>?");
    L10N.put("group.settings.group_type_change_warning.large", "&iquest;Seguro que quieres cambiar el tipo de grupo a <strong\>ilimitado</strong\>? Si contin&uacute;as, no podr&aacute;s volver a cambiarlo m&aacute;s adelante!");
    L10N.put("myhabbo.groups.confirmation_ok", "OK");
    L10N.put("myhabbo.groups.confirmation_cancel", "Cancelar");
    switchGroupSettingsTab(null, "group");
</script>