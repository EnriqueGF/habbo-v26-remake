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
    <link rel="stylesheet" href="/web-gallery/v2/styles/style.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/v2/styles/buttons.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/v2/styles/boxes.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/v2/styles/tooltips.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/v2/styles/process.css" type="text/css" />
    @stack('head')
</head>
<body id="@yield('bodyId', 'landing')" class="process-template">
<div id="overlay"></div>

<div id="container">
    <div class="cbb process-template-box clearfix">
        <div id="content">
            <div id="header" class="clearfix">
                <h1><a href="/index.php"></a></h1>
                <ul class="stats">
                    <li class="stats-online"><span class="stats-fig">{{ $onlineCount }}</span> &iexcl;Usuarios conectados!</li>
                    <li class="stats-visited"><img src="/web-gallery/v2/images/{{ $onlineStatus }}.gif" alt="Server Status" border="0"></li>
                </ul>
            </div>

            @yield('content')

            <div id="footer">
                <p><a href="/index.php" target="_self">Inicio</a> | <a href="/disclaimer" target="_self">T&eacute;rminos de uso</a> | <a href="/privacy" target="_self">Informaci&oacute;n pr&aacute;ctica</a></p><br>
                <p>Powered by HoloCMS &copy; 2008 Meth0d &amp; Parts by Yifan, sisija.<br/>
                Las marcas, copyright y bases de datos del sitio Habbo, as&iacute; como su contenido, son propiedad de Sulake Inc.<br />
                <strong>HRW Cms, Una Producci&oacute;n <a href="http://habboretroweb.net">HabboRetroWeb</a> Creado por Victor. Todos los Derechos Reservados.</strong></p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
try { HabboView.run(); } catch (e) {}
</script>
</body>
</html>
