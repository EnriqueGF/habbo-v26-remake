@extends('layouts.community')

@section('title', 'Applications')
@php($activeNav = '')

{{-- El legacy usa $pageid="apply": header.php pinta el navi2-container con la lista
     vacía (ningún item coincide). Reproducimos esa barra vacía para que el contenido
     arranque a la misma altura que el original. --}}
@section('subnav')<ul></ul>@endsection

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container ">
            <div class="cbb clearfix brown ">

                <h2 class="title">Solicitudes
                </h2>
                <div class="habblet box-content">
&iquest;Quieres ser miembro del staff? A continuaci&oacute;n puedes ver las solicitudes abiertas y cerradas. En total hay <b>{{ $open->count() }}</b> solicitudes abiertas y <b>{{ $closed->count() }}</b> cerradas. <i>Si no hay solicitudes abiertas, int&eacute;ntalo m&aacute;s tarde.</i><br><br><b>Solicitudes abiertas:</b><br>@forelse($open as $form)<a href="/applications/{{ $form->id }}">{{ $form->name }}</a><br>@empty<i>&iexcl;No hay solicitudes abiertas!</i>@endforelse
 <br><b>Solicitudes cerradas:</b><br>@forelse($closed as $form){{ $form->name }}<br>@empty<i>&iexcl;No hay solicitudes cerradas!</i><br>@endforelse<br>Saludos,<br>El equipo de staff
</div>


            </div>
        </div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

    </div>
    <div id="column2" class="column">
        <div class="habblet-container ">
            <div class="cbb clearfix blue ">

                <h2 class="title">&iquest;Quieres ser miembro del staff?
                </h2>
                <div id="notfound-looking-for" class="box-content">
    <p>Si quieres ser miembro del equipo de staff, rellena una solicitud. La leeremos y te diremos si eres aceptado. &iexcl;Es muy sencillo y solo lleva unos minutos!</p>
</div>


            </div>
        </div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
    </div>


    <div id="column2" class="column">
        <div class="habblet-container ">
            <div class="cbb clearfix green ">

                <h2 class="title">&iexcl;Aceptado! :D
                </h2>
                <div id="notfound-looking-for" class="box-content">
    <p>&iexcl;Si eres aceptado, enhorabuena! Pronto recibir&aacute;s tu placa y permisos. &iexcl;Todo el equipo te desea mucho &eacute;xito!</p>
</div>


            </div>
        </div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
    </div>


    <div id="column2" class="column">
        <div class="habblet-container ">
            <div class="cbb clearfix red ">

                <h2 class="title">&iquest;No aceptado? :O :(
                </h2>
                <div id="notfound-looking-for" class="box-content">
    <p>Es posible que no seas aceptado o que no recibas respuesta. Si no eres aceptado, int&eacute;ntalo m&aacute;s tarde con una mejor solicitud. Si no recibes respuesta, quiz&aacute;s a&uacute;n no ha sido le&iacute;da.</p>
</div>


            </div>
        </div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
    </div>
@endsection
