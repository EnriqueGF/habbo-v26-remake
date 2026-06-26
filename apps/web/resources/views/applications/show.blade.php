@extends('layouts.community')

@section('title', 'Applications')
@php($activeNav = '')

{{-- El legacy usa $pageid="apply": header.php pinta el navi2-container con la lista
     vacía. Reproducimos la barra vacía para igualar la altura del original. --}}
@section('subnav')<ul></ul>@endsection

@section('content')
@if($form)
    <div id="column1" class="column">
        <div class="habblet-container ">
            <div class="cbb clearfix brown ">

                <h2 class="title">Solicitud: {{ $form->name }}
                </h2>
                <div class="habblet box-content">
                @if($submitted)<b>&iexcl;Tu solicitud ha sido enviada!</b>@endif
                <i><b>Nota:</b> No hay verificaci&oacute;n autom&aacute;tica. Si env&iacute;as el formulario incompleto, igualmente se enviar&aacute;. Solo puedes solicitar una vez hasta que sea le&iacute;da.</i><hr><center>El equipo de staff te desea mucha suerte.</center><hr>
                @if($form->introduction !== '')
                <b>Introducci&oacute;n</b><br>
                {!! nl2br(e($form->introduction)) !!}<br><br>
                @endif
                @if($form->requirements !== '')
                <b>Requisitos</b><br>
                {!! nl2br(e($form->requirements)) !!}<br><br>
                @endif
                &iquest;Seguro que quieres solicitar este puesto? S&iacute;, rellena el formulario de abajo.<br><br>

            <form method="post" action="/applications/{{ $form->id }}">
                @csrf
                <table cellspacing="1" cellpadding="1" width="420" border="3">
                    @if($form->username == 1)
                    <tr>
                        <td><b>Nombre de usuario</b><br>
                        <i>&iquest;Cu&aacute;l es tu nombre de usuario?</i></td>

                        <td><input type="text" maxlength="50" name="username" disabled="disabled" value="{{ $user->name }}"></td>
                    </tr>
                    @endif
                    @if($form->realname == 1)
                    <tr>
                        <td><b>Nombre</b><br>
                        <i>&iquest;Cu&aacute;l es tu nombre completo?</i></td>

                        <td><input type="text" maxlength="50" name="realname" value="{{ request('realname') }}"></td>
                    </tr>
                    @endif
                    @if($form->birth == 1)
                    <tr>
                        <td><b>Fecha de nacimiento</b><br>
                        <i>&iquest;Cu&aacute;l es tu fecha de nacimiento?</i></td>

                        <td><input type="text" maxlength="50" name="birth" disabled="disabled" value="{{ $user->birth }}"></td>
                    </tr>
                    @endif
                    @if($form->sex == 1)
                    <tr>
                        <td><b>Sexo</b><br>
                        &iquest;Cu&aacute;l es tu sexo (Hombre/Mujer)?</td>

                        <td><input type="text" maxlength="10" name="sex" value="{{ request('sex') }}"></td>
                    </tr>
                    @endif
                    @if($form->country == 1)
                    <tr>
                        <td><b>Pa&iacute;s</b><br><i>&iquest;En qu&eacute; pa&iacute;s vives?</i></td>

                        <td><input type="text" maxlength="50" name="country" value="{{ request('country') }}"></td>
                    </tr>
                    @endif
                    @if($form->education == 1)
                    <tr>
                        <td><b>Estudios</b><br><i>&iquest;Qu&eacute; nivel de estudios tienes?</i></td>

                        <td><input type="text" maxlength="50" name="education" value="{{ request('education') }}"></td>
                    </tr>
                    @endif
                </table>

                <br>

                <table cellspacing="1" cellpadding="0" width="420" border="3">
                    @if($form->general_information == 1)
                        <td><b>Informaci&oacute;n general</b><br><i>&iquest;Por qu&eacute; te interesa este puesto y por qu&eacute; deber&iacute;amos elegirte?</i><br>
                        <textarea name='general_information' cols='64' rows='5'>{{ request('general_information') }}</textarea></td>
                    @endif
                </table>

                <br>

                <table cellspacing="1" cellpadding="0" width="420" border="3">
                    @if($form->experience == 1)
                        <td><b>Experiencia</b><br><i>&iquest;Tienes experiencia? Si es as&iacute;, cu&eacute;ntanos.</i><br>
                        <textarea name='experience' cols='64' rows='5'>{{ request('experience') }}</textarea></td>
                    @endif
                </table>

                <br>

                <table cellspacing="1" cellpadding="0" width="420" border="3">
                    @if($form->additional_information == 1)
                        <td><b>Informaci&oacute;n adicional</b><br><i>&iquest;Cu&aacute;les son tus aficiones e intereses?</i><br>
                        <textarea name='additional_information' cols='64' rows='5'>{{ request('additional_information') }}</textarea></td>
                    @endif
                </table>
                @if($questions->isNotEmpty())

                <br>

                <table cellspacing="1" cellpadding="0" width="420" border="3">
                        <td><b>Preguntas</b><br><i>A continuaci&oacute;n hay algunas preguntas.</i><br>
                        @foreach($questions as $question)
                        <br><b>{{ $question->text }}</b><br>
                        @foreach($question->options as $option)
                        <input value="{{ $option->id }}" type="{{ $option->sort == 1 ? 'radio' : 'checkbox' }}" name="{{ $option->type }}"> {{ $option->text }}<br>
                        @endforeach
                        @endforeach</td>
                </table>

                <br>
                @endif
                @if($form->show_disclaimer == 1 && $form->disclaimer_text !== '')

                <table cellspacing="1" cellpadding="0" width="420" border="3">
                        <td><b>Aviso legal</b><br><i>Lee el siguiente aviso legal y acuerda con sus t&eacute;rminos.</i><br><br><center>--------------------------------------------------------------------------------</center>
                        <font color="gray">{!! nl2br(e($form->disclaimer_text)) !!}</font><br><center>--------------------------------------------------------------------------------</center><br>
                        <INPUT type=checkbox name="agreement"{{ request()->boolean('agreement') ? ' CHECKED' : '' }}> Acepto este aviso legal.
                        </td>
                </table>
                @endif
                <br>
                <center><input type="submit" name="sumbit" value="&iexcl;Enviar solicitud!"></center>
                </form>
</div>


            </div>
        </div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container ">
            <div class="cbb clearfix blue ">

                <h2 class="title">&iexcl;Aqu&iacute; estamos!
                </h2>
                <div id="notfound-looking-for" class="box-content">
    <p>&iexcl;Aqu&iacute; estamos! Est&aacute;s a punto de comenzar tu solicitud. El equipo de staff te desea mucha suerte.</p>
</div>


            </div>
        </div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container ">
            <div class="cbb clearfix green ">

                <h2 class="title">&iquest;Preguntas?
                </h2>
                <div id="notfound-looking-for" class="box-content">
    <p>Es posible que encuentres preguntas en el formulario. As&iacute; podemos evaluar tus conocimientos. &iexcl;Mucha suerte!</p>
</div>


            </div>
        </div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
    </div>
@else
    <div id="column1" class="column">
        <div class="habblet-container ">
            <div class="cbb clearfix red ">

                <h2 class="title">&iexcl;Esta solicitud est&aacute; cerrada!
                </h2>
                <div id="notfound-content" class="box-content">
    <p class="error-text">Lo sentimos, esta solicitud est&aacute; cerrada o no existe.</p> <img id="error-image" src="/web-gallery/v2/images/error.gif" />
    <p class="error-text">Usa el bot&oacute;n 'Atr&aacute;s' de tu navegador para volver.</p>
</div>


            </div>
        </div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

    </div>
    <div id="column2" class="column">
        <div class="habblet-container ">
            <div class="cbb clearfix green ">

                <h2 class="title">&iquest;Buscabas algo?
                </h2>
                <div id="notfound-looking-for" class="box-content">
    <p><b>&iquest;El grupo o la p&aacute;gina personal de un amigo?</b><br/>
    Mira si aparece en la p&aacute;gina <a href="/community">Comunidad</a>.</p>

    <p><b>&iquest;Salas geniales?</b><br/>
    Explora la lista de <a href="/community">Salas recomendadas</a>.</p>

    <p><b>&iquest;Qu&eacute; les interesa a otros usuarios?</b><br/>
    Echa un vistazo a las <a href="/tags">principales etiquetas</a>.</p>

     <p><b>&iquest;C&oacute;mo obtener cr&eacute;ditos?</b><br/>
    Echa un vistazo a la p&aacute;gina de <a href="/credits">cr&eacute;ditos</a>.</p>
</div>


            </div>
        </div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

    </div>
@endif
@endsection
