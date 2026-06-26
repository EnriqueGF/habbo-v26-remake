<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright � 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================+
|| # Friends Management by Yifan Lu
|| # www.obbahhotel.com
|+===================================================*/


$allow_guests = false;

require_once('core.php');
require_once('includes/session.php');
if(function_exists(SendMUSData) !== true){ include('includes/mus.php'); }

$pagename = "Mis datos";
$body_id = "profile";
$pageid = "2";

if(isset($_GET['tab'])){
	if($_GET['tab'] < 1 || $_GET['tab'] > 8 ){
		header("Location: account.php?tab=1");
		$tab = 0;
		exit;
	} else {
		$tab = $_GET['tab'];
	}
} else {
	$tab = "1";
}

if($tab == "1"){

	if(isset($_POST['figureData'])){
		$refer = $_SERVER['HTTP_REFERER']; $pos = strrpos($refer, "account.php"); if ($pos === false) { echo "<h1>Error de seguridad.</h1>"; exit; }
		$new_figure = FilterText($_POST['figureData']);
		$new_gender = FilterText($_POST['newGender']);
		if($new_gender !== "M" && $new_gender !== "F"){
			$result = "Se ha producido un error. Int&eacute;ntalo de nuevo.";
			$error = "1";
		} else {
			if(empty($new_figure)){
				$result = "Error al procesar tu solicitud.";
				$error = "1";
			} else {
				mysql_query("UPDATE users SET figure = '".$new_figure."', sex = '".$new_gender."' WHERE name = '".$rawname."' LIMIT 1") or die(mysql_error());
				$result = "Cambios guardados";
				$mylook1 = FilterText($_POST['figureData']);
				$mysex1 = FilterText($_POST['newGender']);
				@SendMUSData('UPRA' . $my_id);
			}
		}
	} else {
		$mylook1 = $myrow['figure'];
		$mysex1 = $myrow['sex'];
	}

	// Wardrobe handler
	$slot1 = mysql_query("SELECT figure,gender FROM cms_wardrobe WHERE slotid = '1' AND userid = '".$my_id."' LIMIT 1") or die(mysql_error());
	$slot1 = mysql_fetch_assoc($slot1);
	if(!empty($slot1['figure'])){ $slot1_url = "/habbo-imaging/avatarimage?figure=".$slot1['figure']."&size=s&direction=4&head_direction=4&gesture=sml"; }
	$slot2 = mysql_query("SELECT figure,gender FROM cms_wardrobe WHERE slotid = '2' AND userid = '".$my_id."' LIMIT 1") or die(mysql_error());
	$slot2 = mysql_fetch_assoc($slot2);
	if(!empty($slot2['figure'])){ $slot2_url = "/habbo-imaging/avatarimage?figure=".$slot2['figure']."&size=s&direction=4&head_direction=4&gesture=sml"; }
	$slot3 = mysql_query("SELECT figure,gender FROM cms_wardrobe WHERE slotid = '3' AND userid = '".$my_id."' LIMIT 1") or die(mysql_error());
	$slot3 = mysql_fetch_assoc($slot3);
	if(!empty($slot3['figure'])){ $slot3_url = "/habbo-imaging/avatarimage?figure=".$slot3['figure']."&size=s&direction=4&head_direction=4&gesture=sml"; }
	$slot4 = mysql_query("SELECT figure,gender FROM cms_wardrobe WHERE slotid = '4' AND userid = '".$my_id."' LIMIT 1") or die(mysql_error());
	$slot4 = mysql_fetch_assoc($slot4);
	if(!empty($slot4['figure'])){ $slot4_url = "/habbo-imaging/avatarimage?figure=".$slot4['figure']."&size=s&direction=4&head_direction=4&gesture=sml"; }
	$slot5 = mysql_query("SELECT figure,gender FROM cms_wardrobe WHERE slotid = '5' AND userid = '".$my_id."' LIMIT 1") or die(mysql_error());
	$slot5 = mysql_fetch_assoc($slot5);
	if(!empty($slot5['figure'])){ $slot5_url = "/habbo-imaging/avatarimage?figure=".$slot5['figure']."&size=s&direction=4&head_direction=4&gesture=sml"; }

} else if($tab == "2"){
	if(isset($_POST['save'])){
		if(strlen($_POST['motto']) > 32){
			$result = "El lema que has introducido es demasiado largo.";
			$error = "1";
			$motto = $_POST['motto']; // Do not add slashes, no database communication here.
		} else {
			$motto = FilterText($_POST['motto']);
$motto = preg_replace('##', 'Anti-Script', $motto);
			mysql_query("UPDATE users SET mission = '".$motto."' WHERE name = '".$rawname."' and password = '".$rawpass."' LIMIT 1");
			$motto = preg_replace('##', 'Anti-Script', $motto);
			$result = "&iexcl;Tu perfil ha sido actualizado!";
			$motto = $_POST['motto']; // Do not add slashes, this is for display purposes.
			@SendMUSData('UPRA' . $my_id);
		}
	} else {
		$motto = HoloText($myrow['mission']);
	}
} else if($tab == "3"){
	if(isset($_POST['save'])){
	$pass1 = $_POST['password'];
	//Hashes and salts the first password with the user id (in lowercase) --encryption--
	$pass1_hash = HoloHash($pass1, $myrow['name']);
	$day1 = $_POST['day'];
	$month1 = $_POST['month'];
	$year1 = $_POST['year'];
		$formatted_dob = "".$day1."-".$month1."-".$year1."";
	$mail1 = $_POST['email'];
	$themail = $mail1;
	if($_POST['directemail'] == "on"){ $newsletter = "checked=\"checked\""; }else{ $newsletter = ""; }
		//checks password --encryption--
		if($pass1_hash == $myrow['password'] && $formatted_dob == $myrow['birth']){
		$email_check = preg_match("/^[a-z0-9_\.-]+@([a-z0-9]+([\-]+[a-z0-9]+)*\.)+[a-z]{2,7}$/i", $mail1);
			if($email_check == "1"){
			if($_POST['directemail'] == "on"){ $newsletter = "1"; }else{ $newsletter = "0"; }
			mysql_query("UPDATE users SET email = '".$mail1."', newsletter = '".$newsletter."' WHERE name = '".$rawname."' and password = '".$rawpass."'") or die(mysql_error());
			$result = "Tu direcci&oacute;n de correo ha sido cambiada a ".$mail1."";
			} else {
			$result = "Direcci&oacute;n de correo no v&aacute;lida";
			$error = "1";
			}
		} else {
		$result = "Los datos introducidos no coinciden con los registrados";
		$error = "1";
		}
	} else {
	$themail = $myrow['email'];
	if($myrow['newsletter'] == "1"){ $newsletter = "checked=\"checked\""; }else{ $newsletter = ""; }
	}
} else if($tab == "4"){
	if(isset($_POST['save'])){
	$pass1 = $_POST['password'];
	//Hashes and salts the old password with the user id (in lowercase) --encryption--
	$pass1_hash = HoloHash($pass1, $myrow['name']);
	$day1 = $_POST['day'];
	$month1 = $_POST['month'];
	$year1 = $_POST['year'];
		$formatted_dob = "".$day1."-".$month1."-".$year1."";
	$newpass = $_POST['pass'];
	//Hashes and salts the new password with the user id (in lowercase) --encryption--
	$newpass_hash = HoloHash($newpass, $rawname);
	$newpass_conf = $_POST['confpass'];
		if($pass1_hash == $myrow['password'] && $formatted_dob == $myrow['birth']){
			if($newpass == $newpass_conf){
				if(strlen($newpass) < 6){
				$result = "&iexcl;Tu contrase&ntilde;a es demasiado corta!";
				$error = "1";
				} else {
					if(strlen($newpass) > 25){
					$result = "&iexcl;Tu contrase&ntilde;a es demasiado larga!";
					$error = "1";
					} else {
					//Updates password --encryption--
					mysql_query("UPDATE users SET password = '".$newpass_hash."' WHERE name = '".$rawname."' and password = '".$rawpass."'") or die(mysql_error());
					$result = "&iexcl;Tu contrase&ntilde;a ha sido cambiada, vuelve a iniciar sesi&oacute;n!";
					}
				}
			} else {
			$result = "Las contrase&ntilde;as no coinciden.";
			$error = "1";
			}
		} else {
		$result = "Los datos introducidos no coinciden con los registrados";
		$error = "1";
		}
	}
} else if($tab == "7"){
	if(isset($_POST['save'])){
	$pass1 = $_POST['password'];
	//Hashes and salts the old password with the user id (in lowercase) --encryption--
	$pass1_hash = HoloHash($pass1, $myrow['name']);
	$day1 = $_POST['day'];
	$month1 = $_POST['month'];
	$year1 = $_POST['year'];
		$formatted_dob = "".$day1."-".$month1."-".$year1."";
	$newpass = $_POST['pass'];
	//Hashes and salts the new password with the user id (in lowercase) --encryption--
	$newpass_hash = HoloHash($newpass, $rawname);
	$newpass_conf = $_POST['confpass'];
		if($pass1_hash == $myrow['password'] && $formatted_dob == $myrow['birth']){
			if($newpass == $newpass_conf){
				if(strlen($newpass) < 6){
				$result = "La contrase&ntilde;a es demasiado corta, m&iacute;nimo 6 caracteres";
				$error = "1";
				} else {
					if(strlen($newpass) > 25){
					$result = "La contrase&ntilde;a es demasiado larga, m&aacute;ximo 25 caracteres";
					$error = "1";
					} else {
					//Updates password --encryption--
					mysql_query("UPDATE users SET password = '".$newpass_hash."' WHERE name = '".$rawname."' and password = '".$rawpass."'") or die(mysql_error());
					$result = "&iexcl;Tu contrase&ntilde;a ha sido cambiada, vuelve a iniciar sesi&oacute;n!";
					}
				}
			} else {
			$result = "Las contrase&ntilde;as no coinciden";
			$error = "1";
			}
		} else {
		$result = "Los datos introducidos no coinciden con los registrados";
		$error = "1";
		}
	}
} else if($tab == "8"){
	if(isset($_POST['save'])){
		mysql_query("UPDATE users SET screen = '".$_POST['screen']."', rea = '".$_POST['rea']."' WHERE id = '".$my_id."'");
		$result = "Actualizado";
	}
}

include('templates/community/subheader.php');
include('templates/community/header.php');

// Save it in a variable to avoid having to check wether this user is HC member or not each time
$hc_member = IsHCMember($my_id);

?>

<div id="container">
	<div id="content">
    <div>
<div class="content">
<div class="habblet-container" style="float:left; width:210px;">
<div class="cbb settings">

<h2 class="title">Mis preferencias</h2>
<div class="box-content">
            <div id="settingsNavigation">
            <ul>
		<?php
		if($tab == "1"){
                echo "<li class='selected'>ASPECTO
                </li>";
		} else {
                echo "<li><a href='account.php?tab=1'>ASPECTO</a>
                </li>";
		}

		if($tab == "2"){
                echo "<li class='selected'>LEMA
                </li>";
		} else {
                echo "<li><a href='account.php?tab=2'>LEMA</a>
                </li>";
		}

		if($tab == "3"){
                echo "<li class='selected'>EMAIL
                </li>";
		} else {
                echo "<li><a href='account.php?tab=3'>EMAIL</a>
                </li>";
		}

		if($tab == "4"){
                echo "<li class='selected'>CONTRASE&Ntilde;A
                </li>";
		} else {
                echo "<li><a href='account.php?tab=4'>CONTRASE&Ntilde;A</a>
                </li>";
		}

		if($tab == "5"){
                echo "<li class='selected'>&iquest;CR&Eacute;DITOS?
                </li>";
		} else {
                echo "<li><a href='account.php?tab=5'>&iquest;CR&Eacute;DITOS?</a>
                </li>";
		}

		if($tab == "6"){
                echo "<li class='selected'>GESTIONAR AMIGOS
                </li>";
		} else {
                echo "<li><a href='account.php?tab=6'>GESTIONAR AMIGOS</a>
                </li>";
		}

		if($tab == "8"){
				echo "<li class='selected'>LOADER
				</li>";
		} else {
				echo "<li><a href='account.php?tab=8'>LOADER</a>
				</li>";
		}
		?>
            </ul>
            </div>
</div></div>
<?php if( !IsHCMember($my_id) ){ ?>
    <div class="cbb habboclub-tryout">
        <h2 class="title">Un&eacute;te al <?php echo $shortname; ?> Club</h2>
        <div class="box-content">
            <div class="habboclub-banner-container habboclub-clothes-banner"></div>
            <p class="habboclub-header">El <?php echo $shortname; ?> Club es exclusivo para VIPs: ventajas, ropa exclusiva y una lista de hasta 600 amigos.</p>
            <p class="habboclub-link"><a href="club.php">S&uacute;mate al <?php echo $shortname; ?> Club &gt;&gt;</a></p>
        </div>
    </div>
<?php } ?>
</div>

<?php if($tab == "1"){ ?>
<div class="habblet-container" style="float:left; width: 560px;">
<div class="cbb clearfix settings">

<h2 class="title">Cambia tu aspecto</h2>
<div class="box-content">

<?php
if(!empty($result)){
	if($error == "1"){
	echo "<div class='rounded rounded-red'>";
	} else {
	echo "<div class='rounded rounded-green'>";
	}
	echo "".$result."<br />
	</div><br />";
}
?>
	<div>&nbsp;</div>

<div id="settings-editor">
Para cambiar tu aspecto necesitas Flash Player de Adobe: <a target="_blank" href="http://www.adobe.com/go/getflashplayer">http://www.adobe.com/go/getflashplayer</a>
</div>

<?php if($hc_member){ ?><div id="settings-wardrobe" style="display: none">
<ol id="wardrobe-slots">
	<li>
		<p id="wardrobe-slot-1" style="background-image: url(<?php echo $slot1_url; ?>)">
	   		<span id="wardrobe-store-1" class="wardrobe-store"></span>
	   		<span id="wardrobe-dress-1" class="wardrobe-dress"></span>
   		</p>
    </li>
	<li>
		<p id="wardrobe-slot-2" style="background-image: url(<?php echo $slot2_url; ?>)">
	   		<span id="wardrobe-store-2" class="wardrobe-store"></span>
	   		<span id="wardrobe-dress-2" class="wardrobe-dress"></span>
   		</p>
    </li>
	<li>
		<p id="wardrobe-slot-3" style="background-image: url(<?php echo $slot3_url; ?>)">
	   		<span id="wardrobe-store-3" class="wardrobe-store"></span>
	   		<span id="wardrobe-dress-3" class="wardrobe-dress"></span>
   		</p>
    </li>
	<li>
		<p id="wardrobe-slot-4" style="background-image: url(<?php echo $slot4_url; ?>)">
	   		<span id="wardrobe-store-4" class="wardrobe-store"></span>
	   		<span id="wardrobe-dress-4" class="wardrobe-dress"></span>
   		</p>
    </li>
	<li>
		<p id="wardrobe-slot-5" style="background-image: url(<?php echo $slot5_url; ?>)">
	   		<span id="wardrobe-store-5" class="wardrobe-store"></span>
	   		<span id="wardrobe-dress-5" class="wardrobe-dress"></span>
   		</p>
    </li>
</ol>

<script type="text/javascript">
<?php if(!empty($slot1['figure'])){ ?>
Wardrobe.add(1, "<?php echo $slot1['figure']; ?>", "<?php echo $slot1['gender']; ?>", true);
$("wardrobe-dress-" + 1).show();
<?php } ?>
<?php if(!empty($slot2['figure'])){ ?>
Wardrobe.add(2, "<?php echo $slot2['figure']; ?>", "<?php echo $slot2['gender']; ?>", true);
$("wardrobe-dress-" + 2).show();
<?php } ?>
<?php if(!empty($slot3['figure'])){ ?>
Wardrobe.add(3, "<?php echo $slot3['figure']; ?>", "<?php echo $slot3['gender']; ?>", true);
$("wardrobe-dress-" + 3).show();
<?php } ?>
<?php if(!empty($slot4['figure'])){ ?>
Wardrobe.add(4, "<?php echo $slot4['figure']; ?>", "<?php echo $slot4['gender']; ?>", true);
$("wardrobe-dress-" + 4).show();
<?php } ?>
<?php if(!empty($slot5['figure'])){ ?>
Wardrobe.add(5, "<?php echo $slot5['figure']; ?>", "<?php echo $slot5['gender']; ?>", true);
$("wardrobe-dress-" + 5).show();
<?php } ?>
L10N.put("profile.figure.wardrobe_replace.title", "&iquest;Reemplazar?");
L10N.put("profile.figure.wardrobe_replace.dialog", "<p\>\n&iquest;Seguro que quieres reemplazar el look guardado por el nuevo?\n</p\>\n\n<p\>\n<a href=\"#\" class=\"new-button\" id=\"wardrobe-replace-cancel\"\><b\>Cancelar</b\><i\></i\></a\>\n<a href=\"#\" class=\"new-button\" id=\"wardrobe-replace-ok\"\><b\>OK</b\><i\></i\></a\>\n</p\>\n\n<div class=\"clear\"\></div\>\n");
L10N.put("profile.figure.wardrobe_invalid_data", "&iexcl;Error! Este look no se puede guardar.");
L10N.put("profile.figure.wardrobe_instructions", "Pulsa las flechas rojas para guardar hasta 5 looks en tu armario. Pulsa la flecha verde para seleccionar un look y guardar los cambios para usarlo.");
Wardrobe.init();
</script>
</div><?php } ?>

<div id="settings-hc" style="display: none">
	<div class="rounded rounded-hcred clearfix">
<a href="club.php" id="settings-hc-logo"></a>
La ropa con el s&iacute;mbolo <img src="./web-gallery/v2/images/habboclub/hc_mini.png" /> es exclusiva para miembros del Club. <a href="club.php">&iexcl;S&uacute;mate ya!</a>
	</div>
</div>

<div id="settings-oldfigure" style="display: none">
	<div class="rounded rounded-lightbrown clearfix">
Tu Habbo necesita colores y ropa. &iexcl;Haz clic en la cabeza para ver el cuerpo!
	</div>
</div>

<form method="post" action="account.php?tab=1" id="settings-form" style="display: none">
<input type="hidden" name="tab" value="1" />
<input type="hidden" name="__app_key" value="HoloCMS" />
<input type="hidden" name="figureData" id="settings-figure" value="<?php echo $mylook1; ?>" />
<input type="hidden" name="newGender" id="settings-gender" value="<?php echo $mysex1; ?>" />
<input type="hidden" name="editorState" id="settings-state" value="" />
<a href="#" id="settings-submit" class="new-button disabled-button"><b>Guardar</b><i></i></a>

<!-- Flash look editor via Ruffle (WASM), isolated in an iframe (Ruffle's polyfill
     conflicts with the CMS's Prototype.js, so it must run on a clean page). -->
<script type="text/javascript">
(function(){
	var c = document.getElementById("settings-editor");
	if(c){
		c.innerHTML = '<iframe src="flash_editor.php?figure=<?php echo urlencode($mylook1); ?>&gender=<?php echo $mysex1; ?>&hc=<?php echo $hc_member ? "1" : "0"; ?>" width="435" height="400" frameborder="0" scrolling="no" style="border:0;overflow:hidden;"></iframe>';
		c.style.textAlign = "center";
	}
	var f = document.getElementById("settings-form"); if(f){ f.style.display = ""; }
	<?php if( $hc_member ){ ?>var w = document.getElementById("settings-wardrobe"); if(w){ w.style.display = ""; }<?php } ?>

	// The editor iframe relays the chosen look via postMessage.
	window.addEventListener("message", function(ev){
		if(ev.data && ev.data.type === "habboFigure" && ev.data.figure){
			var fig = document.getElementById("settings-figure"); if(fig){ fig.value = ev.data.figure; }
			if(ev.data.gender){ var g = document.getElementById("settings-gender"); if(g){ g.value = ev.data.gender; } }
			var btn = document.getElementById("settings-submit");
			if(btn){
				btn.className = "new-button";  // enable Save
				btn.onclick = function(e){ if(e&&e.preventDefault){e.preventDefault();} document.getElementById("settings-form").submit(); return false; };
			}
		}
	}, false);
})();
</script>

</form>

</div>

</div>
</div>
</div>
</div>
    </div>
<?php } else if($tab == "2"){ ?>
    <div class="habblet-container " style="float:left; width: 560px;">
        <div class="cbb clearfix settings">

            <h2 class="title">Editar perfil</h2>
            <div class="box-content">



<form action="account.php?tab=2" method="post">
<input type="hidden" name="tab" value="2" />
<input type="hidden" name="__app_key" value="HoloCMS" />

<?php
if(!empty($result)){
	if($error == "1"){
	echo "<div class='rounded rounded-red'>";
	} else {
	echo "<div class='rounded rounded-green'>";
	}
	echo $result . "<br />
	</div><br />";
}
?>

<h3>Tu lema</h3>

<p>
&iexcl;Escribe tu lema como quieras!
</p>

<p>
<span class="label">Lema:</span>
<input type="text" name="motto" size="32" maxlength="32" value="<?php echo HoloText($motto); ?>" id="avatarmotto" />
</p>

<?php if( IsHCMember($my_id) ){ ?>
<h3>Habbo Club</h3>

<p>Eres miembro del <?php echo $shortname; ?> Club. Tu suscripci&oacute;n termina en <b><?php echo HCDaysLeft($my_id); ?> d&iacute;as</b>. Si quieres renovarla, haz clic <a href='club.php'>aqu&iacute;</a>.</p>
<?php }?>
<div class="settings-buttons">
<input type="submit" value="Guardar" name="save" class="submit" />
</div>

</form>

</div>
</div>
</div>
</div></div>
    </div>
<?php } else if($tab == "3"){ ?>
    <div class="habblet-container " style="float:left; width: 560px;">
        <div class="cbb clearfix settings">

            <h2 class="title">Cambiar e-mail</h2>
            <div class="box-content">

<?php
if(!empty($result)){
	if($error == "1"){
	echo "<div class='rounded rounded-red'>";
	} else {
	echo "<div class='rounded rounded-green'>";
	}
	echo "".$result."<br />
	</div><br />";
}
?>



<form action="account.php?tab=3" method="post" id="emailform">
<input type="hidden" name="tab" value="3" />
<input type="hidden" name="__app_key" value="HoloCMS" />

<div class="settings-step">

	<h4>1.</h4>
	<div class="settings-step-content">

<h3>Introduce tus datos personales:</h3>

<p>
 <label for="currentpassword">Tu contrase&ntilde;a:</label><br />
 <input type="password" size="32" maxlength="32" name="password" id="currentpassword" class="currentpassword " />
</p>

<div>
<div><label for="birthdate">Fecha de nacimiento:</label></div>
<div id="required-birthday" ><select name="day" id="day" class="dateselector"><option value="">D&iacute;a</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select> <select name="month" id="month" class="dateselector"><option value="">Mes</option><option value="1">Enero</option><option value="2">Febrero</option><option value="3">Marzo</option><option value="4">Abril</option><option value="5">Mayo</option><option value="6">Junio</option><option value="7">Julio</option><option value="8">Agosto</option><option value="9">Septiembre</option><option value="10">Octubre</option><option value="11">Noviembre</option><option value="12">Diciembre</option></select> <select name="year" id="year" class="dateselector"><option value="">A&ntilde;o</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option><option value="1919">1919</option><option value="1918">1918</option><option value="1917">1917</option><option value="1916">1916</option><option value="1915">1915</option><option value="1914">1914</option><option value="1913">1913</option><option value="1912">1912</option><option value="1911">1911</option><option value="1910">1910</option><option value="1909">1909</option><option value="1908">1908</option><option value="1907">1907</option><option value="1906">1906</option><option value="1905">1905</option><option value="1904">1904</option><option value="1903">1903</option><option value="1902">1902</option><option value="1901">1901</option><option value="1900">1900</option></select> </div>
</div>

	</div>
</div>
<div class="settings-step">

	<h4>2.</h4>
	<div class="settings-step-content">

<h3>Introduce tu direcci&oacute;n de e-mail actual</h3>

<p>&iexcl;Aseg&uacute;rate de poner la correcta!</p>

<p>
<label for="email">Direcci&oacute;n de e-mail:</label><br />
<input type="text" name="email" id="email" size="32" maxlength="48" value="<?php echo $themail; ?>" />
</p>

<p>
 <input name="directemail" id="directemail" <?php echo $newsletter; ?> type="checkbox"> <label for="directemail">S&iacute;, env&iacute;ame las novedades del hotel por e-mail.</label>
</p>

	</div>
</div>

<div class="settings-buttons">
<input type="submit" value="Guardar" name="save" class="submit" />
</div>

</form>

</div></div></div></div>


</div>
    </div>
<?php } else if($tab == "4"){ ?>
    <div class="habblet-container " style="float:left; width: 560px;">
        <div class="cbb clearfix settings">

            <h2 class="title">Cambiar contrase&ntilde;a</h2>
            <div class="box-content">

<?php
if(!empty($result)){
	if($error == "1"){
	echo "<div class='rounded rounded-red'>";
	} else {
	echo "<div class='rounded rounded-green'>";
	}
	echo "".$result."<br />
	</div><br />";
}
?>

<form action="account.php?tab=4" method="post" id="passwordform">
<input type="hidden" name="tab" value="4" />
<input type="hidden" name="__app_key" value="HoloCMS" />

<div class="settings-step">

	<h4>1.</h4>
	<div class="settings-step-content">

<h3>Introduce tus datos personales</h3>

<p>
 <label for="currentpassword">Tu contrase&ntilde;a actual:</label><br />
 <input type="password" size="32" maxlength="32" name="password" id="currentpassword" class="currentpassword " />
</p>

<div>
<div><label for="birthdate">Fecha de nacimiento:</label></div>
<div id="required-birthday" ><select name="day" id="day" class="dateselector"><option value="">D&iacute;a</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select> <select name="month" id="month" class="dateselector"><option value="">Mes</option><option value="1">Enero</option><option value="2">Febrero</option><option value="3">Marzo</option><option value="4">Abril</option><option value="5">Mayo</option><option value="6">Junio</option><option value="7">Julio</option><option value="8">Agosto</option><option value="9">Septiembre</option><option value="10">Octubre</option><option value="11">Noviembre</option><option value="12">Diciembre</option></select> <select name="year" id="year" class="dateselector"><option value="">A&ntilde;o</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option><option value="1919">1919</option><option value="1918">1918</option><option value="1917">1917</option><option value="1916">1916</option><option value="1915">1915</option><option value="1914">1914</option><option value="1913">1913</option><option value="1912">1912</option><option value="1911">1911</option><option value="1910">1910</option><option value="1909">1909</option><option value="1908">1908</option><option value="1907">1907</option><option value="1906">1906</option><option value="1905">1905</option><option value="1904">1904</option><option value="1903">1903</option><option value="1902">1902</option><option value="1901">1901</option><option value="1900">1900</option></select> </div>
</div>

	</div>
</div>
<div class="settings-step">

	<h4>2.</h4>
	<div class="settings-step-content">

<h3>Elige una nueva contrase&ntilde;a</h3>

<p>Introduce tu nueva contrase&ntilde;a y conf&iacute;rm&aacute;la a continuaci&oacute;n.</p>

<p>
<label for="pass">Nueva contrase&ntilde;a:</label><br />
<input type="password" name="pass" id="password" size="32" maxlength="48" value="" />
</p>

<p>
<label for="confpass">Confirma la contrase&ntilde;a:</label><br/>
<input type="password" name="confpass" id="password" size="32" maxlength="48" value="" />
</p>

	</div>
</div>

<div class="settings-buttons">
<input type="submit" value="Guardar" name="save" class="submit" />
</div>

</form>

</div></div></div></div>


</div>
    </div>
<?php } else if($tab == "5"){ ?>
    <div class="habblet-container " style="float:left; width: 560px;">
        <div class="cbb clearfix settings">

            <h2 class="title">&iquest;Necesitas cr&eacute;ditos?</h2>
            <div class="box-content">
		<h3>&iexcl;Enlace de registro que da cr&eacute;ditos!</h3>
		<p>&iquest;Te est&aacute;s quedando sin cr&eacute;ditos? &iexcl;Hay una soluci&oacute;n!</p>
		<h3>&iquest;C&oacute;mo funciona?</h3>
		<p>Es un enlace que puedes compartir con tus amigos. &iexcl;Sencillo y eficaz!</p>
		<p><b>1. Comparte tu enlace con tu amigo.</b><br />En dos minutos tu amigo se registra y recibes cr&eacute;ditos autom&aacute;ticamente. Tu enlace:<br />
		<input type="text" size="80%" enabled="enabled" value="<?php if(!empty($path)){ echo $path; } ?>register.php?refer=<?php echo $rawname; ?>"></p>
		<p><b>2. &iexcl;Espera a que tu amigo se registre!</b><br />Despu&eacute;s, &iexcl;s&eacute; el m&aacute;s rico del hotel!
		<h3>&iquest;Cu&aacute;nto ganas?</h3>
		<p>Esta es la cantidad de cr&eacute;ditos que ganar&aacute;s:<br />
		<br />
		<b><?php echo $reward; ?></b> cr&eacute;ditos <sup>por registro</sup>
		</p>
	    </div>
	</div>
    </div>
<?php } elseif($tab == "6"){ ?>
<div id="friend-management" class="habblet-container">
                <div class="cbb clearfix settings">
                    <h2 class="title">Gesti&oacute;n de amigos</h2>
                    <div id="friend-management-container" class="box-content">
                        <div id="category-view" class="clearfix">
                            <div id="search-view">
                                Busca un amigo
				                <div id="friend-search" class="friendlist-search">
					                <input type="text" maxlength="32" id="friend_query" class="friend-search-query" />
					                <a class="friendlist-search new-button search-icon" id="friend-search-button"><b><span></span></b><i></i></a>
					            </div>
                            </div>
                            <div id="category-list">
<div id="friends-category-title">
    Categor&iacute;as
</div>

<div class="category-default category-item selected-category" id="category-item-0">Amigos</div>

    <input type="text" maxlength="32" id="category-name" class="create-category" /><div id="add-category-button" class="friendmanagement-small-icons add-category-item add-category"></div>
                            </div>
                        </div>
                        <div id="friend-list" class="clearfix">
<div id="friend-list-header-container" class="clearfix">
    <div id="friend-list-header">
        <div class="page-limit">
            <div class="big-icons friend-header-icon">Amigos
                <br />Mostrar
                30 |
                <a class="category-limit" id="pagelimit-50">50</a> |
                <a class="category-limit" id="pagelimit-100">100</a>
            </div>
        </div>
    </div>
	<div id="friend-list-paging">
			 1 |
    <?php
		$afriendscount = mysql_query("SELECT COUNT(*) FROM messenger_friendships WHERE userid = '1' OR friendid = '1'") or die(mysql_error());
		$friendscount = mysql_result($afriendscount, 0);

		$pages = ceil($friendscount / 30);

		$n = 1;

		while ($n < $pages) {
			$n++;
			echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-".$n."\">".$n."</a> |";
		}

		echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-2\">&gt;&gt;</a>"
	?>

        </div>
    </div>


<form id="friend-list-form">
    <table id="friend-list-table" border="0" cellpadding="0" cellspacing="0">
        <tbody>
            <tr class="friend-list-header">
                <td class="friend-select" />
                <td class="friend-name table-heading">Nombre</td>
                <td class="friend-login table-heading">&Uacute;ltima conexi&oacute;n</td>
                <td class="friend-remove table-heading">Eliminar</td>
            </tr>
           <?php
		   $i = 0;
		   $getem = mysql_query("SELECT * FROM messenger_friendships WHERE userid = '1' OR friendid = '1' LIMIT 30") or die(mysql_error());

		   while ($row = mysql_fetch_assoc($getem)) {
		           $i++;

		           if(IsEven($i)){
		               $even = "odd";
		           } else {
		               $even = "even";
		           }

		           if($row['friendid'] == 1){
		           		$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['userid']."'");
		           } else {
		           		$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['friendid']."'");
		           }

		           $friendrow = mysql_fetch_assoc($friendsql);



printf("   <tr class=\"%s\">
               <td><input type=\"checkbox\" name=\"friendList[]\" value=\"%s\" /></td>
               <td class=\"friend-name\">
                %s
               </td>
               <td class=\"friend-login\" title=\"%s\">%s</td>
               <td class=\"friend-remove\"><div id=\"remove-friend-button-%s\" class=\"friendmanagement-small-icons friendmanagement-remove remove-friend\"></div></td>
           </tr>", $even, $friendrow['id'], $friendrow['name'], $friendrow['lastvisit'], $friendrow['lastvisit'], $friendrow['id']);
		   }
		?>
        </tbody>
    </table>
    <a class="select-all" id="friends-select-all" href="#">Seleccionar todo</a> |
    <a class="deselect-all" href=#" id="friends-deselect-all">Deseleccionar todo</a>
</form>                        </div>
    <script type="text/javascript">
        new FriendManagement({ currentCategoryId: 0, pageListLimit: 30, pageNumber: 1});
    </script>
                        <div id="category-options" class="clearfix">
<select id="category-list-select" name="category-list">
    <option value="0">Amigos</option>
    <option value="1">Amigos de prueba</option>
</select>
<div class="friend-del"><a class="new-button red-button cancel-icon" href="#" id="delete-friends"><b><span></span>Eliminar amigos seleccionados</b><i></i></a></div>
<div class="friend-move"><a class="new-button" href="#" id="move-friend-button"><b><span></span>Volver</b><i></i></a></div>                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script type="text/javascript">

        L10N.put("friendmanagement.tooltip.deletefriends", "&iquest;Est&aacute;s seguro de eliminar estos amigos?\n<div class=\"friendmanagement-small-icons friendmanagement-save friendmanagement-tip-delete\"\>\n    <a class=\"friends-delete-button\" id=\"delete-friends-button\"\>Eliminar</a\>\n</div\>\n<div class=\"friendmanagement-small-icons friendmanagement-remove friendmanagement-tip-cancel\"\>\n    <a id=\"cancel-delete-friends\"\>Cancelar</a\>\n</div\>\n\n");
        L10N.put("friendmanagement.tooltip.deletefriend", "&iquest;Est&aacute;s seguro de eliminar este amigo?\n<div class=\"friendmanagement-small-icons friendmanagement-save friendmanagement-tip-delete\"\>\n    <a id=\"delete-friend-%friend_id%\"\>Eliminar</a\>\n</div\>\n<div class=\"friendmanagement-small-icons friendmanagement-remove friendmanagement-tip-cancel\"\>\n    <a id=\"remove-friend-can-%friend_id%\"\>Cancelar</a\>\n</div\>");
        L10N.put("friendmanagement.tooltip.deletecategory", "&iquest;Est&aacute;s seguro de eliminar esta categor&iacute;a?\n<div class=\"friendmanagement-small-icons friendmanagement-save friendmanagement-tip-delete\"\>\n    <a class=\"delete-category-button\" id=\"delete-category-%category_id%\"\>Eliminar</a\>\n</div\>\n<div class=\"friendmanagement-small-icons friendmanagement-remove friendmanagement-tip-cancel\"\>\n    <a id=\"cancel-cat-delete-%category_id%\"\>Cancelar</a\>\n</div\>");
    </script>

    </div>
    </div>
<?php } else if($tab == "8"){ ?>
    <div class="habblet-container " style="float:left; width: 560px;">
        <div class="cbb clearfix settings">

            <h2 class="title">Loader</h2>
            <div class="box-content">



<form action="account.php?tab=8" method="post">
<input type="hidden" name="tab" value="8" />
<input type="hidden" name="__app_key" value="HoloCMS" />

<?php
if(!empty($result)){
	if($error == "1"){
	echo "<div class='rounded rounded-red'>";
	} else {
	echo "<div class='rounded rounded-green'>";
	}
	echo $result . "<br />
	</div><br />";
}
?>

<h3>Tama&ntilde;o de pantalla</h3>

<p>
<?php
$sqll = mysql_query("SELECT screen,rea FROM users WHERE id = '".$my_id."'");
$roww = mysql_fetch_assoc($sqll);
if($roww['screen'] == "wide"){ ?>
<input type="radio" name="screen" value="wide" checked> Apagado
<br />
<input type="radio" name="screen" value="full"> Encendido
<?php } else { ?>
<input type="radio" name="screen" value="wide"> Apagado
<br />
<input type="radio" name="screen" value="full" checked> Encendido
<?php } ?>
</p>

<h3>P&aacute;gina de reautenticaci&oacute;n</h3>
<p>
Es la p&aacute;gina previa al loader, donde se piden tus datos. &iexcl;Puedes activarla o desactivarla aqu&iacute;!<br />
<?php
if($roww['rea'] == "enabled"){ ?>
<input type="radio" name="rea" value="enabled" checked> Encendido
<br />
<input type="radio" name="rea" value="disabled"> Apagado
<?php } else { ?>
<input type="radio" name="rea" value="enabled"> Encendido
<br />
<input type="radio" name="rea" value="disabled" checked> Apagado
<?php } ?>
</p>

<div class="settings-buttons">
<input type="submit" value="Guardar" name="save" class="submit" />
</div>

</form>

</div>
</div>
</div>
</div></div>
    </div>
<?php } else { ?>
<b>La pesta&ntilde;a existe pero no se encontraron datos. Por favor, informa del problema.</b>
<?php } ?>

<?php

include('templates/community/footer.php');

?>