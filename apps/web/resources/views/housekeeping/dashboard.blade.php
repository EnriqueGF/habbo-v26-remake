@extends('layouts.housekeeping')

@section('pagename', 'Dashboard')

@section('content')
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr>	<td width='100%' valign='top' id='rightblock'>
	 <div><!-- RIGHT CONTENT BLOCK -->


	 <div style='font-size:30px; padding-left:7px; letter-spacing:-2px; border-bottom:1px solid #EDEDED'>
 <img src="/housekeeping/images/holocms-logo.png"><b>Housekeeping</b>
</div>
<br />
<div id='ipb-get-members' style='border:1px solid #000; background:#FFF; padding:2px;position:absolute;width:120px;display:none;z-index:100'></div>
<!--in_dev_notes-->
<!--in_dev_check-->
<table border='0' width='100%' cellpadding='0' cellspacing='4'>
<tr>
 <td valign='top' width='75%'>
	<table border='0' width='100%' cellpadding='0' cellspacing='0'>
	<tr>
	 <td>
		<div class='homepage_pane_border'>
		 <div class='homepage_section'>Tareas y estad&iacute;sticas</div>
		 <table width='100%' cellspacing='0' cellpadding='4'>
			 <tr>
			  <td width='50%' valign='top'>
			  	<div class='homepage_border'>
 <div class='homepage_sub_header'>Resumen del sistema</div>
 <table width='100%' cellpadding='4' cellspacing='0'>
 <tr>
  <td class='homepage_sub_row' width='60%'><strong>Versi&oacute;n de HRW Cms&copy;</strong> &nbsp;</td>
  <td class='homepage_sub_row' width='40%'><strong>Version 2.0</td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Miembros</strong></td>
  <td class='homepage_sub_row'>
  	{{ $stats['members'] }} (<a href='/housekeeping/index.php?p=onlinelist'>{{ $stats['online'] }}</a> en l&iacute;nea)
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Salas</strong></td>
  <td class='homepage_sub_row'>
  	{{ $stats['rooms'] }}
	(de las cuales {{ $stats['public_rooms'] }} Salas P&uacute;blicas)
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Mobis</strong></td>
  <td class='homepage_sub_row'>
  	{{ $stats['furniture'] }}
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Grupos</strong></td>
  <td class='homepage_sub_row'>
  	{{ $stats['groups'] }}
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Entradas del registro de staff</strong></td>
  <td class='homepage_sub_row'>
  	{{ $stats['stafflog'] }}
  </td>
 </tr>
  <tr>
  <td class='homepage_sub_row'><strong>Baneos activos</strong></td>
  <td class='homepage_sub_row'>
  	<a href='/housekeeping/index.php?p=banlist'>{{ $stats['bans'] }}</a>
  </td>
 </tr>

 </table>
</div>
			  </td>
			  <td width='50%' valign='top'>
			  	<div class='homepage_border'>
 <div class='homepage_sub_header'>Configuraci&oacute;n del servidor</div>
 <table width='100%' cellpadding='4' cellspacing='0'>
 <tr>
  <td class='homepage_sub_row'><strong>Game Port</strong></td>
  <td class='homepage_sub_row'>
  	{{ $server['game_port'] }}
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>&nbsp;&nbsp;&nbsp;&nbsp;- MUS Port</strong></td>
  <td class='homepage_sub_row'>
  	{{ $server['mus_port'] }}
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>M&aacute;ximo de conexiones</strong></td>
  <td class='homepage_sub_row'>
  	{{ $server['max_connections'] }}
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Intercambio activado</strong></td>
  <td class='homepage_sub_row'>
  	{{ $server['trading'] }}
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Reciclador activado</strong></td>
  <td class='homepage_sub_row'>
  	{{ $server['recycler'] }}
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Filtro de palabras activado</strong></td>
  <td class='homepage_sub_row'>
  	{{ $server['wordfilter'] }} ({{ $server['wordfilter_censor'] }})
  </td>
 </tr>
 </table>
</div>
			  </td>		   </tr>
	    </table>
	   </div>
	 </td>
	</tr>
	<tr>
	 <td>&nbsp;</td>
	</tr>
	<tr>
	 <td>
		<div class='homepage_pane_border'>
		 <div class='homepage_section'>Comunicaci&oacute;n</div>
		 <table width='100%' cellspacing='0' cellpadding='4'>
			 <tr>
			  <td valign='top' width='50%'>
			  	<div class='homepage_border'>
					<div class='homepage_sub_header'>Notas</div>
					<br /><div align='center'>
<form method='post' action="{{ route('hk.dashboard') }}">
@csrf
<textarea name='notes' style='background-color:#F9FFA2;border:1px solid #CCC;width:95%;font-family:verdana;font-size:10px' rows='8' cols='25'>{{ $adminNotes }}</textarea>
<div><br /><input type='submit' value='Guardar notas' class='realbutton' /></div>
</form>
</div><br />
				</div>
			  </td>
			  <td width='50%' valign='top'>
			  	<div class='homepage_border'>
 <div class='homepage_sub_header'>{{ $sitename }} Administradores</div>
 <table width='100%' cellpadding='4' cellspacing='0'>
@foreach($admins as $row)
 <tr>
 <td class='tablerow1' align='center'>
	 <strong><div style='font-size:12px'><a href='/user_profile.php?name={{ $row->name }}' target='_blank'>{{ $row->name }}</a></strong> (ID: {{ $row->id }})
</td>
 <td class='tablerow2'>
	<div style='margin-top:6px'><a href='mailto:{{ $row->email }}'>{{ $row->email }}</a></div>
</td>
</tr>
@endforeach
 </table>
</div>
			  </td>
			 </tr>
		 </table>
		</div>
	 </td>
	</tr>

	</table>
 </td>
 <td valign='top' width='25%'>
	<div id='acp-update-wrapper'>
		<div class='homepage_pane_border' id='acp-update-normal'>
		 <div class='homepage_section'>Tienda de descargas HRW Cms&copy;</div>
			<div style='font-size:12px;padding:4px; text-align:center'>
                                	<img src="/housekeeping/logo_store.png"><br>

<br>
<b><u>&Uacute;ltimas novedades para HRW Cms&copy;:</b></u><br><br>

<IFRAME src="http://habboretroweb.net/hrwcms/hrwcmsnews.php" width=500 height=80 scrolling=auto frameborder=0 ></IFRAME><br>
<a href="http://forum.habboretroweb.net/forumdisplay.php?fid=37">Ver m&aacute;s m&oacute;dulos &raquo;</a>

<br><br>
<b><u>Actualizar tu versi&oacute;n:</b></u><br><br>
Est&aacute;s usando la versi&oacute;n: <b><i>2.1</i></b><br>
La &uacute;ltima versi&oacute;n es: <i><IFRAME src="http://habboretroweb.net/hrwcms/version.php" width=100 height=13 scrolling=auto frameborder=0 ></IFRAME></i><br><br>
M&aacute;s informaci&oacute;n en <a href="http://habboretroweb.net">HabboRetroWeb.net</a> !<br>

			</div>
		</div>
		<br />
	</div>
	<div id='acp-update-wrapper'>
		<div class='homepage_pane_border' id='acp-update-normal'>
		 <div class='homepage_section'>&iquest;Hacer una donaci&oacute;n a HabboRetroWeb?</div>
			<div style='font-size:12px;padding:4px; text-align:center'>
				<p>
					Si est&aacute;s satisfecho con nuestro servicio y nuestro CMS, puedes hacernos un <b>DONATIVO</b> en cualquier momento. Recuerda que este CMS llev&oacute; mucho tiempo desarrollarlo y se ofrece de forma gratuita. <i>Para hacer un donativo, <a href="http://habboretroweb.net/don.html">haz clic aqu&iacute;</a>. Gracias.</i>
		</div>

	</div>

		<br />
	</div>
	 </div><!-- / RIGHT CONTENT BLOCK -->
	 </td></tr>
</table>
@endsection
