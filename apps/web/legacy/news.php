<?php
('Content-Type: text/html; charset=utf-8');
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright &copy; 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

$allow_guests = true;

include('core.php');
include('includes/session.php');

$body_id = "news";
$pageid = "4";

// Rather simple. If there's a news ID, check of a article with that ID exists,
// if it does, simply show the article data, otherwise show the news archive.

if(isset($_GET['id'])){
        $news_id = $_GET['id'];
        $main_sql = mysql_query("SELECT * FROM cms_news WHERE num = '".$news_id."'") or die(mysql_error());
        $article_exists = mysql_num_rows($main_sql);
    if($article_exists == "1"){
                $news = mysql_fetch_assoc($main_sql);
                $pagename = "News - " . HoloText($news['title']);
                $archive = "0";
    } else {
                $pagename = "Las noticias";
                $archive = "1";
    }
} elseif(isset($_GET['category'])){
        $pagename = "Categor&iacute;a";
        $archive = "2";
        $cat = FilterText($_GET['category']);
} else {
        $pagename = "Las noticias";
        $archive = "1";
}

include('templates/community/subheader.php');
include('templates/community/header.php');

?>

<div id="container">
    <div id="content">
    <div id="column1" class="column">
                <div class="habblet-container ">
                        <div class="cbb clearfix default ">

                            <h2 class="title">News
                            </h2>
                        <div id="article-archive">
<h2>Las noticias</h2>
<ul>
    <?php
    $get_sub_archive = mysql_query("SELECT num, title, date FROM cms_news ORDER BY num DESC LIMIT 10");
    $count = mysql_num_rows($get_sub_archive);

    if($count > 0){
        while ($row = mysql_fetch_array($get_sub_archive, MYSQL_NUM)) {
            printf("<li><a href='news.php?id=%s'>%s</a> &raquo;</li>", $row[0], HoloText($row[1]));
        }
    } else {
        echo "<br />Sin noticias";
    }
    ?>
</ul>
<h2>&iquest;M&aacute;s noticias?</h2>
<ul>
    <li>
        <a href="news.php" class="article">Ver todas las noticias</a> &raquo;
    </li>
</ul>

</div>


                    </div>
                </div>
                <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>
<div id="column2" class="column">
                <div class="habblet-container ">
                        <div class="cbb clearfix notitle ">

<?php if($archive < 1){ ?>
                        <div id="article-wrapper">
    <h2><?php echo HoloText($news['title']); ?></h2>
    <div class="article-meta">Publicado el <?php echo $news['date']; ?>
        <a href="news.php?category=<?php echo $news['category']; ?>"><?php echo $news['category']; ?></a></div>
    <p class="summary"><?php echo nl2br(HoloText($news['short_story'])); ?></p>

    <div class="article-body">
<p><?php echo html_entity_decode(nl2br(HoloText($news['story'], true))); ?></p>

        <div class="article-body"><a href='user_profile.php?name=<?php echo $news['author']; ?>' target='_self'><img src='./web-gallery/album1/users_online.PNG' alt='User Profile' border='0'></a><b><?php echo $news['author']; ?></b></div>

    </div>
</div>
<?php } elseif($archive == "1"){ ?>
<div id="article-wrapper">
<h2><?php echo "".$shortname." News"; ?></h2>
    <div class="article-meta">Aqu&iacute; est&aacute;n las noticias, de la m&aacute;s reciente a la m&aacute;s antigua.</div>
    <div class="article-body">
        <p>
        <ul>
        <?php
        $get_archive = mysql_query("SELECT num, title, date FROM cms_news ORDER BY num DESC");
        $count = mysql_num_rows($get_archive);

        if($count > 0){
            while ($row = mysql_fetch_array($get_archive, MYSQL_NUM)) {
                printf("<li>%s - <a href='news.php?id=%s'>%s</a></li>", $row[2], $row[0], HoloText($row[1]));
            }
        } else {
            echo "Sin noticias.";
        }
        ?>
        </ul>
        </p>
    </div>
</div>
<?php } elseif($archive > 1){ ?>
<div id="article-wrapper">
<h2><?php echo "Categor&iacute;a"; ?></h2>
    <div class="article-meta">Estas son las &uacute;ltimas 25 noticias de la categor&iacute;a <b><?php echo $cat; ?></b>.</div>
    <div class="article-body">
        <p>
        <ul>
        <?php
        $get_archive = mysql_query("SELECT num, title, date FROM cms_news WHERE category = '".$cat."' ORDER BY num DESC LIMIT 25");

        while ($row = mysql_fetch_array($get_archive, MYSQL_NUM)) {
        printf("<li>%s - <a href='news.php?id=%s'>%s</a></li>", $row[2], $row[0], HoloText($row[1]));
        }
        ?>
        </ul>
        </p>
    </div>
</div>
<?php } ?>



                    </div>
                </div>
                <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>

<div id="column3" class="column">
</div>


<!-- DEBUT DU SCRIPT -->
<script language="JavaScript1.2">
/*
SCRIPT EDITE SUR L'EDITEUR JAVASCRIPT
http://www.editeurjavascript.com
*/

function ejs_nodroit()
    {
    alert('No se permite copiar ni obtener el codigo fuente.');
    return(false);
    }

document.oncontextmenu = ejs_nodroit;
</script>

<!-- FIN DU SCRIPT -->

<?php

include('templates/community/footer.php');

?>