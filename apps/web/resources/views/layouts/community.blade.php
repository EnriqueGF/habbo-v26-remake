<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <title>{{ $shortname }} : @yield('title', $sitename) </title>

    <link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon" />
    <script src="/web-gallery/static/js/visual.js" type="text/javascript"></script>
    <script src="/web-gallery/static/js/libs.js" type="text/javascript"></script>
    <script src="/web-gallery/static/js/common.js" type="text/javascript"></script>
    <script src="/web-gallery/static/js/fullcontent.js" type="text/javascript"></script>
    <script src="/web-gallery/static/js/libs2.js" type="text/javascript"></script>
    <script src="/web-gallery/static/js/group.js" type="text/javascript"></script>
    <script src="/web-gallery/static/js/rooms.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/web-gallery/v2/styles/style.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/v2/styles/buttons.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/v2/styles/boxes.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/v2/styles/tooltips.css" type="text/css" />
    {{-- CSS de maquetación de contenido que carga el legacy (columnas, cajas, perfil, salas, grupos) --}}
    <link rel="stylesheet" href="/web-gallery/v2/styles/welcome.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/v2/styles/personal.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/v2/styles/group.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/v2/styles/rooms.css" type="text/css" />
    <link rel="alternate" type="application/rss+xml" title="{{ $sitename }} News RSS" href="/rss.php"/>
    <script type="text/javascript">
        document.habboLoggedIn = {{ $loggedIn ? 'true' : 'false' }};
        var habboName = "{{ $loggedIn ? $chromeUser->name : '' }}";
        var habboReqPath = "";
        var habboStaticFilePath = "/web-gallery";
        var habboImagerUrl = "/habbo-imaging/";
        var habboPartner = "";
        window.name = "habboMain";
    </script>
    @stack('head')
</head>
@php($active = $activeNav ?? '')
<body id="@yield('bodyId', 'home')" class="{{ $loggedIn ? '' : 'anonymous' }}">
<div id="overlay"></div>

<div id="header-container">
    <div id="header" class="clearfix">
        <h1><a href="/index.php"></a></h1>
        <div id="subnavi">
            <div id="subnavi-user">
                @if($loggedIn)
                    <ul>
                        <li id="myfriends"><a href="#"><span>Mis amigos</span></a><span class="r"></span></li>
                        <li id="mygroups"><a href="#"><span>Mis grupos</span></a><span class="r"></span></li>
                        <li id="myrooms"><a href="#"><span>Mis salas</span></a><span class="r"></span></li>
                    </ul>
                    <div id="to-hotel">
                        <a href="/client_dp.php" class="new-button green-button" target="client"><b>Entrar a {{ $shortname }}</b><i></i></a>
                    </div>
                @else
                    <div class="clearfix">&nbsp;</div>
                    <div id="to-hotel">
                        <a href="/client_dp.php" class="new-button green-button" target="client"><b>Entrar a {{ $shortname }}</b><i></i></a>
                    </div>
                @endif
            </div>

            @if($loggedIn)
                <div id="subnavi-search">
                    <div id="subnavi-search-upper">
                        <ul id="subnavi-search-links">
                            <li><a href="/help" target="habbohelp">Ayuda</a></li>
                            <li><a href="/logout?reason=site" class="userlink">Cerrar sesi&oacute;n</a></li>
                        </ul>
                    </div>
                    <form name="tag_search_form" action="/user_profile.php" class="search-box clearfix">
                        <a id="search-button" class="new-button search-icon" href="#" onclick="$('search-button').up('form').submit(); return false;"><b><span></span></b><i></i></a>
                        <input type="text" name="tag" id="search_query" value="Buscar Homepage..." class="search-box-query search-box-onfocus" style="float: right"/>
                    </form>
                </div>
            @else
                <div id="subnavi-login">
                    <form action="/" method="post" id="login-form">
                        <ul>
                            <li>
                                <label for="login-username" class="login-text"><b>Nombre</b></label>
                                <input tabindex="1" type="text" class="login-field" name="username" id="login-username" />
                                <input type="submit" id="login-submit-button" value="Entrar" class="submit"/>
                            </li>
                            <li>
                                <label for="login-password" class="login-text"><b>Contrase&ntilde;a</b></label>
                                <input tabindex="2" type="password" class="login-field" name="password" id="login-password" />
                            </li>
                        </ul>
                    </form>
                    <div id="subnavi-login-help" class="clearfix">
                        <ul>
                            <li class="register"><a href="/forgot">&iquest;Olvidaste tu contrase&ntilde;a?</a></li>
                            <li><a href="/register">Reg&iacute;strate gratis</a></li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>{{-- /#subnavi: #navi y #habbos-online son hermanos (hijos de #header), como en el legacy --}}

            <ul id="navi">
                @if($loggedIn)
                    <li class="{{ $active === 'home' ? 'selected' : '' }}">
                        @if($active === 'home')<strong>{{ $chromeUser->name }}</strong>@else<a href="/index.php">{{ $chromeUser->name }}</a>@endif<span></span>
                    </li>
                @else
                    <li id="tab-register-now"><a href="/register" target="_self">Registro</a><span></span></li>
                @endif

                <li class="{{ $active === 'community' ? 'selected' : '' }}">
                    @if($active === 'community')<strong>Comunidad</strong>@else<a href="/community">Comunidad</a>@endif<span></span>
                </li>
                <li class="{{ $active === 'credits' ? 'selected' : '' }}">
                    @if($active === 'credits')<strong>Cr&eacute;ditos</strong>@else<a href="/credits">Cr&eacute;ditos</a>@endif<span></span>
                </li>
                <li class="{{ $active === 'shop' ? 'selected' : '' }}">
                    @if($active === 'shop')<strong>Tienda {{ $shortname }}</strong>@else<a href="/shop">Tienda {{ $shortname }}</a>@endif<span></span>
                </li>
                <li class="{{ $active === 'vip' ? 'selected' : '' }}">
                    @if($active === 'vip')<strong>VIP Club</strong>@else<a href="/vip">VIP Club</a>@endif<span></span>
                </li>
                @if($loggedIn && (int) ($chromeUser->rank ?? 0) > 5)
                    <li id="tab-register-now"><a href="/housekeeping/" target="_self"><b>Administraci&oacute;n</b></a><span></span></li>
                @endif
            </ul>

            <div id="habbos-online"><div style="text-align:center; padding:10px;"><span><strong>{{ $onlineCount }}</strong> {{ $shortname }}s en l&iacute;nea!</span></div></div>
    </div>{{-- /#header --}}
</div>{{-- /#header-container --}}

<div id="content-container">
    @hasSection('subnav')
        <div id="navi2-container" class="pngbg">
            <div id="navi2" class="pngbg clearfix">
                @yield('subnav')
            </div>
        </div>
    @endif
    <div id="container">
        <div id="content">
            @yield('content')

            @if(! ($noColumn3 ?? false))
                <div id="column3" class="column">
                    <div class="habblet-container">
                        <div class="ad-container">
                            @foreach($banners as $banner)
                                @if(($banner->advanced ?? '0') === '1')
                                    {!! $banner->html !!}<br />
                                @else
                                    <a target="_blank" href="{{ $banner->url }}"><img src="{{ $banner->banner }}" alt="" /></a><br />
                                    <a target="_blank" href="{{ $banner->url }}">{{ $banner->text }}</a><br />
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div id="footer">
        <p><a href="/index.php" target="_self">Inicio</a> | <a href="/disclaimer" target="_self">T&eacute;rminos de uso</a> | <a href="/privacy" target="_self">Informaci&oacute;n pr&aacute;ctica</a></p>
        <p>Powered by HoloCMS &copy; 2008 Meth0d &amp; Parts by Yifan, sisija.<br/>
        Las marcas, copyright y bases de datos del sitio Habbo, as&iacute; como su contenido, son propiedad de Sulake Inc.<br />
        <strong>HRW Cms, Una Producci&oacute;n <a href="http://habboretroweb.net">HabboRetroWeb</a> Creado por Victor. Todos los Derechos Reservados.</strong></p>
    </div>
</div>

{{-- Diálogos/markup que el legacy emite a nivel de body, tras el footer (p.ej. el foro). --}}
@stack('body_end')

<script type="text/javascript">
try { HabboView.run(); } catch (e) {}
</script>
</body>
</html>
