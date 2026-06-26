<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright &copy; 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind.
+---------------------------------------------------*/

if (!defined("IN_HOLOCMS")) { header("Location: ../../index.php"); exit; }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title><?php echo $shortname; ?> : Registro </title>

<script type="text/javascript">
var andSoItBegins = (new Date()).getTime();
</script>

<link rel="shortcut icon" href="favicon.ico" type="image/vnd.microsoft.icon" />
<script src="./web-gallery/static/js/visual.js" type="text/javascript"></script>
<script src="./web-gallery/static/js/domready.js" type="text/javascript"></script>
<script src="./web-gallery/static/js/libs.js" type="text/javascript"></script>
<script src="./web-gallery/static/js/common.js" type="text/javascript"></script>
<script src="./web-gallery/static/js/libs2.js" type="text/javascript"></script>
<link rel="stylesheet" href="./web-gallery/v2/styles/style.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/buttons.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/boxes.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/tooltips.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/process.css" type="text/css" />

<script type="text/javascript">
document.habboLoggedIn = false;
var habboName = null;
var habboReqPath = "";
var habboStaticFilePath = "./web-gallery";
var habboImagerUrl = "/habbo-imaging/";
var habboPartner = "";
window.name = "habboMain";

</script>

<link rel="stylesheet" href="./web-gallery/v2/styles/registration.css" type="text/css">
<script src="./web-gallery/static/js/registration.js" type="text/javascript"></script>

    <script type="text/javascript">
        L10N.put("register.tooltip.name", "Tu nombre puede contener n&uacute;meros, letras y los caracteres -=?!@:.");
        L10N.put("register.tooltip.password", "Tu contrase&ntilde;a debe tener al menos 6 caracteres y puede estar formada por letras o n&uacute;meros.");
        L10N.put("register.error.password_required", "Introduce una contrase&ntilde;a");
        L10N.put("register.error.password_too_short", "Tu contrase&ntilde;a debe tener al menos 6 caracteres");
        L10N.put("register.error.retyped_password_required", "Repite tu contrase&ntilde;a");
        L10N.put("register.error.retyped_password_notsame", "Las contrase&ntilde;as no coinciden");
        L10N.put("register.error.retyped_email_required", "Repite tu direcci&oacute;n de email");
        L10N.put("register.error.retyped_email_notsame", "Los emails no coinciden");
        L10N.put("register.tooltip.namecheck", "Haz clic aqu&iacute; para comprobar si tu nombre est&aacute; disponible");
        L10N.put("register.tooltip.retypepassword", "Repite tu contrase&ntilde;a");
        L10N.put("register.tooltip.personalinfo.disabled", "Elige primero tu nombre de <?php echo $shortname; ?>.");
        L10N.put("register.tooltip.namechecksuccess", "&iexcl;Genial! Tu nombre est&aacute; disponible!");
        L10N.put("register.tooltip.passwordsuccess", "Tu contrase&ntilde;a es segura");
        L10N.put("register.tooltip.passwordtooshort", "La contrase&ntilde;a que has elegido es demasiado corta");
        L10N.put("register.tooltip.passwordnotsame", "Repite tus contrase&ntilde;as.");
        L10N.put("register.tooltip.invalidpassword", "La contrase&ntilde;a que has elegido no es v&aacute;lida");
        L10N.put("register.tooltip.email", "Escribe tu direcci&oacute;n de email. Te recomendamos que pongas la real, por si tienes alg&uacute;n problema despu&eacute;s.");
        L10N.put("register.tooltip.retypeemail", "Repite tu direcci&oacute;n de email");
        L10N.put("register.tooltip.invalidemail", "Por favor, introduce una direcci&oacute;n de email v&aacute;lida.");
        L10N.put("register.tooltip.emailsuccess", "&iexcl;Has introducido un email v&aacute;lido!");
        L10N.put("register.tooltip.emailnotsame", "Tu email no es v&aacute;lido");
        L10N.put("register.tooltip.enterpassword", "Por favor, introduce una contrase&ntilde;a.");
        L10N.put("register.tooltip.entername", "Introduce un nombre con caracteres v&aacute;lidos.");
        L10N.put("register.tooltip.enteremail", "Por favor, introduce una direcci&oacute;n de email");
        L10N.put("register.tooltip.enterbirthday", "Introduce una fecha de nacimiento");
        L10N.put("register.tooltip.acceptterms", "Acepta los t&eacute;rminos de uso");
        L10N.put("register.tooltip.invalidbirthday", "Por favor, introduce una fecha de nacimiento v&aacute;lida");
        L10N.put("register.tooltip.emailandparentemailsame","El email de tu padre/madre y el tuyo no pueden ser el mismo, por favor indica uno diferente.");
        L10N.put("register.tooltip.entercaptcha","Introduce el c&oacute;digo");
        L10N.put("register.tooltip.captchavalid","C&oacute;digo inv&aacute;lido");
        L10N.put("register.tooltip.captchainvalid","C&oacute;digo inv&aacute;lido, vuelve a introducirlo");

        RegistrationForm.parentEmailAgeLimit = -1;
        L10N.put("register.message.parent_email_js_form", "<div\>\n\t<div class=\"register-label\"\>Dado que eres menor de 16 a&ntilde;os y de acuerdo con las directrices del sector, necesitamos la direcci&oacute;n de email de tu padre, madre o tutor.</div\>\n\t<div id=\"parentEmail-error-box\"\>\n        <div class=\"register-error\"\>\n            <div class=\"rounded rounded-blue\"  id=\"parentEmail-error-box-container\"\>\n                <div id=\"parentEmail-error-box-content\"\>\n                    Por favor, introduce tu direcci&oacute;n de email.\n                </div\>\n            </div\>\n        </div\>\n\t</div\>\n\t<div class=\"register-label\"\><label for=\"register-parentEmail-bubble\"\>Email del padre, madre o tutor</label\></div\>\n\t<div class=\"register-label\"\><input type=\"text\" name=\"bean.parentEmail\" id=\"register-parentEmail-bubble\" class=\"register-text-black\" size=\"15\" /\></div\>\n\n\n</div\>");

        RegistrationForm.isCaptchaEnabled = true;
        L10N.put("register.message.captcha_js_form", "<div\>\n\t<div class=\"register-label\"\><img src=\"./captcha/captcha.jpg\" alt=\"\" width=\"200\" height=\"50\" /\></div\>\n\t<div class=\"register-label\"\><label for=\"register-captcha-bubble\"\>Introduce el c&oacute;digo de confirmaci&oacute;n que ves abajo</label\></div\>\n\t<div class=\"register-input\"\><input type=\"text\" name=\"bean.captchaResponse\" id=\"register-captcha-bubble\" class=\"register-text-black\" value=\"\" size=\"15\" /\></div\>\t\n</div\>");

        L10N.put("register.message.age_limit_ban", "<div\>\n<p\>\n Eres demasiado joven para registrarte. Vuelve en unos minutos.\n</p\>\n\n<p style=\"text-align:right\"\>\n<input type=\"button\" class=\"submit\" id=\"register-parentEmail-cancel\" value=\"Cancelar\" onclick=\"RegistrationForm.cancel(\'?ageLimit=true\')\" /\>\n</p\>\n</div\>");
        RegistrationForm.ageLimit = -1;
        HabboView.add(function() { Rounder.addCorners($("register-avatar-editor-title"), 4, 4, "rounded-container"); RegistrationForm.init(true); });
    </script>

<meta name="description" content="<?php echo $sitename; ?> is a virtual world where you can meet and make friends." />
<meta name="keywords" content="<?php echo $shortname; ?>,<?php echo $sitename; ?>,virtual world,play games,enter competitions,make friends" />

<!--[if lt IE 8]>
<link rel="stylesheet" href="./web-gallery/v2/styles/ie.css" type="text/css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" href="./web-gallery/v2/styles/ie6.css" type="text/css" />
<script src="./web-gallery/static/js/pngfix.js" type="text/javascript"></script>
<script type="text/javascript">
try { document.execCommand('BackgroundImageCache', false, true); } catch(e) {}
</script>

<style type="text/css">
body { behavior: url(./web-gallery/csshover.htc); }
</style>
<![endif]-->
<meta name="build" content="21.0.68 - Registration - HoloCMS" />
</head>