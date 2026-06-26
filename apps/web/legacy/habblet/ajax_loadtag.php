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

if($_GET['sp'] == "plain"){
include('core.php');
echo "<div class=\"habblet box-content\">";
} elseif(!defined("IN_HOLOCMS")){ exit; }

$result = mysql_query("SELECT tag, COUNT(id) AS quantity FROM cms_tags GROUP BY tag ORDER BY quantity DESC LIMIT 30");
$number = mysql_num_rows($result);

if($number > 0){
echo "	    <li id=\"category-1\" class=\"category-tags full\">";
	for($i=0;($array[$i] = @    mysql_fetch_array($result, MYSQL_ASSOC))!="";$i++)
        {
            $row[] = $array[$i];
        }
	sort($row);
	$i = -1;
	while($i <> $number){
		$i++;
		$tag = $row[$i]['tag'];
		$count = $row[$i]['quantity'];
		$tags[$tag] = $count;
	}
		
		$max_qty = max(array_values($tags));
		$min_qty = min(array_values($tags));
		$spread = $max_qty - $min_qty;

		if($spread == 0){ $spread = 1; }

		$step = (200 - 100)/($spread);

		foreach($tags as $key => $value){
		    $size = 100 + (($value - $min_qty) * $step);
		    $size = ceil($size);
		    //echo "<li><a href=\"tags.php?tag=".strtolower($key)."\" class=\"tag\" style=\"font-size:".$size."%\">".trim(strtolower($key))."</a> </li>\n";
			echo "<a href=\"#\"><span>".trim(strtolower($key))."</span></a>\n";
		}

echo "</ul>";
} else {
echo "<div>No hay etiquetas.</div>";
}
?>