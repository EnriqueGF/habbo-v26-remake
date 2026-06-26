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


include('core.php');




session_start();

if(isset($_GET) && $_GET['do'] == "version"){
echo "<h1>HoloCMS Version</h1><hr />".$sitename." is running HoloCMS v".$holocms['version']."<hr /><p><strong>HoloCMS Version:</strong> ".$holocms['version']." ".$holocms['stable']." [".$holocms['title']."]<br />\n<strong>HoloCMS Build Date:</strong> ".$holocms['date']."<br />\n<strong>Holograph Emulator Compatability:</strong> Build for <a href='http://trac2.assembla.com/holograph/changeset/".$holograph['revision']."' target='_blank'>Revision ".$holograph['revision']."</a> (".$holograph['type'].")</p>\n<hr /><i>".$remote_ip." @ ".$path."</i>";
exit;
}

if(!session_is_registered(username)){

	if(htmlentities(isset($_POST['username']),ENT_QUOTES) && htmlentities(isset($_POST['password']),ENT_QUOTES)){
		$username = htmlentities(FilterText($_POST['username']),ENT_QUOTES);
		// $password = htmlentities(HoloHash($_POST['password']),ENT_QUOTES);
		$password = htmlentities(HoloHash($_POST['password'], $username),ENT_QUOTES);
		$remember_me = $_POST['_login_remember_me'];

		if(empty($username) || empty($password)){
			$login_error = "Por favor, rellena todos los campos.";
		} else {
			$sql = mysql_query("SELECT id FROM users WHERE name = '".$username."' AND password = '".$password."' LIMIT 1") or die(mysql_error());
			$rows = mysql_num_rows($sql);
			if($rows < 1){
				$login_error = "Nombre de usuario o contrase&ntilde;a incorrectos.";
			} else {
				$userdata = mysql_fetch_assoc($sql);
				$userid = $userdata['id'];
				$check = mysql_query("SELECT * FROM users_bans WHERE userid = '".$userid."' OR ipaddress = '".$remote_ip."' LIMIT 1") or die(mysql_error());
				$is_banned = mysql_num_rows($check);
				if($is_banned < 1){
					$_SESSION['username'] = $username;
					$_SESSION['password'] = $password;
					if($remember_me == "true"){
						setcookie("remember", "remember", time()+60*60*24*100, "/");
						setcookie("rusername", $_SESSION['username'], time()+60*60*24*100, "/");
						setcookie("rpassword", $_SESSION['password'], time()+60*60*24*100, "/");
					}
					$sql3 = mysql_query("UPDATE users SET lastvisit = '".$date_full."' WHERE name = '".$username."'") or die(mysql_error());
					header("location:security_check.php"); exit;
				} else {
					$bandata = mysql_fetch_assoc($check);
					$reason = $bandata['descr'];
					$expire = $bandata['date_expire'];

					$xbits = explode(" ", $expire);
					$xtime = explode(":", $xbits[1]);
					$xdate = explode("-", $xbits[0]);

					$stamp_now = mktime(date('H'),date('i'),date('s'),$today,$month,$year);
					$stamp_expire = mktime($xtime[0], $xtime[1], $xtime[2], $xdate[0], $xdate[1], $xdate[2]);

					if($stamp_now < $stamp_expire){
						$login_error = "Tu cuenta est&aacute; baneada. Motivo del baneo: \"".$reason."\". El baneo expira el ".$expire.".";
					} else { // ban expired
						mysql_query("DELETE FROM users_bans WHERE userid = '".$userid."' OR ipaddress = '".$remote_ip."' LIMIT 1") or die(mysql_error());
						$_SESSION['username'] = $username;
						$_SESSION['password'] = $password;
						if($remember_me == "true"){
							setcookie("remember", "remember", time()+60*60*24*100, "/");
							setcookie("rusername", $_SESSION['username'], time()+60*60*24*100, "/");
						setcookie("rpassword", HoloHash($_SESSION['password'], $_SESSION['username']), time()+60*60*24*100, "/");
						}
						$sql3 = mysql_query("UPDATE users SET lastvisit = '".$date_full."' WHERE name = '".$username."'") or die(mysql_error());
						header("location:security_check.php"); exit;
					}
				}
			}
		}
	}

	if(isset($_GET['error'])){
		$errorno = $_GET['error'];
		if($errorno == 1){
			$login_error = "Nombre de usuario o contrase&ntilde;a incorrectos.";
		} elseif($errorno == 2){
			$login_error = "Nombre de usuario o contrase&ntilde;a incorrectos.";
		} elseif(isset($_GET['ageLimit']) && $_GET['ageLimit'] == "true"){
			$login_error = "Eres demasiado joven para registrarte.";
		}
	}

	include("locale/en/login.php");
       	include('templates/login/subheader.php');
	include('templates/login/header.php');
	

?>

<div id="column1" class="column">
				<div class="habblet-container " id="create-habbo">	
	
		<br><div style="background-image: url(web-gallery/v2/images/hotelview.png); width: 756px; height: 389px; margin-left: -21px;"></div>
</div>





				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>
<br /><br /><br /><br /><br /><br /><br /><br /><br />

<div id="column2" class="column">
				<div class="habblet-container ">

<?php
	if(isset($error)){
		echo "\n<div class=\"action-error flash-message\">\n <div class=\"rounded\">\n  <ul>\n   <li>".$error."</li>\n  </ul>\n </div>\n</div>\n";
	}
?>
						<div class="cbb loginbox clearfix">
    <div class="box-content clearfix" id="login-habblet">
        <form action="index.php?do=process_login" method="post" class="login-habblet"><font color="#535454">

            <ul>
                <li>
                    <label for="login-username" class="login-text">Nombre de usuario</label>
                    <input tabindex="1" type="text" class="login-field" name="username" id="login-username" value="<?php echo htmlentities($_POST['username'],ENT_QUOTES); ?>" />
                </li>
                <li>
                    <label for="login-password" class="login-text">Contrase&ntilde;a</label>
                    <input tabindex="2" type="password" class="login-field" name="password" id="login-password" />
	                    <input type="submit" value="Entrar" class="submit" id="login-submit-button"/>
	                    <a href="#" id="login-submit-new-button" class="new-button" style="float: left; margin-left: 0;display:none"><b style="padding-left: 10px; padding-right: 7px; width: 55px">Entrar</b><i></i></a>
                </li>
                <li class="no-label">
                    <input tabindex="3" type="checkbox" name="_login_remember_me" id="login-remember-me" value="true"/>
                    <label for="login-remember-me">Recordar mis datos</label></FONT>
                </li>
                <li class="no-label">
                    <a href="register.php" class="login-register-link"><span><strong><font color="#0094FF">REG&Iacute;STRATE AHORA &raquo;</font></strong></span></a>
                </li>
                <li class="no-label">
                    <a href="forgot.php" id="forgot-password"><span>&iquest;Has olvidado tu contrase&ntilde;a?</span></a>
                </li>
            </ul>
        </form>

    </div>
</div>
<div id="remember-me-notification" class="bottom-bubble" style="display:none;">
	<div class="bottom-bubble-t"><div></div></div>
	<div class="bottom-bubble-c">
	Al marcar "Recordar mis datos" este ordenador recordar&aacute; autom&aacute;ticamente tu nombre de usuario y contrase&ntilde;a. &iexcl;No marques esta opci&oacute;n si est&aacute;s en un lugar p&uacute;blico!
	</div>
	<div class="bottom-bubble-b"><div></div></div>
</div>
<script type="text/javascript">
	HabboView.add(LoginFormUI.init);
	HabboView.add(RememberMeUI.init);
</script>



								</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class="habblet-container ">

						<div class="ad-container">
<div id="geoip-ad" style="display:none"></div>
</div>



				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class="habblet-container ">





				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class="habblet-container ">

						<div class="ad-container">
<a href="register.php"><img src="./web-gallery/v2/images/landing/lobby.gif" alt="" /></a>
</div>



				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div>

<?php

include('templates/login/footer.php');

} else {
header("location:me.php");
}

?>