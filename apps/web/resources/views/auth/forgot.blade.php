@extends('layouts.guest')

@section('title', 'Recuperar contraseña')

@push('head')
    <style type="text/css">
        div.left-column { float: left; width: 50% }
        div.right-column { float: right; width: 49% }
        label { display: block }
        input { width: 98% }
        input.process-button { width: auto; float: right }
    </style>
@endpush

@section('content')
    <div id="process-content">
        <div class="left-column">

            @if (! empty($newPassword))
                <div class="cbb clearfix white">
                    <div class="box-content">
                        <p>
                        <div align="center">
                            <b>&iexcl;Contrase&ntilde;a restablecida!</b><br />
                            Hola <b>{{ $username }}</b>, tu nueva contrase&ntilde;a es:<br />
                            <b>{{ $newPassword }}</b><br />
                            C&aacute;mbiala despu&eacute;s de iniciar sesi&oacute;n.
                        </div>
                        </p>
                    </div>
                </div>
            @elseif (! empty($error))
                <div class="cbb clearfix white">
                    <div class="box-content">
                        <p><div align="center"><b>{!! $error !!}</b></div></p>
                    </div>
                </div>
            @endif

            <div class="cbb clearfix">
                <h2 class="title">&iquest;Has olvidado tu contrase&ntilde;a?</h2>
                <div class="box-content">

                    <p>&iexcl;Sin p&aacute;nico! D&eacute;janos tus datos a continuaci&oacute;n y generaremos una nueva contrase&ntilde;a para tu cuenta.</p>

                    <div class="clear"></div>

                    <form method="post" action="/forgot" id="forgottenpw-form">
                        @csrf
                        <p>
                            <label for="forgottenpw-username">Nombre de usuario</label>
                            <input type="text" name="name" id="forgottenpw-username" value="{{ old('name') }}" />
                        </p>

                        <p>
                            <label for="forgottenpw-email">Direcci&oacute;n e-mail</label>
                            <input type="text" name="email" id="forgottenpw-email" value="{{ old('email') }}" />
                        </p>

                        <p>
                            <input type="submit" value="Solicitar nueva contrase&ntilde;a" name="actionForgot" class="submit process-button" id="forgottenpw-submit" />
                        </p>
                    </form>
                </div>
            </div>

        </div>

        <div class="right-column">
            <div class="cbb clearfix">
                <h2 class="title">&iexcl;Falsa alarma!</h2>
                <div class="box-content">
                    <p>Si recuerdas tu contrase&ntilde;a o tu nombre de Habbo, o has llegado aqu&iacute; por error, haz clic en el enlace de abajo para volver al inicio.</p>
                    <p><a href="/index.php">Volver al inicio &raquo;</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
