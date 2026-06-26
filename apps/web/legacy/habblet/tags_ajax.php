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

$key = $_GET['key'];
$tag = FilterText($_POST['tagName']);

$tmp = mysql_query("SELECT * FROM cms_tags WHERE ownerid = '".$my_id."' LIMIT 20") or die(mysql_error());
$tag_num = mysql_num_rows($tmp);

$randomq[] = "&iquest;Cu&aacute;l es tu serie de tele favorita?";
$randomq[] = "&iquest;Qui&eacute;n es tu actor favorito?";
$randomq[] = "&iquest;Qui&eacute;n es tu actriz favorita?";
$randomq[] = "&iquest;Cu&aacute;l es tu pasatiempo favorito?";
$randomq[] = "&iquest;Cu&aacute;l es tu canci&oacute;n favorita?";
$randomq[] = "&iquest;C&oacute;mo te describes?";
$randomq[] = "&iquest;Cu&aacute;l es tu grupo de m&uacute;sica favorito?";
$randomq[] = "&iquest;Cu&aacute;l es tu c&oacute;mic favorito?";
$randomq[] = "&iquest;Cu&aacute;l es tu &eacute;poca del a&ntilde;o favorita?";
$randomq[] = "&iquest;Cu&aacute;l es tu comida favorita?";
$randomq[] = "&iquest;A qu&eacute; deporte juegas?";
$randomq[] = "&iquest;Qui&eacute;n fue tu primer amor?";
$randomq[] = "&iquest;Cu&aacute;l es tu pel&iacute;cula favorita?";
$randomq[] = "&iquest;Gatos, perros o algo m&aacute;s ex&oacute;tico?";
$randomq[] = "&iquest;Cu&aacute;l es tu color favorito?";
srand ((double) microtime() * 1000000);
$chosen = rand(0,count($randomq)-1);

if($key == "remove"){

mysql_query("DELETE FROM cms_tags WHERE tag = '".$tag."' AND ownerid = '".$my_id."' LIMIT 1") or die(mysql_error());
echo "SUCCESS";

} elseif($key == "p_remove"){

echo "<div id=\"profile-tags-container\">\n";

mysql_query("DELETE FROM cms_tags WHERE tag = '".$tag."' AND ownerid = '".$my_id."' LIMIT 1") or die(mysql_error());
$get_tags = mysql_query("SELECT * FROM cms_tags WHERE ownerid = '" . $my_id . "' ORDER BY id LIMIT 25") or die(mysql_error());
$rows = mysql_num_rows($get_tags);

	if($rows > 0){
		while ($row = mysql_fetch_assoc($get_tags)){
			printf("    <span class=\"tag-search-rowholder\">
        <a href=\"tags.php?tag=%s\" class=\"tag-search-link tag-search-link-%s\"
        >%s</a><img border=\"0\" class=\"tag-delete-link tag-delete-link-%s\" onMouseOver=\"this.src='http://images.habbohotel.co.uk/habboweb/21_5527e6590eba8f3fb66348bdf271b5a2/14/web-gallery/images/buttons/tags/tag_button_delete_hi.gif'\" onMouseOut=\"this.src='http://images.habbohotel.co.uk/habboweb/21_5527e6590eba8f3fb66348bdf271b5a2/14/web-gallery/images/buttons/tags/tag_button_delete.gif'\" src=\"http://images.habbohotel.co.uk/habboweb/21_5527e6590eba8f3fb66348bdf271b5a2/14/web-gallery/images/buttons/tags/tag_button_delete.gif\"
        /></span>", $row['tag'], $row['tag'], $row['tag'], $row['tag']);
		}
	} else {
		echo "Sin etiquetas";
	}

echo "\n    <img id=\"tag-img-added\" border=\"0\" src=\"http://images.habbohotel.co.uk/habboweb/21_5527e6590eba8f3fb66348bdf271b5a2/14/web-gallery/images/buttons/tags/tag_button_added.gif\" style=\"display:none\"/>    
</div>";

} elseif($key == "p_list"){

echo "<div id=\"profile-tags-container\">\n";

mysql_query("DELETE FROM cms_tags WHERE tag = '".$tag."' AND ownerid = '".$my_id."' LIMIT 1") or die(mysql_error());
$get_tags = mysql_query("SELECT * FROM cms_tags WHERE ownerid = '" . $my_id . "' ORDER BY id LIMIT 25") or die(mysql_error());
$rows = mysql_num_rows($get_tags);

	if($rows > 0){
		while ($row = mysql_fetch_assoc($get_tags)){
			printf("    <span class=\"tag-search-rowholder\">
        <a href=\"tags.php?tag=%s\" class=\"tag-search-link tag-search-link-%s\"
        >%s</a><img border=\"0\" class=\"tag-delete-link tag-delete-link-%s\" onMouseOver=\"this.src='http://images.habbohotel.co.uk/habboweb/21_5527e6590eba8f3fb66348bdf271b5a2/14/web-gallery/images/buttons/tags/tag_button_delete_hi.gif'\" onMouseOut=\"this.src='http://images.habbohotel.co.uk/habboweb/21_5527e6590eba8f3fb66348bdf271b5a2/14/web-gallery/images/buttons/tags/tag_button_delete.gif'\" src=\"http://images.habbohotel.co.uk/habboweb/21_5527e6590eba8f3fb66348bdf271b5a2/14/web-gallery/images/buttons/tags/tag_button_delete.gif\"
        /></span>", $row['tag'], $row['tag'], $row['tag'], $row['tag']);
		}
	} else {
		echo "Sin etiquetas";
	}

echo "\n    <img id=\"tag-img-added\" border=\"0\" src=\"http://images.habbohotel.co.uk/habboweb/21_5527e6590eba8f3fb66348bdf271b5a2/14/web-gallery/images/buttons/tags/tag_button_added.gif\" style=\"display:none\"/>    
</div>";

} elseif($key == "add"){

$tag = strtolower(FilterText($_POST['tagName']));
$filter = preg_replace("/[^a-z\d]/i", "", $tag);

	if(strlen($tag) < 2 || $filter !== $tag || strlen($tag) > 20){
	echo "invalidtag"; exit;
	} else {
	$check = mysql_query("SELECT * FROM cms_tags WHERE ownerid = '".$my_id."' AND tag = '".$tag."' LIMIT 1") or die(mysql_error());
	$num = mysql_num_rows($check);
		if($num > 0){
		echo "invalidtag"; exit;
		} else {
			if($tag_num > 20){
			echo "invalidtag"; exit;
			} else {
			mysql_query("INSERT INTO cms_tags (ownerid,tag) VALUES ('".$my_id."','".$tag."')");
			echo "valid"; exit;
			}
		}
	}

} elseif($key == "mytagslist"){

echo "   <div class=\"habblet\" id=\"my-tags-list\">\n\n";
            echo "<ul class=\"tag-list make-clickable\"> ";
	while($row = mysql_fetch_assoc($tmp)){
                    printf("<li><a href=\"tags.php?tag=%s\" class=\"tag\" style=\"font-size:10px\">%s</a>\n
                        <a class=\"tag-remove-link\"\n
                        title=\"Eliminar etiqueta\"\n
                        href=\"#\"></a></li>\n", $row['tag'], $row['tag']);
	}
            echo "</ul>";
if($tag_num < 20){
echo "     <form method=\"post\" action=\"tag_ajax.php?key=add\" onsubmit=\"TagHelper.addFormTagToMe();return false;\" >
    <div class=\"add-tag-form clearfix\">
		<a  class=\"new-button\" href=\"#\" id=\"add-tag-button\" onclick=\"TagHelper.addFormTagToMe();return false;\"><b>A&ntilde;adir etiqueta</b><i></i></a>
        <input type=\"text\" id=\"add-tag-input\" maxlength=\"20\" style=\"float: right\"/>
        <em class=\"tag-question\">" . $randomq[$chosen] . "</em>
    </div>
    <div style=\"clear: both\"></div> 
    </form>";
}
echo "    </div>

<script type=\"text/javascript\">

    TagHelper.setTexts({
        tagLimitText: \"Has alcanzado el l&iacute;mite de etiquetas: borra una de tus etiquetas si quieres a&ntilde;adir una nueva.\",
        invalidTagText: \"Etiqueta no v&aacute;lida\",
        buttonText: \"OK\"
    });
        TagHelper.bindEventsToTagLists();

</script>\n";

}
?>

