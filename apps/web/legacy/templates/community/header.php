<?php

/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright &copy; 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

if (!defined("IN_HOLOCMS")) { header("Location: index.php"); exit; }

if(empty($body_id)){
$body_id = "home";
}

?>
<body id="<?php echo $body_id; ?>" class="<?php if(!$logged_in){ echo "anonymous"; } ?> ">
<div id="overlay"></div>

<div id="header-container">
	<div id="header" class="clearfix">
		<h1><a href="index.php"></a></h1>
       <div id="subnavi">
			<div id="subnavi-user">
                            <?php if($logged_in){ ?>
				<ul>
					<li id="myfriends"><a href="#"><span>Mis amigos</span></a><span class="r"></span></li>
					<li id="mygroups"><a href="#"><span>Mis grupos</span></a><span class="r"></span></li>
					<li id="myrooms"><a href="#"><span>Mis salas</span></a><span class="r"></span></li>
				</ul>
                            <?php } elseif(!$logged_in){ ?>
                                <div class="clearfix">&nbsp;</div>
                                <p>
				   <div id="to-hotel">
					    <a href="client_dp.php" class="new-button green-button" target="client" onclick="HabboClient.openOrFocus(this); return false;"><b>Entrar a <?php echo $shortname; ?></b><i></i></a>
			</div>     
                                </p>
                            <?php } ?>
<?php if($online == "online" && $logged_in){ ?>
				    <div id="to-hotel">
					    <a href="client_dp.php" class="new-button green-button" target="client" onclick="HabboClient.openOrFocus(this); return false;"><b>Entrar a <?php echo $shortname; ?></b><i></i></a>
			</div>     
<?php } elseif($logged_in){ ?>
					<div id="hotel-closed-medium"><?php echo $sitename; ?> est&aacute; cerrado</div>
<?php } ?>
			</div>
        <?php if(!$logged_in){ ?>
            <div id="subnavi-login">
                <form action="index.php?anonymousLogin" method="post" id="login-form">
            		<input type="hidden" name="page" value="<?php echo $pageid; ?>" />
                    <ul>
                        <li>
                            <label for="login-username" class="login-text"><b>Nombre</b></label>
                            <input tabindex="1" type="text" class="login-field" name="username" id="login-username" />
		                    <a href="#" id="login-submit-new-button" class="new-button" style="float: left; display:none"><b>Entrar</b><i></i></a>
                            <input type="submit" id="login-submit-button" value="Entrar" class="submit"/>
                        </li>
                        <li>
                            <label for="login-password" class="login-text"><b>Contrase&ntilde;a</b></label>
                            <input tabindex="2" type="password" class="login-field" name="password" id="login-password" />
                            <input tabindex="3" type="checkbox" name="_login_remember_me" value="true" id="login-remember-me" />
                            <label for="login-remember-me" class="left">Recu&eacute;rdame</label>
                        </li>
                    </ul>
                </form>
                <div id="subnavi-login-help" class="clearfix">
                    <ul>
                        <li class="register"><a href="forgot.php" id="forgot-password"><span>&iquest;Olvidaste tu nombre o contrase&ntilde;a?</span></a></li>
                    	<li><a href="register.php"><span>Reg&iacute;strate gratis</span></a></li>
                    </ul>
                </div>
<div id="remember-me-notification" class="bottom-bubble" style="display:none;">
	<div class="bottom-bubble-t"><div></div></div>
	<div class="bottom-bubble-c">
					Al seleccionar 'Recu&eacute;rdame' seguir&aacute;s con la sesi&oacute;n iniciada en este ordenador hasta que pulses 'Cerrar sesi&oacute;n'. Si es un ordenador p&uacute;blico, no uses esta opci&oacute;n.
	</div>
	<div class="bottom-bubble-b"><div></div></div>
</div>
            </div>
        </div>
		<script type="text/javascript">
			LoginFormUI.init();
			RememberMeUI.init("right");
		</script>
        <?php } else { ?>
            <div id="subnavi-search">
                <div id="subnavi-search-upper">
                <ul id="subnavi-search-links">
                    <li><a href="./help.php" target="habbohelp" onclick="openOrFocusHelp(this); return false">Ayuda</a></li>
					<li><a href="logout.php?reason=site" class="userlink">Cerrar sesi&oacute;n</a></li>
				</ul>
                </div>
               
<form name="tag_search_form" action="user_profile.php" class="search-box clearfix">
					<a id="search-button" class="new-button search-icon" href="#" onclick="$('search-button').up('form').submit(); return false;"><b><span></span></b><i></i></a>
					<input type="text" name="tag" id="search_query" value="Buscar Homepage..." class="search-box-query search-box-onfocus" style="float: right"/>
				</form> 
       
            </div>
        </div>
        <script type="text/javascript">
		L10N.put("purchase.group.title", "Comprar un grupo");
        </script>
        <?php } ?>
<ul id="navi">
        <?php if($pageid > 0 && $pageid < 4 || $pageid == "myprofile" && $logged_in == true){ ?>
        <li class="selected"><strong><?php echo $name; ?></strong><span></span></li>
        <?php } elseif($logged_in == true){ ?>
        <li class=" "><a href="index.php"><?php echo $name; ?></a><span></span></li>
        <?php } elseif($logged_in !== true){ ?>
        <li id="tab-register-now"><a href="register.php" target="_self">Registro</a><span></span></li>
        <?php } ?>

        <?php if($pageid == 4 || $pageid > 3  && $pageid < 6 || $pageid == "profile" || $pageid == "com" || $pageid == "8" || $pageid == "forum" || $pageid == "10"){ ?>
        <li class="selected"><strong>Comunidad</strong><span></span></li>
        <?php } else { ?>
        <li class=" "><a href="community.php">Comunidad</a><span></span></li>
        <?php } ?>

        <?php if($pageid == 6 || $pageid > 5  && $pageid < 8 || $pageid == "collectables" || $pageid == "hand" || $pageid == "pixel"){ ?>
        <li class="selected"><strong>Cr&eacute;ditos</strong><span></span></li>
        <?php } else { ?>
        <li class=" "><a href="credits.php">Cr&eacute;ditos</a><span></span></li>
        <?php } ?>

		<?php if($pageid == "fshop" || $pageid == "bshop"){ ?>
        <li class="selected"><strong>Tienda <?php echo $shortname; ?></strong><span></span></li>
        <?php } else { ?>
        <li class=" "><a href="shop_furni.php">Tienda <?php echo $shortname; ?></a><span></span></li>
        <?php } ?>
		
        <?php if($pageid == "vip" || $pageid == "viplist"){ ?>
        <li class="selected"><strong>VIP Club</strong><span></span></li>
        <?php } else { ?>
        <li class=" "><a href="vip.php">VIP Club</a><span></span></li>
        <?php } ?>

        <?php if($user_rank > 5 && $logged_in == true){ ?>
        <li id="tab-register-now"><a href="./housekeeping/" target="_self"><b>Administraci&oacute;n</b></a><span></span></li>
        <?php } ?>
</ul>

	<div id="habbos-online"><div style="text-align:center; padding:10px;"><span><strong><?php echo $online_count; ?></strong> <?php echo $shortname; ?>s en l&iacute;nea!</span></div></div>
	</div>
</div>

<div id="content-container">

<div id="navi2-container" class="pngbg">
    <div id="navi2" class="pngbg clearfix">

	<ul>
        <?php if($pageid > 0 && $pageid < 4 || $pageid == "myprofile"){ ?>
                <?php if($pageid == "1"){ ?>
                <li class="selected">
    				Inicio
                <?php } else { ?>
                <li class="">
    				<a href="index.php">Inicio</a>
                <?php } ?>
        		</li>

                <?php if($pageid == "myprofile"){ ?>
                <li class="selected">
    				Mi Homepage
                <?php } else { ?>
                <li class="">
    				<a href="user_profile.php?name=<?php echo $rawname; ?>">Mi Homepage</a>
                <?php } ?>
        		</li>

                <?php if($pageid == "2" && $logged_in){ ?>
                <li class="selected">
    				Mis preferencias
                <?php } elseif($logged_in){ ?>
                <li class="">
	    			<a href="account.php">Mis preferencias</a>
                <?php } ?>
    	    	</li>
<?php if($pageid == "hand"){ ?>
                <li class="selected">
    				Vac&iacute;a tu mano
                <?php } else { ?>
                <li class="">
    				<a href="deletehand.php">Vac&iacute;a tu mano</a>
                <?php } ?>
        		</li>

		<li class="last">
				<a href="club.php"><?php echo $shortname; ?> Club</a>
		</li>
        <?php } else if($pageid == 4 || $pageid > 3  && $pageid < 6 || $pageid == "profile" || $pageid == "com" || $pageid == "8" || $pageid == "forum" || $pageid == "10"){ ?>
                <?php if($pageid == "com"){ ?>
                <li class="selected">
    				Comunidad
                <?php } else { ?>
                <li class=" ">
	    			<a href="community.php">Comunidad</a>
                <?php } ?>
                <?php if($pageid == "4"){ ?>
                <li class="selected">
    				Noticias
                <?php } else { ?>
                <li class=" ">
	    			<a href="news.php">Noticias</a>
                <?php } ?>
                <?php if($pageid == "8"){ ?>
                <li class="selected">
    				El equipo
                <?php } else { ?>
                <li class=" ">
	    			<a href="staff.php">El equipo</a>
                <?php } ?>
                <?php if($pageid == "forum"){ ?>
                <li class="selected">
    				Forum
                <?php } else { ?>
                <li class=" ">
	    			<a href="forum.php">Forum</a>
                <?php } ?>
                <?php if($pageid == "5"){ ?>
                <li class="selected">
    				Tags
                <?php } else { ?>
                <li class="">
	    			<a href="tags.php">Tags</a>
                <?php } ?>
	<?php if($pageid == "10"){ ?>
                <li class="selected last">
    				Estad&iacute;sticas
                <?php } else { ?>
                <li class="last">
	    			<a href="statistics.php">Estad&iacute;sticas</a>
                <?php } ?>
        <?php } else if($pageid == "6" || $pageid > 5 && $pageid < 8 || $pageid == "6b" || $pageid == "collectables" || $pageid == "hand" || $pageid == "pixel"){ ?>
                <?php if($pageid == "6"){ ?>
                <li class="selected ">
    				Cr&eacute;ditos
                <?php } else { ?>
                <li class=" ">
	    			<a href="credits.php">Cr&eacute;ditos</a>
                <?php } ?>
                <?php if($pageid == "7"){ ?>
                <li class="selected">
    				<?php echo $shortname; ?> Club
                <?php } else { ?>
                <li class=" ">
	    			<a href="club.php"><?php echo $shortname; ?> Club</a>
                <?php } ?>
                
                <?php if($pageid == "hand"){ ?>
                <li class="selected">
    				Vac&iacute;a tu mano
                <?php } else { ?>
                <li class="">
    				<a href="deletehand.php">Vac&iacute;a tu mano</a>
                <?php } ?>

                <?php if($pageid == "collectables"){ ?>
                <li class="selected">
    				Collectors
                <?php } else { ?>
                <li class="">
	    			<a href="collectables.php">Collectors</a>
                <?php } ?>    

                <?php if($pageid == "pixel"){ ?>
                <li class="selected last">
    				Pixels
                <?php } else { ?>
                <li class="last">
	    			<a href="pixels.php">Pixels</a>
                <?php } ?>
                                    </li>   

<?php } else if($pageid == "fshop" || $pageid == "bshop"){ ?>
                <?php if($pageid == "fshop"){ ?>
                <li class="selected ">
    				Tienda de mobis
                <?php } else { ?>
                <li class=" ">
	    			<a href="shop_furni.php">Tienda de mobis</a>
                <?php } ?>
                <?php if($pageid == "bshop"){ ?>
                <li class="selected last">
    				Tienda de placas
                <?php } else { ?>
                <li class=" last">
	    			<a href="badges.php">Tienda de placas</a>
                <?php } ?>
    	    	</li>

                <?php } else if($pageid == "vip" || $pageid == "viplist"){ ?>
                <?php if($pageid == "vip"){ ?>
                <li class="selected ">
    				VIP Club
                <?php } else { ?>
                <li class=" ">
	    			<a href="vip.php">VIP Club</a>
                <?php } ?>
                <?php if($pageid == "viplist"){ ?>
                <li class="selected last">
    				Lista de VIP
                <?php } else { ?>
                <li class=" last">
	    			<a href="vip_liste.php">Lista de VIP</a>
                <?php } ?>
        <?php } ?>
    	    	</li>

</ul>


	</div>
</div>

<?php if($notify_maintenance){ ?>
<div align="center" style="color: red; background-color: white; border: 1px solid black; padding:2px; width: 75%"><b>Aviso:</b> &iexcl;La web est&aacute; desactivada en este momento!</div>
<?php } ?>