@php
    $hkAdminName = auth()->user()->name;
    $hkRank = (int) auth()->user()->rank;
    $strRank = $hkRank > 6 ? 'Administrator' : ($hkRank > 5 ? 'Moderator' : '');
    $tab = $tab ?? 0;
@endphp
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title>HoloCMS Housekeeping | @yield('pagename')</title>
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Expires" content="Mon, 06 May 1996 04:57:00 GMT" />
<link rel="shortcut icon" href="/housekeeping/favicon.ico" />
<style type='text/css' media="all">
@import url( "/housekeeping/css/hk_style.css" );
</style>
 <script type="text/javascript" src='/housekeeping/js/hk_js.js'></script>
@stack('head')
</head>
<body>
<div id='loading-layer' style='display:none'>
	<div id='loading-layer-shadow'>
	   <div id='loading-layer-inner' >
		   <img src='/housekeeping/images/loading_anim.gif' style='vertical-align:middle' border='0' alt='Cargando...' /><br />
		   <span style='font-weight:bold' id='loading-layer-text'>Cargando datos. Por favor, espera...</span>
	   </div>
	</div>
</div>
<div id='ipdwrapper'><!-- IPDWRAPPER -->
<!-- TOP TABS -->
<div class='tabwrap-main'>
<div class='tab{{ $tab == 1 ? 'on' : 'off' }}-main'><img src='/housekeeping/images/dashboard.png' style='vertical-align:middle' /> <a href='/housekeeping/dashboard'>Panel</a></div>
<div class='tab{{ $tab == 2 ? 'on' : 'off' }}-main'><img src='/housekeeping/images/system.png' style='vertical-align:middle' /> <a href='/housekeeping/index.php?p=server'>Servidor</a></div>
<div class='tab{{ $tab == 3 ? 'on' : 'off' }}-main'><img src='/housekeeping/images/tools.png' style='vertical-align:middle' /> <a href='/housekeeping/index.php?p=site'>Sitio y Contenido</a></div>
<div class='tab{{ $tab == 5 ? 'on' : 'off' }}-main'><img src='/housekeeping/images/admin.png' style='vertical-align:middle' /> <a href='/housekeeping/index.php?p=users'>Usuarios</a></div>


<div class='logoright'><br /><font size='2' color='black'></font>&nbsp;&nbsp;&nbsp;</div>
</div>
<!-- / TOP TABS -->

<div class='sub-tab-strip'>
    <div class='global-memberbar'>
 Bienvenido, <strong>{{ $strRank }} {{ $hkAdminName }}</strong> [
 <a href='/index.php' target='_blank'>Inicio del sitio</a> &middot;
 <a href='/housekeeping/logout'>CERRAR SESI&Oacute;N</a>
 ]
</div>
	<div class='navwrap'><a href='/housekeeping/dashboard'>{{ $sitename }} Administration</a></div>
</div>
<div class='outerdiv' id='global-outerdiv'><!-- OUTERDIV -->

@yield('content')

<br />
 <div class='copy' align='center'>
	<p>Powered by HoloCMS &copy; 2008 Meth0d &amp; Parts by Yifan, sisija.<br/>
Las marcas, copyright y bases de datos del sitio Habbo, as&iacute; como su contenido, son propiedad de Sulake Inc.<br /><strong>HRW Cms&copy;, una producci&oacute;n de <a href="http://habboretroweb.net">HabboRetroWeb</a> creada por Victor. Todos los derechos reservados.</strong><br /></div>
</div>
</div>
</body>
</html>
