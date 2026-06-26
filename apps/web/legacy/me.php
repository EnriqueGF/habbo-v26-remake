<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright &copy; 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind.
+---------------------------------------------------*/

include('core.php');
include('includes/session.php');
include('includes/news_headlines.php');

if($_GET['do'] == "RemoveFeedItem" && is_numeric($_GET['key'])){ // ex. me.php?do=RemoveFeedItem&key=5
    mysql_query("DELETE FROM cms_alerts WHERE userid = '".$my_id."' AND id = '".$_GET['key']."' ORDER BY id ASC LIMIT 1") or die(mysql_error());
}

$pagename = $name;
$pageid = "1";

// Header for minimail
$messages = mysql_query("SELECT COUNT(*) FROM cms_minimail WHERE to_id = '".$my_id."'") or $messages = 0;
header("X-JSON: {\"totalMessages\":".$messages."}");

include('templates/community/subheader.php');
include('templates/community/header.php');

// Query tags
$fetch_tags = mysql_query("SELECT tag,id FROM cms_tags WHERE ownerid = '".$my_id."' LIMIT 20") or die(mysql_error());
$tags_num = mysql_num_rows($fetch_tags);

	// Create the random tag questions array
$randomq[] = "&iquest;Cu&aacute;l es tu programa favorito?";
$randomq[] = "&iquest;Cu&aacute;l es tu actor favorito?";
$randomq[] = "&iquest;Cu&aacute;l es tu nombre de usuario?";
$randomq[] = "&iquest;Cu&aacute;l es tu m&uacute;sica favorita?";
$randomq[] = "&iexcl;Descr&iacute;bete!";
$randomq[] = "&iquest;Cu&aacute;l es tu staff favorito?";

// Select a random question from the array above
srand ((double) microtime() * 1000000);
$chosen = rand(0,count($randomq)-1);

// Appoint the variable
$tag_question = $randomq[$chosen];

?>

<div id="container">
	<div id="content">
        <div id="column1" class="column">
            <div class="habblet-container ">

              <div id="new-personal-info" style="background-image:url(./web-gallery/v2/images/personal_info/hotel_views/htlview_br.png)" />
              <div id="enter-hotel">
                <?php if($online == "online"){ ?>
                  <div class="open">
                      <?php
                      if(HoloText(getContent('client-widescreen'), true) == "1"){
                       $wide_enabled = true;
                   } else {
                       $wide_enabled = false;
                   }
                   ?>
                   <a href="client.php<?php if($wide_enabled == false){ echo "?wide=false"; } ?>" target="client" onclick="openOrFocusHabbo(this); return false;">Entrar <?php echo $shortname; ?><i></i></a>
                   <b></b>
               </div>
           <?php } else { ?>
            <div class="closed">
               <span>Hotel cerrado</span>
               <b></b>
           </div>
       <?php } ?>
   </div>

   <div id="habbo-plate">
      <a href="account.php?tab=1">
         <img alt="<?php echo $name; ?>" src="/habbo-imaging/avatarimage?figure=<?php echo $myrow['figure']; ?>&size=b&direction=4&head_direction=3&gesture=sml" width="64" height="110" />
     </a>
 </div>

 <div id="habbo-info">
  <div id="motto-container" class="clearfix"><strong><?php echo $name; ?>:</strong><div><span title="&iquest;C&oacute;mo est&aacute;s hoy?"><?php if(!empty($myrow['mission'])){ echo stripslashes($myrow['mission']); } else { echo "&iquest;C&oacute;mo est&aacute;s hoy?"; } ?></span><p style="display: none"><input type="text" length="30" name="motto" value="<?php echo stripslashes($myrow['mission']); ?>"/></p></div></div><div id="motto-links" style="display: none"><a href="#" id="motto-cancel">Cancelar</a></div></div>
  <ul id="link-bar" class="clearfix">
      <li class="change-looks"><a href="account.php?tab=1">Cambia tu look &raquo;</a></li>
      <li class="credits">
         <a href="credits.php"><?php echo $myrow['credits']; ?></a> cr&eacute;ditos
     </li>
     <li class="club">

       <a href="club.php"><?php if( !IsHCMember($my_id) ){ echo "Un&eacute;te al " . $shortname . " Club &raquo;</a>"; } else { echo HCDaysLeft($my_id) . " </a>d&iacute;as HC"; }?>
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       <img src="./web-gallery/v2/images/pixellogo.png"><a href="credits.php"><?php echo $myrow['pixels']; ?></a> P&iacute;xeles
   </li>
</ul>

<div id="habbo-feed">
    <ul id="feed-items">

        <?php
        $sqluser = mysql_query("SELECT hc_before FROM users WHERE id='".$my_id."' LIMIT 1");
        $user = mysql_query($sqluser);

        $sql = mysql_query("SELECT * FROM users_club WHERE userid='".$my_id."' LIMIT 1");
        if($user['hc_before'] > 0 && mysql_num_rows($sql) == 0) { ?>
            <li id="feed-item-hc-reminder">
                <a href="#" class="remove-feed-item" id="remove-hc-reminder" title="Eliminar notificaci&oacute;n">Eliminar notificaci&oacute;n</a>
                <div>
                    <?php if(mysql_num_rows($sql) == 0) { ?>    Tu Club <?php echo $shortname; ?> ha expirado. &iquest;Quieres renovar tu <?php echo $shortname; ?> Club?<?php } ?>
                </div>
                <div id="hc-reminder-buttons" class="clearfix">
                    <a href="#" class="new-button" id="hc-reminder-1" title="31 d&iacute;as, 20 Cr&eacute;ditos"><b>1 mes</b><i></i></a>
                    <a href="#" class="new-button" id="hc-reminder-2" title="93 d&iacute;as, 50 Cr&eacute;ditos"><b>3 meses</b><i></i></a>
                    <a href="#" class="new-button" id="hc-reminder-3" title="186 d&iacute;as, 80 Cr&eacute;ditos"><b>6 meses</b><i></i></a>
                </div>
            </li>
            <script type="text/javascript">
                L10N.put("subscription.title", "HABBO CLUB");
            </script>

            <?php
        }

        if(IsHCMember($my_id)){
            if($user['hc_before'] > 0) {
                if(HCDaysLeft($my_id) < 6) { ?>
                    <li id="feed-item-hc-reminder">
                        <a href="#" class="remove-feed-item" id="remove-hc-reminder" title="Eliminar notificaci&oacute;n">Eliminar notificaci&oacute;n</a>
                        <div>
                            Tu <?php echo $shortname; ?> Club expira en <?php echo HCDaysLeft($my_id); ?> d&iacute;as. &iquest;Quieres renovar tu <?php echo $shortname; ?> Club?
                        </div>
                        <div id="hc-reminder-buttons" class="clearfix">
                            <a href="#" class="new-button" id="hc-reminder-1" title="31 d&iacute;as, 20 Cr&eacute;ditos"><b>1 mes</b><i></i></a>
                            <a href="#" class="new-button" id="hc-reminder-2" title="93 d&iacute;as, 50 Cr&eacute;ditos"><b>3 meses</b><i></i></a>
                            <a href="#" class="new-button" id="hc-reminder-3" title="186 d&iacute;as, 80 Cr&eacute;ditos"><b>6 meses</b><i></i></a>
                        </div>
                    </li>
                    <script type="text/javascript">
                        L10N.put("subscription.title", "HABBO CLUB");
                    </script>

                    <?php
                }
            }
        }

        if($user_rank > 5){
            $alerts = mysql_evaluate("SELECT COUNT(*) FROM cms_help WHERE picked_up = '0'");
            if($alerts > 0){
                echo "            <li class=\"small\" id=\"feed-group-discussion\">
                <strong>&iquest;Necesitas ayuda?</strong><br />Hay "; if($alerts == 1){ echo " "; } else { echo " "; } echo " <strong><a href='./housekeeping/index.php?p=helper' target='_self'>" . $alerts . "</a></strong> alertas sin atender."; if($alerts > 1){ echo ""; } else { echo ""; } echo "
                </li>";
            }
        }

        $tmp = mysql_query("SELECT * FROM cms_alerts WHERE userid = '".$my_id."'") or die(mysql_error());
        $alerts = mysql_num_rows($tmp);

        if($alerts > 0){

            $row = mysql_fetch_assoc($tmp);

            $id = $row['id'];
            $type = $row['type'];

            if($type == 1){
                $heading = "Notificaci&oacute;n";
            } elseif($type == 2){
                $heading = "Mensaje del " . $shortname . " Staff";
            } else {
                $heading = "undefined";
            }

            if(mysql_num_rows($tmp) > 0) { ?>
                <li id="feed-item-campaign" class="contributed">
                    <a href="#" class="remove-feed-item" id="remove-feed-item-<?php echo $row['id']; ?>" title="Eliminar notificaci&oacute;n">Eliminar notificaci&oacute;n</a>
                    <div>
                        <b><?php echo $heading; ?></b><br />
                        <?php echo HoloText(nl2br(trim(FilterText($row['alert'])))); ?>
                    </div>
                </li>
                <?php            while($row = mysql_fetch_assoc($tmp)) {

                    ?>
                    <li id="feed-item-campaign" class="contributed">
                        <a href="#" class="remove-feed-item" id="remove-feed-item-<?php echo $row['id']; ?>" title="Eliminar notificaci&oacute;n">Eliminar notificaci&oacute;n</a>
                        <div>
                            <b><?php echo $heading; ?></b><br />
                            <?php echo HoloText(nl2br(trim(FilterText($row['alert'])))); ?>
                        </div>
                    </li>

                    <?php
                }
            }
        }
        $sql = mysql_query("SELECT * FROM cms_noobgifts WHERE userid='".$my_id."' LIMIT 1");
        if(mysql_num_rows($sql) > 0) {
            $row = mysql_fetch_assoc($sql); ?>
            <li id="feed-item-giftqueue" class="contributed">
                <a href="#" class="remove-feed-item" title="Eliminar notificaci&oacute;n">Eliminar notificaci&oacute;n</a>
                <div>
                    Ha llegado un nuevo regalo. Esta vez has recibido <?php if($row['gift'] == 0) { echo "Mi primer taburete de ".$shortname; }elseif($row['gift'] == 1) { echo "planta"; } ?>.
                </div>
            </li>
            <?php
        }

        $dob = $myrow['birth'];
        $bits = explode("-", $dob);
        $day = $bits[0];
        $month2 = $bits[1];
        $year2 = $bits[2];

        if($day == $today && $month2 == $month && HoloText(getContent('birthday-notifications'), true) == "1"){

            $age = $year - $year2;

    // If we have haxxorz that bypassed the age check (only javascript validates it), they may be like, what,
    // one year old, so instead of showing 'happy 1th birthday', we properly show 'happy 1st birthday' etc.
            if($age == 1 || $age == 21){
                $age = $age . "st";
            } elseif($age == 2 || $age == 22){
                $age = $age . "nd";
            } elseif($age == 3 || $age = 33){
                $age = $age . "rd";
            } else {
                $age = $age . "th";
            }
            ?>

            <li id="feed-birthday">
                <div>
                    &iexcl;Feliz cumplea&ntilde;os n&uacute;mero <?php echo $age; ?>, <?php echo $name; ?>!<br />&iexcl;Te deseamos un d&iacute;a genial!
                </div>
            </li>
        <?php } ?>
        <?php
        $sql = mysql_query("SELECT * FROM messenger_friendrequests WHERE userid_to = '".$my_id."'");
        $count = mysql_num_rows($sql);
        if($count != 0){ ?>
         <li id="feed-notification">
            Tienes <a href="./client.php" onclick="HabboClient.openOrFocus(this); return false;"><?php echo $count; ?> solicitud(es) de amistad</a> pendiente(s)
        </li>
    <?php } ?>
<?php /*
$onlineCutOff = (time() - 601);
$onlineUsers = mysql_evaluate("SELECT COUNT(*) FROM users WHERE online > " . $onlineCutOff);
$get_users = mysql_query("SELECT id,name,email,ipaddress_last,hbirth,online FROM users WHERE online > " . $onlineCutOff . " ORDER BY online DESC LIMIT " . $onlineUsers) or die(mysql_error());

while($row = mysql_fetch_assoc($get_users)){
	
	if(empty($row['ipaddress_last'])){ $row['ipaddress_last'] = "No IP Found"; }
	printf(" <tr>
  <td class='tablerow1' align='center'>%s</td>
  <td class='tablerow2'><strong>%s</strong><div class='desctext'>%s [<a href='http://who.is/whois-ip/ip-address/%s/' target='_blank'>WHOIS</a>]</div></td>
  <td class='tablerow2' align='center'><a href='mailto:%s'>%s</a></td>
  <td class='tablerow2' align='center'>%s</td>
  <td class='tablerow2' align='center'>%s</td>
  <td class='tablerow2' align='center'><a href='index.php?p=edituser&key=%s'><img src='./images/edit.gif' alt='Edit User Data'></a></td>
</tr>", $row['id'], $row['name'], $row['ipaddress_last'], $row['ipaddress_last'], $row['email'], $row['email'], $row['hbirth'], (time() - $row['online']) . " seconds ago", $row['id']);
}
?>
			<li id="feed-friends">
				Hay <strong>1</strong> amigos en l&iacute;nea!
				<span>
			Dafor
				</span>
			</li>
*/ ?>

<li class="small" id="feed-lastlogin">
    &Uacute;ltima conexi&oacute;n:
    <?php echo $myrow['lastvisit']; ?>
</li>


</ul>
</div>

<p class="last"></p>
</div>
<div class="habblet-container ">        
  <div class="cbb clearfix orange ">
    <h2 class="title">Destacados
    </h2>
    <div id="hotcampaigns-habblet-list-container">
        <ul id="hotcampaigns-habblet-list">
            <?php
            $getcampaigns = mysql_query("select * from cms_campaigns");
            while($campaigns = mysql_fetch_assoc($getcampaigns)) {
                ?>
                <li class="even">
                    <div class="hotcampaign-container">
                        <a href="<?php echo $campaigns['url']; ?>"><img src="<?php echo $campaigns['image']; ?>" align="left" alt="" /></a>
                        <h3><?php echo $campaigns['name']; ?></h3>
                        <p><?php echo $campaigns['desc']; ?></p>
                        <p class="link"><a href="<?php echo $campaigns['url']; ?>">Ver &raquo;&raquo;</a></p>
                    </div>
                </li>
            <?php  } ?>
        </ul>
    </div> 
</div>
</div>
<script type="text/javascript">
    HabboView.add(function() {
        L10N.put("personal_info.motto_editor.spamming", "Don\'t spam me, bro!");
        PersonalInfo.init("");
    });
</script>


</div>

<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

<?php /* Minimail */ ?>
<div class="habblet-container minimail" id="mail">
    <div class="cbb clearfix blue ">

        <h2 class="title">Mis mensajes
        </h2>
        <div id="minimail">
            <div class="minimail-contents">
              <?php
              $bypass = true;
              $page = "inbox";
              include('./minimail/loadMessage.php');
              ?>
          </div>
          <div id="message-compose-wait"></div>
          <form style="display: none" id="message-compose">
           <div>Para</div>
           <div id="message-recipients-container" class="input-text" style="width: 426px; margin-bottom: 1em">
              <input type="text" value="" id="message-recipients" />
              <div class="autocomplete" id="message-recipients-auto">
                 <div class="default" style="display: none;">Escribe el nombre de tu amigo:</div>
                 <ul class="feed" style="display: none;"></ul>

             </div>
         </div>
         <div>Asunto<br/>
           <input type="text" style="margin: 5px 0" id="message-subject" class="message-text" maxlength="100" tabindex="2" />
       </div>
       <div>Mensaje<br/>
           <textarea style="margin: 5px 0" rows="5" cols="10" id="message-body" class="message-text" tabindex="3"></textarea>

       </div>
       <div class="new-buttons clearfix">
           <a href="#" class="new-button preview"><b>Vista previa</b><i></i></a>
           <a href="#" class="new-button send"><b>Enviar</b><i></i></a>
       </div>
   </form>
</div>
<?php
$sql = mysql_query("SELECT * FROM messenger_friendships WHERE userid = '".$my_id."' OR friendid = '".$my_id."'") or die(mysql_error());
$count = mysql_num_rows($sql); 
$sql = mysql_query("SELECT * FROM cms_minimail WHERE to_id = '".$my_id."' OR senderid = '".$my_id."'") or die(mysql_error());
$mescount = mysql_num_rows($sql); 
?>
<script type="text/javascript">
  L10N.put("minimail.compose", "Escribir").put("minimail.cancel", "Cancelar")
  .put("bbcode.colors.red", "Rojo").put("bbcode.colors.orange", "Naranja")
  .put("bbcode.colors.yellow", "Amarillo").put("bbcode.colors.green", "Verde")
  .put("bbcode.colors.cyan", "Cian").put("bbcode.colors.blue", "Azul")
  .put("bbcode.colors.gray", "Gris").put("bbcode.colors.black", "Negro")
  .put("minimail.empty_body.confirm", "&iquest;Seguro que quieres enviar el mensaje sin texto?")
  .put("bbcode.colors.label", "Color").put("linktool.find.label", " ")
  .put("linktool.scope.habbos", "<?php echo $shortname; ?>s").put("linktool.scope.rooms", "Salas")
  .put("linktool.scope.groups", "Grupos").put("minimail.report.title", "Denunciar mensaje a los moderadores");

  L10N.put("date.pretty.just_now", "ahora mismo");
  L10N.put("date.pretty.one_minute_ago", "hace 1 minuto");
  L10N.put("date.pretty.minutes_ago", "hace {0} minutos");
  L10N.put("date.pretty.one_hour_ago", "hace 1 hora");
  L10N.put("date.pretty.hours_ago", "hace {0} horas");
  L10N.put("date.pretty.yesterday", "ayer");
  L10N.put("date.pretty.days_ago", "hace {0} d&iacute;as");
  L10N.put("date.pretty.one_week_ago", "hace 1 semana");
  L10N.put("date.pretty.weeks_ago", "hace {0} semanas");
  new MiniMail({ pageSize: 10,
   total: <?php echo $mescount; ?>,
   friendCount: <?php echo $count; ?>,
   maxRecipients: 50,
   messageMaxLength: 20,
   bodyMaxLength: 4096,
   secondLevel: <?php if($count = 0){ echo "true"; }else{ echo "false"; } ?>});
</script>
</div></div>
<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
<?php /* Noob Gift ?>
                <?php
                $sql = mysql_query("SELECT noob,gift,roomid,sort FROM users WHERE id='".$my_id."' LIMIT 1");
                $row = mysql_query($sql);
                if($row['noob'] == 0 && $row['gift'] == 0 && $row['roomid'] == 0) { ?>
                                <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

                <div class="habblet-container " id="roomselection">
                        <div class="cbb clearfix rooms ">

                            <h2 class="title">&iexcl;Elige tu sala!
                            <span class="habblet-close" id="habblet-close-roomselection"></span></h2>
                        <div id="roomselection-plp-intro" class="box-content">
&iexcl;Hey! No has elegido tu sala predecorada, &iexcl;que viene con muebles gratis! Elige una abajo:
</div>

<ul id="roomselection-plp" class="clearfix">
    <li class="top">
    <a class="roomselection-select new-button green-button" href="client.php?createRoom=0" target="client" onclick="return RoomSelectionHabblet.create(this, 0);"><b>Elegir</b><i></i></a>
    </li>
    <li class="top">
    <a class="roomselection-select new-button green-button" href="client.php?createRoom=1" target="client" onclick="return RoomSelectionHabblet.create(this, 1);"><b>Elegir</b><i></i></a>
    </li>
    <li class="top">
    <a class="roomselection-select new-button green-button" href="client.php?createRoom=2" target="client" onclick="return RoomSelectionHabblet.create(this, 2);"><b>Elegir</b><i></i></a>
    </li>
    <li class="bottom">
    <a class="roomselection-select new-button green-button" href="client.php?createRoom=3" target="client" onclick="return RoomSelectionHabblet.create(this, 3);"><b>Elegir</b><i></i></a>
    </li>
    <li class="bottom">
    <a class="roomselection-select new-button green-button" href="client.php?createRoom=4" target="client" onclick="return RoomSelectionHabblet.create(this, 4);"><b>Elegir</b><i></i></a>
    </li>
    <li class="bottom">
    <a class="roomselection-select new-button green-button" href="client.php?createRoom=5" target="client" onclick="return RoomSelectionHabblet.create(this, 5);"><b>Elegir</b><i></i></a>
    </li>
</ul>

<script type="text/javascript">
L10N.put("roomselection.hide.title", "Ocultar selecci&oacute;n de sala");
L10N.put("roomselection.old_user.done", "&iexcl;Y listo! El hotel se abrir&aacute; ahora en una nueva ventana y te llevaremos a tu sala enseguida.");
HabboView.add(RoomSelectionHabblet.initClosableHabblet);
</script>



                    </div>
                </div>
                <?php }elseif($row['noob'] == 1 && $row['roomid'] != 0) { ?>
                <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

                <div class="habblet-container " id="giftqueue">
                        <div class="cbb clearfix rooms ">

                            <h2 class="title">&iexcl;Tu pr&oacute;ximo regalo!
                            <span class="habblet-close" id="habblet-close-giftqueue"></span></h2>
                        <div class="box-content" id="gift-container">
<?php if($row['gift'] < 2) { ?>

<div class="gift-img"><?php if($row['gift'] == 0) { ?><img src="http://images.habbohotel.co.uk/habboweb/<?php echo $habboversion; ?>/web-gallery/v2/images/welcome/newbie_furni/noob_stool_<?php echo $row['sort']; ?>.png" alt="Mi primer taburete de Obbah" /><?php }elseif($row['gift'] == 1) { ?><img src="http://images.habbohotel.co.uk/habboweb/23_deebb3529e0d9d4e847a31e5f6fb4c5b/9/web-gallery/v2/images/welcome/newbie_furni/noob_plant.png"><?php } ?></div>
<div class="gift-content-container">

<p class="gift-content">
Tu pr&oacute;ximo mueble gratis ser&aacute; <strong><?php if($row['gift'] == 0) { echo "Mi primer taburete"; }elseif($row['gift'] == 1) { echo "planta"; } ?></strong>
</p>

<p>
<b>Tiempo restante:</b> <span id="gift-countdown"></span>
</p>

<p class="last">
<a class="new-button green-button" href="client.php?forwardId=2&roomId=<?php echo $row['roomid']; ?>" target="client" onclick="HabboClient.roomForward(this, '<?php echo $row['roomid']; ?>', 'private'); return false;"><b>Ir a tu sala &gt;&gt;</b><i></i></a>
</p>
<br style="clear: both" />
</div>

<?php
// calculate time

$time = time();
$day = date("j");
$month = date("n");
$year = date("y");
$date = mktime(0,0,0, $month, $day, $year);
$timeleft = $date-$time; ?>

<script type="text/javascript">
L10N.put("time.hours", "{0}h");
L10N.put("time.minutes", "{0}min");
L10N.put("time.seconds", "{0}s");
GiftQueueHabblet.init(<?php echo $timeleft; ?>);
</script>
<?php }else{ ?>
<p>
&iquest;C&oacute;mo consigues m&aacute;s muebles para tu sala?
</p>

<p>
Puedes comprar un set de muebles por solo 3 cr&eacute;ditos que incluye una l&aacute;mpara, una alfombra y dos sillones. &iquest;C&oacute;mo se hace?
</p>

<ul><li>1. Compra cr&eacute;ditos en la secci&oacute;n de <a href="/credits">cr&eacute;ditos</a></li><li>2. Abre el cat&aacute;logo desde la barra del Hotel (icono de la silla)</li><li>3. Abre la secci&oacute;n de ofertas</li><li>4. Coge el set de muebles que quieras</li><li>5. &iexcl;Gracias por tu compra!</li></ul>

<p class="aftergift-img">
  <img src="http://images.habbohotel.co.uk/habboweb/23_deebb3529e0d9d4e847a31e5f6fb4c5b/9/web-gallery/v2/images/giftqueue/aftergifts.png" alt="" width="381" height="63"/>
</p>

<p class="last">
<a class="new-button green-button" href="client.php?forwardId=2&roomId=<?php echo $row['roomid']; ?>" target="client" onclick="HabboClient.roomForward(this, '<?php echo $row['roomid']; ?>', 'private'); return false;"><b>Ir a tu sala &gt;&gt;</b><i></i></a>
</p>

<script type="text/javascript">
HabboView.add(GiftQueueHabblet.initClosableHabblet);
</script>
<?php } ?>

</div>



                    </div>
                </div>
                <?php } ?>

                <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
                <?php */ ?>
                <?php /*Habbo Search*/ ?>
                <div class="habblet-container ">
                    <div class="cbb clearfix default ">
                        <div class="box-tabs-container clearfix">
                            <h2><?php echo $shortname; ?>s</h2>
                            <ul class="box-tabs">
                                
                                <li id="tab-0-3-2" class="selected"><a href="#">Busca <?php echo $shortname; ?>s</a><span class="tab-spacer"></span></li>
                            </ul>
                        </div>
                        <div id="tab-0-3-1-content"  style="display: none">
                            <div id="friend-invitation-habblet-container" class="box-content">
                                <div id="invitation-form" class="clearfix">
                                    <textarea name="invitation_message" id="invitation_message" class="invitation-message">&iexcl;Ven a pasar el rato conmigo en <?php echo $shortname; ?>!
                                        - <?php echo $rawname; ?></textarea>
                                        <div id="invitation-email">
                                            <div class="invitation-input">1.<input  onkeypress="$('invitation_recipient2').enable()" type="text" name="invitation_recipients" id="invitation_recipient1" value="Email de tu amigo" class="invitation-input" />
                                            </div>
                                            <div class="invitation-input">2.<input disabled onkeypress="$('invitation_recipient3').enable()" type="text" name="invitation_recipients" id="invitation_recipient2" value="Email de tu amigo" class="invitation-input" />
                                            </div>
                                            <div class="invitation-input">3.<input disabled  type="text" name="invitation_recipients" id="invitation_recipient3" value="Email de tu amigo" class="invitation-input" />
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="fielderror" id="invitation_message_error" style="display: none;"><div class="rounded"></div></div>
                                    </div>

                                    <div class="invitation-buttons clearfix" id="invitation_buttons">
                                        <a  class="new-button" id="send-friend-invite-button" href="#"><b>Invitar a amigo(s)</b><i></i></a>
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    L10N.put("invitation.button.invite", "Invitar a amigo(s)");
                                    L10N.put("invitation.form.recipient", "Email de tu amigo");
                                    L10N.put("invitation.error.message_too_long", "invitation.error.message_limit");
                                    inviteFriendHabblet = new InviteFriendHabblet(500);
                                    $("friend-invitation-habblet-container").select(".fielderror .rounded").each(function(el) {
                                        Rounder.addCorners(el, 8, 8);
                                    });
                                </script>    </div>
                                <div id="tab-0-3-2-content" >
                                    <div class="habblet-content-info">
                                        <a name="habbo-search">Escribe el nombre de un <?php echo $shortname; ?> para visitar su perfil.</a>
                                    </div>
                                    <div id="habbo-search-error-container" style="display: none;"><div id="habbo-search-error" class="rounded rounded-red"></div></div>
                                    <br clear="all"/>
                                    <div id="avatar-habblet-list-search">
                                        <input type="text" id="avatar-habblet-search-string"/>
                                        <a href="#" id="avatar-habblet-search-button" class="new-button"><b>Buscar</b><i></i></a>
                                    </div>

                                    <br clear="all"/>

                                    <div id="avatar-habblet-content">
                                        <div id="avatar-habblet-list-container" class="habblet-list-container">
                                            <ul class="habblet-list">
                                            </ul>

                                        </div>
                                        <script type="text/javascript">
                                            L10N.put("habblet.search.error.search_string_too_long", "La palabra de b&uacute;squeda es demasiado larga. La longitud m&aacute;xima es de 25 caracteres.");
                                            L10N.put("habblet.search.error.search_string_too_short", "La palabra de b&uacute;squeda es demasiado corta. Se necesita al menos 1 car&aacute;cter.");
                                            L10N.put("habblet.search.add_friend.title", "&iquest;Enviar solicitud de amistad?");
                                            new HabboSearchHabblet(1, 25);

                                        </script>
                                    </div>

                                    <script type="text/javascript">
                                        Rounder.addCorners($("habbo-search-error"), 8, 8);
                                    </script>    </div>

                                </div>
                            </div>
                            <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
                            <?php /* Groups */ ?>
                            <div class="habblet-container ">
                                <div class="cbb clearfix blue ">
                                    <div class="box-tabs-container clearfix">
                                        <h2>Grupos</h2>
                                        <ul class="box-tabs">
                                            <li id="tab-2-1"><a href="#">Grupos aleatorios</a><span class="tab-spacer"></span></li>
                                            <li id="tab-2-2" class="selected"><a href="#">Mis grupos</a><span class="tab-spacer"></span></li>
                                        </ul>
                                    </div>
                                    <div id="tab-2-1-content"  style="display: none">
                                      <div class="progressbar"><img src="./web-gallery/images/progress_bubbles.gif" alt="" width="29" height="6" /></div>
                                      <a href="randomgroups.php?sp=plain" class="tab-ajax"></a>
                                  </div>
                                  <div id="tab-2-2-content" >


                                   <div id="groups-habblet-info" class="habblet-content-info">
                                    &iexcl;Descubre los grupos de tus amigos y crea el tuyo!
                                </div>

                                <div id="groups-habblet-list-container" class="habblet-list-container groups-list">

                                    <?php
                                    $get_em = mysql_query("SELECT * FROM groups_memberships WHERE userid = '".$my_id."'") or die(mysql_error());
                                    $groups = mysql_num_rows($get_em);

                                    echo "\n    <ul class=\"habblet-list two-cols clearfix\">";

                                    $num = 0;

                                    while($row = mysql_fetch_assoc($get_em)){
                                       $num++;

                                       if(IsEven($num)){
                                          $pos = "right";
                                          $rights++;
                                      } else {
                                          $pos = "left";
                                          $lefts++;
                                      }

                                      if(IsEven($lefts)){
                                          $oddeven = "odd";
                                      } else {
                                          $oddeven = "even";
                                      }

                                      $group_id = $row['groupid'];
                                      $check = mysql_query("SELECT * FROM groups_details WHERE id = '".$group_id."' LIMIT 1");
                                      $groupdata = mysql_fetch_assoc($check);

                                      echo "            <li class=\"".$oddeven." ".$pos."\" style=\"background-image: url(./habbo-imaging/badge.php?badge=".$groupdata['badge'].")\">\n            	\n                \n                <a class=\"item\" href=\"group_profile.php?id=".$group_id."\">".HoloText($groupdata['name'])."</a>\n            </li>";
                                  }

                                  $rights_should_be = $lefts;
                                  if($rights !== $rights_should_be){
                                   echo "<li class=\"".$oddeven." right\"><div class=\"item\">&nbsp;</div></li>";
                               }

                               echo "\n    </ul>";
                               ?>

                               <div class="habblet-button-row clearfix"><a class="new-button" id="purchase-group-button" href="#"><b>Crear un grupo</b><i></i></a></div>
                           </div>

                           <div id="groups-habblet-group-purchase-button" class="habblet-list-container"></div>

                           <script type="text/javascript">
                            $("purchase-group-button").observe("click", function(e) { Event.stop(e); GroupPurchase.open(); });
                        </script>





                    </div>

                </div>
            </div>

            <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
        </div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

        <script type='text/javascript'>if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
        <div id="column2" class="column">
            <div class="habblet-container news-promo">
              <div class="cbb clearfix notitle ">


                  <div id="newspromo">
                    <div id="topstories">
                       <div class="topstory" style="background-image: url(<?php echo $news_1_topstory; ?>)">
                           <h4>&Uacute;ltima noticia <a href="./rss.php"><img src="./web-gallery/v2/images/holo/feed-icon.gif" alt="" border="0"/></a></h4>
                           <h3><a href="news.php?id=<?php echo $news_1_id; ?>"><?php echo $news_1_title; ?></a></h3>
                           <p class="summary">
                               <?php echo $news_1_snippet; ?>
                           </p>
                           <p>
                               <a href="news.php?id=<?php echo $news_1_id; ?>">Leer m&aacute;s &raquo;</a>
                           </p>
                       </div>
                       <div class="topstory" style="background-image: url(<?php echo $news_2_topstory; ?>); display: none">
                           <h4>&Uacute;ltima noticia</h4>
                           <h3><a href="news.php?id=<?php echo $news_2_id; ?>"><?php echo $news_2_title; ?></a></h3>
                           <p class="summary">
                               <?php echo $news_2_snippet; ?>
                           </p>
                           <p>
                               <a href="news.php?id=<?php echo $news_2_id; ?>">Leer m&aacute;s &raquo;</a>
                           </p>
                       </div>
                       <div id="topstories-nav" style="display: none"><a href="#" class="prev">&laquo; Anterior</a><span>1</span> / 2<a href="#" class="next">Siguiente &raquo; </a></div>
                   </div>
                   <ul class="widelist">
                    <li class="even">
                        <a href="news.php?id=<?php echo $news_3_id; ?>"><?php echo $news_3_title; ?></a><div class="newsitem-date"><?php echo $news_3_date; ?></div>
                    </li>
                    <li class="odd">
                        <a href="news.php?id=<?php echo $news_4_id; ?>"><?php echo $news_4_title; ?></a><div class="newsitem-date"><?php echo $news_4_date; ?></div>
                    </li>
                    <li class="last"><a href="news.php">Todas las noticias &raquo;</a></li>
                </ul>
                
            </div>
            <script type="text/javascript">
               document.observe("dom:loaded", function() { NewsPromo.init(); });
           </script>
       </div>
   </div>
   <div class="habblet-container ">
    <div class="cbb clearfix blue ">
        <div class="box-tabs-container clearfix">
            <h2>&iexcl;Hola <?php echo $name; ?>!</h2>
            <ul class="box-tabs">

            </ul>
        </div>
        <div id="tab-2-1-content"  style="display: none">
          <div class="progressbar"><img src="./web-gallery/images/progress_bubbles.gif" alt="" width="29" height="6" /></div>
          <a href="randomgroups?sp=plain" class="tab-ajax"></a>
      </div>
      <div id="tab-2-2-content" >


          <div id="groups-habblet-info" class="habblet-content-info">

           <div id="invitation-link-container">
            <h3>&iexcl;Invita a tus amigos y gana cr&eacute;ditos!</h3>
            <div class="copytext">
                <p>Desde ahora puedes compartir tu enlace y ganar cr&eacute;ditos. &iquest;C&oacute;mo hacerlo?</p>
            </div>
        </div>

    </div>

    <div class="habblet-button-row clearfix"><a class="new-button" id="purchase-group-button" href="account.php?tab=5"><b>&iexcl;Invita a tus amigos!</b><i></i></a></div>


    






</div>

</div>
</div>
<?php /* Recommend Groups  */?>
<div class="habblet-container ">        
    <div class="cbb clearfix blue ">
        
        <h2 class="title">Recomendados
        </h2>
        <div id="promogroups-habblet-list-container" class="habblet-list-container groups-list">
            <ul class="habblet-list two-cols clearfix">
                <?php $sql = mysql_query("SELECT * FROM cms_recommended WHERE type = 'group' ORDER BY id ASC") or die(mysql_error());
                while($row = mysql_fetch_assoc($sql)) {
                    $i++;
                    
                    $groupsql = mysql_query("SELECT * FROM groups_details WHERE id = '".$row['rec_id']."' LIMIT 1");
                    $grouprow = mysql_fetch_assoc($groupsql);

                    if(IsEven($i)){
                        $even = "even left";
                    } else {
                        $even = "even right";
                    }
                    ?>
                    <li class="<?php echo $even; ?>" style="background-image: url(./habbo-imaging/badge-fill/<?php echo $grouprow['badge']; ?>.gif)">
                        <?php if($grouprow['roomid'] != 0) { ?><a href="client.php?forwardId=2&amp;roomId=<?php echo $grouprow['roomid']; ?>" onclick="HabboClient.roomForward(this, '<?php echo $grouprow['roomid']; ?>', 'private'); return false;" target="client" class="group-room"></a><?php } ?>
                        <a class="item" href="group_profile.php?id=<?php echo $grouprow['id']; ?>"><?php echo HoloText($grouprow['name']); ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        
        
    </div>
</div>
<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
<?php /*Tags */ ?>
<div class="habblet-container ">
  <div class="cbb clearfix green ">
    <div class="box-tabs-container clearfix">
        <h2>Tags</h2>
        <ul class="box-tabs">
            <li id="tab-3-1"><a href="#">A los usuarios les gusta..</a><span class="tab-spacer"></span></li>
            <li id="tab-3-2" class="selected"><a href="#">Mis etiquetas</a><span class="tab-spacer"></span></li>
        </ul>
    </div>
    <div id="tab-3-1-content"  style="display: none">
      <div class="progressbar"><img src="./web-gallery/images/progress_bubbles.gif" alt="" width="29" height="6" /></div>
      <a href="tagcloud.php?sp=plain" class="tab-ajax"></a>
  </div>
  <div id="tab-3-2-content" >
    <div id="my-tag-info" class="habblet-content-info">
        <?php if($tags_num > 19){ echo "Has alcanzado el m&aacute;ximo de etiquetas."; } elseif($tags_num == 0){ echo "&iexcl;No tienes etiquetas, puedes a&ntilde;adir una ahora!"; } elseif($tags_num < 20){ echo "&iexcl;Puedes a&ntilde;adir m&aacute;s etiquetas!"; } ?>
    </div>
    <div class="box-content">
        <div class="habblet" id="my-tags-list">

            <?php if($tags_num > 0){
                echo "<ul class=\"tag-list make-clickable\"> ";
                while($row = mysql_fetch_assoc($fetch_tags)){
                    printf("<li><a href=\"tags.php?tag=%s\" class=\"tag\" style=\"font-size:10px\">%s</a>\n
                        <a class=\"tag-remove-link\"\n
                        title=\"Eliminar etiqueta\"\n
                        href=\"#\"></a></li>\n", $row['tag'], $row['tag']);
                }
                echo "</ul>";
            } ?>

            <?php if($tags_num < 20){ ?>
               <form method="post" action="tags_ajax.php?key=add" onsubmit="TagHelper.addFormTagToMe();return false;" >
                <div class="add-tag-form clearfix">
                  <a  class="new-button" href="#" id="add-tag-button" onclick="TagHelper.addFormTagToMe();return false;"><b>A&ntilde;adir</b><i></i></a>
                  <input type="text" id="add-tag-input" maxlength="20" style="float: right"/>
                  <em class="tag-question"><?php echo $tag_question; ?></em>
              </div>
              <div style="clear: both"></div>
          </form>
      <?php } ?>
  </div>
</div>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        TagHelper.setTexts({
            tagLimitText: "Has alcanzado el l&iacute;mite de etiquetas. Elimina una para a&ntilde;adir otra.",
            invalidTagText: "Etiqueta no v&aacute;lida",
            buttonText: "OK"
        });
        TagHelper.init('21063711');
    });
</script>
</div>

</div>
</div>
<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>


<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
<?php /* Random Rooms*/ ?>
<div class="habblet-container ">
  <div class="cbb clearfix green ">
    <div class="box-tabs-container clearfix">
        <h2>Salas aleatorias..</h2>
        <ul class="box-tabs">
        </ul>
    </div>
    <div id="tab-0-2-content" >

        <div id="rooms-habblet-list-container-h105" class="recommendedrooms-lite-habblet-list-container">
            <ul class="habblet-list">

                <?php
                $i = 0;
                $getem = mysql_query("SELECT * FROM rooms WHERE owner IS NOT NULL ORDER BY RAND() LIMIT 5") or die(mysql_error());

                while ($row = mysql_fetch_assoc($getem)) {
    if($row['owner'] !== ""){ // Public Rooms (and possibly bugged rooms) have no owner, thus do not display them
        $i++;

        if(IsEven($i)){
            $even = "odd";
        } else {
            $even = "even";
        }

        // Calculate percentage
        if($row['incnt_max'] == 0){ $row['incnt_max'] = 1; }
        $data[$i] = ($row['incnt_now'] / $row['incnt_max']) * 100;

        // Base room icon based on this - percantage levels may not be habbolike
        if($data[$i] == 99 || $data[$i] > 99){
            $room_fill = 5;
        } elseif($data[$i] > 65){
            $room_fill = 4;
        } elseif($data[$i] > 32){
            $room_fill = 3;
        } elseif($data[$i] > 0){
            $room_fill = 2;
        } elseif($data[$i] < 1){
            $room_fill = 1;
        }

        printf("<li class=\"%s\">
            <span class=\"clearfix enter-room-link room-occupancy-%s\" title=\"Ir a la sala\" roomid=\"%s\">
            <span class=\"room-enter\">Entrar</span>
            <span class=\"room-name\">%s</span>
            <span class=\"room-description\">%s</span>
            <span class=\"room-owner\">Creador: <a href=\"user_profile.php?name=%s\">%s</a></span>
            </span>
            </li>", $even, $room_fill, $row['id'], HoloText($row['name']), FilterText($row['descr']), $row['owner'], $row['owner']);
    }
}
?>

</ul>
<div class="clearfix"></div>
</div>
<script type="text/javascript">
    L10N.put("show.more", "Ver m&aacute;s salas");
    L10N.put("show.less", "Ver menos salas");
    var roomListHabblet_h105 = new RoomListHabblet("rooms-habblet-list-container-h105", "room-toggle-more-data-h105", "room-more-data-h105");
</script>
</div>

</div>
</div>
<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div>


<script type="text/javascript">
	HabboView.add(LoginFormUI.init);
</script>
<?php

include('templates/community/footer.php');

?>