<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title>HoloCMS Housekeeping | Login </title>
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Expires" content="Mon, 06 May 1996 04:57:00 GMT" />
<link rel="shortcut icon" href="/housekeeping/favicon.ico" />
<style type='text/css' media="all">
@import url( "/housekeeping/css/hk_style.css" );
</style>
 <script type="text/javascript" src='/housekeeping/js/hk_js.js'></script>
</head>
<body style='background-image:url(/housekeeping/images/blank.gif)'>
<div id='loading-layer' style='display:none'>
	<div id='loading-layer-shadow'>
	   <div id='loading-layer-inner' >
		   <img src='/housekeeping/images/loading_anim.gif' style='vertical-align:middle' border='0' alt='Cargando...' /><br />
		   <span style='font-weight:bold' id='loading-layer-text'>Cargando datos. Por favor, espera...</span>
	   </div>
	</div>
</div>
<div id='ipdwrapper'><!-- IPDWRAPPER -->
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div align='center'>
<div style='width:500px'>
<div class='outerdiv' id='global-outerdiv'><!-- OUTERDIV -->
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr>
 <td id='rightblock'>
 <div>
 <form id='loginform' action="{{ route('hk.login.submit') }}" method='post'>
 @csrf
 <input type='hidden' name='qstring' value='' />
  <table width='100%' cellpadding='0' cellspacing='0' border='0'>
  <tr>
   <td width='200' class='tablerow1' valign='top' style='border:0px;width:200px'>
   <div style='text-align:center;padding-top:20px'>
   	<img src='/housekeeping/images/acp-login-lock.gif' alt='Housekeeping' border='0' />
   </div>
   <br />
   <div class='desctext' style='font-size:10px'>
   <div align='center'><strong>Bienvenido al panel de gesti&oacute;n</strong></div>
   <br />
  	<div style='font-size:9px;color:gray'>Esta p&aacute;gina est&aacute; reservada al equipo de {{ $shortname }}. Si no formas parte del equipo de {{ $shortname }}, por favor identif&iacute;cate.<br /><br />Si has olvidado tu contrase&ntilde;a, <a href='/forgot'>haz clic aqu&iacute;</a> o contacta con el administrador</div>
   </div>
   </td>
   <td width='300' style='width:300px' valign='top'>
	 <table width='100%' cellpadding='5' cellspacing='0' border='0'>
	 <tr>
	  <td colspan='2' align='center'>
		 <br />
		 <img src='/housekeeping/images/holocms-logo.png' alt='BioCMS'><div style='font-weight:bold;color:red'>{!! $msg !!}</div><br />
	  </td>
	 </tr>
	 <tr>		<td align='right'><strong>Usuario</strong></td>	  <td><input style='border:1px solid #AAA' type='text' size='20' name='username' id='namefield' value='{{ old('username') }}' /></td>
	 </tr>
	 <tr>
	  <td align='right'><strong>Contrase&ntilde;a</strong></td>
	  <td><input style='border:1px solid #AAA' type='password' size='20' name='password' value='' /></td>
	 </tr>
	 <tr>
	  <td colspan='2' align='center'><input type='submit' style='border:1px solid #AAA' value='Entrar' /></td>
	 </tr>
	 <tr>
	  <td colspan='2'><br />

	  </td>
	 </tr>
	</table>
   </td>
  </tr>
  </table>
 </form>

 </div>
 </td>
</tr>
</table>
</div><!-- / OUTERDIV -->

</div>
</div>
<script type='text/javascript'>
<!--
  if (top.location != self.location) { top.location = self.location }

  try
  {
  	window.onload = function() { document.getElementById('namefield').focus(); }
  }
  catch(error)
  {
  	alert(error);
  }

//-->
</script>
</body>
</html>
