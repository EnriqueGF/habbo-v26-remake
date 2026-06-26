<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright � 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind. 
+---------------------------------------------------*/

@include('../core.php');

session_start();

if(session_is_registered(username)){
$username = $_SESSION['username'];
$password = $_SESSION['password'];

$sql = mysql_query("SELECT * FROM users WHERE name = '".$username."' LIMIT 1") or die(mysql_error());
$row = mysql_fetch_assoc($sql);

	if($row['password'] !== $password){
	session_destroy();
	}
}

?>

<html>
<head>
  <title>Redirigiendo...</title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <style type="text/css">body { background-color: #e3e3db; text-align: center; font: 11px Verdana, Arial, Helvetica, sans-serif; } a { color: #fc6204; }</style>
</head>
<body>

<script type="text/javascript">window.location.replace('index.php');</script><noscript><meta http-equiv="Refresh" content="0;URL=index.php"></noscript>

<p class="btn">Si no eres redirigido autom&aacute;ticamente, por favor <a href="index.php" id="manual_redirect_link">haz clic aqu&iacute;</a></p>

</body>
</html>