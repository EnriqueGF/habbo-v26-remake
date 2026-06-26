<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright � 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind.
+---------------------------------------------------*/

include('core.php');

session_start();

if(!session_is_registered(username)){

include("locale/".$language."/login.php");

include('templates/login/subheader.php');
include('templates/login/header.php');

if(isset($_POST['actionForgot'])){
$forgot_name = $_POST['forgottenpw-username'];
$forgot_mail = $_POST['forgottenpw-email'];
$sql_forgot = mysql_query("SELECT password FROM users WHERE name = '".$forgot_name."' and email = '".$forgot_mail ."'") or die(mysql_error());
$sql_num = mysql_num_rows($sql_forgot);
	if($sql_num > 0){
	  $password = "";
	  $length = 8;
	  $possible = "0123456789qwertyuiopasdfghjkzxcvbnm";
	  $i = 0;
	  while ($i < $length) {
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		if (!strstr($password, $char)) {
		  $password .= $char;
		  $i++;
		}
	  }
	$result = $locale['forgot_mail_send'];
	$sql_row = mysql_fetch_assoc($sql_forgot);
	$hashed_pass = HoloHash($password, $sql_row['name']);
	mysql_query("UPDATE users SET password = '".$hashed_pass."' WHERE name = '".$forgot_name."'") or die(mysql_error());
	$teh_pass = $password;
	$subject = 'Your ".$shortname." Password';
	$headers = "From: ".$sitename." <mailings@".strtolower($shortname).".com>\r\nReply-To:admin@".strtolower($shortname).".com";
	$headers .= "\r\nContent-Type: text/html;charset=ISO-8859-1\r\nContent-Transfer-Encoding: 7bit";
	ob_start(); //Turn on output buffering
	?>
	<html><head><style type="text/css">a { color: #fc6204; }</style></head><body style="background-color: #e3e3db; margin: 0; padding: 0; font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000;"><div style="background-color: #bce0ee; padding: 14px; border-bottom: 3px solid #000;">	<img src="<?php echo $path; ?>web-gallery/v2/images/habbo.png" alt="<?php echo $shortname; ?>" /></div><div style="padding: 14px 14px 50px 14px; background-color: #e3e3db;">	<div style="background-color: #fff; padding: 14px; border: 1px solid #ccc"><h1 style="font-size: 16px">Tu nueva contrase&ntilde;a:</h1>

	<p>
	Hola <b><?php echo $forgot_name; ?></b>, tu nueva contrase&ntilde;a es:<br /><b><?php echo $teh_pass; ?></b><br />C&aacute;mbiala despu&eacute;s de iniciar sesi&oacute;n.
	</p>	</div>	<div style="padding: 14px 0; text-align: center; font-size: 10px;">		Todos los derechos, incluidas marcas comerciales, copyright y derechos de bases de datos del sitio Habbo y su contenido son propiedad de o est&aacute;n bajo licencia de Sulake Inc. Todos los derechos reservados. Nota: si no deseas recibir m&aacute;s correos de <?php echo $shortname; ?>, env&iacute;a un e-mail a optout@<?php echo $shortname; ?>.com.	</div></div></body></html>
	<?php
	$message = ob_get_clean();
	$mail_sent = @mail($forgot_mail, $subject, $message, $headers );
	} else {
	$result = $locale['forgot_error_invalid'];
	}
}

?>

<?php /* We have to use special CSS formatting for the page to display properly here. */ ?>

<style type="text/css">
		div.left-column { float: left; width: 50% }
		div.right-column { float: right; width: 49% }
		label { display: block }
		input { width: 98% }
		input.process-button { width: auto; float: right }
	</style>

			<div id="process-content">
	        	<div class="left-column">

<?php if(!empty($result)){ ?>
<div class="cbb clearfix white">
    <div class="box-content">

        <p><?php echo "<div align='center'><b>".$result."</b></div>"; ?></p>

    </div>
</div>
<?php } ?>

<div class="cbb clearfix">
    <h2 class="title"><?php echo $locale['forgot_pass']; ?></h2>
    <div class="box-content">

        <p><?php echo $locale['forgot_pass_content']; ?></p>

        <div class="clear"></div>

        <form method="post" action="forgot.php" id="forgottenpw-form">
            <p>
            <label for="forgottenpw-username"><?php echo $locale['forgot_username']; ?></label>
            <input type="text" name="forgottenpw-username" id="forgottenpw-username" value="" />
            </p>

            <p>
            <label for="forgottenpw-email"><?php echo $locale['forgot_email']; ?></label>
            <input type="text" name="forgottenpw-email" id="forgottenpw-email" value="" />
            </p>

            <p>
            <input type="submit" value="<?php echo $locale['forgot_button']; ?>" name="actionForgot" class="submit process-button" id="forgottenpw-submit" />
            </p>
            <input type="hidden" value="default" name="origin" />
        </form>
    </div>
</div>

</div>


<div class="right-column">

<div class="cbb clearfix">
    <h2 class="title"><?php echo $locale['forgot_false_alarm']; ?></h2>
    <div class="box-content">
        <p><?php echo $locale['forgot_false_alarm_content']; ?></p>
        <p><a href="index.php"><?php echo $locale['forgot_back']; ?> &raquo;</a></p>
    </div>
</div>

</div>

<?php

} else {

include('templates/login/subheader.php');
include('templates/login/header.php');

?>

<div id="process-content">
	        	<div class="action-error flash-message">
	<div class="rounded">
		&iexcl;Cierra sesi&oacute;n primero!
	</div>
</div>

<div style="text-align: center">

	<div style="width:100px; margin: 10px auto"><a href="index.php" id="logout-ok" class="new-button fill"><b>Volver</b><i></i></a></div>

<div id="column1" class="column">
</div>
<div id="column2" class="column">
</div>

</div>

<?php

}

include('templates/login/footer.php');

?>
