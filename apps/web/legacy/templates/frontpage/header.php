<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright � 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind.
+---------------------------------------------------*/

if (!defined("IN_HOLOCMS")) { header("Location: ../../index.php"); exit; }

?>

<body id="frontpage">

<div id="fp-container">
    <div id="header" class="clearfix">
        <h1><a href="index.php"></a></h1>
        <span class="login-register-link">
            <?php echo $locale['newhere']; ?>
            &iquest;Eres nuevo? <a href="./register.php">REG&Iacute;STRATE GRATIS!</a>
        </span>
    </div>
    <div id="content">