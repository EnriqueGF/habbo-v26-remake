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

if(getContent('forum-enabled') !== "1"){ header("Location: index.php"); exit; }
if(!session_is_registered(username)){ exit; }

if($user_rank < 6){ exit; }

?>

<p>Est&aacute;s a punto de eliminar un tema completo. &iquest;Est&aacute;s seguro/a?</p>

<p>
<a href="#" class="new-button" id="discussion-action-cancel"><b>Cancelar</b><i></i></a>
<a href="#" class="new-button" id="discussion-action-ok"><b>Proceder</b><i></i></a>
</p>

<div class="clear"></div>