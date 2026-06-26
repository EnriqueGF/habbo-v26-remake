@extends('layouts.community')

@section('title', 'Estadísticas del hotel')
@php($activeNav = 'community')

@section('subnav')
    @include('partials.navi2', ['section' => 'community', 'active' => 'stats'])
@endsection

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix blue">
                <h2 class="title">Estad&iacute;sticas de {{ $sitename }}</h2>
                <div class="habblet box-content">
                    @if($loggedIn)
                        <h3>Acerca de tu cuenta</h3>

                        <i>Tu nombre</i>: {{ $chromeUser->name }}
                        <br /><br />

                        <i>Cr&eacute;ditos</i>: {{ (int) ($chromeUser->credits ?? 0) }} Cr&eacute;ditos (<a href="/transactions.php">Ver transacciones</a>)
                        <br /><br />

                        <i>Tu rango</i>: {{ $rankLabel }}
                        <br /><br />

                        <i>&Uacute;ltima visita</i>: {{ $chromeUser->lastvisit ?? '' }}
                    @endif

                    <h3>Acerca del hotel</h3>

                    <i>Usuarios en l&iacute;nea</i>: {{ $onlineCount }} {{ $shortname }}s en l&iacute;nea.

                    <h3>Acerca del servidor</h3>

                    <i>Versi&oacute;n</i>: v{{ $serverVersion }}
                    <br /><br />
                    <i>Usuarios registrados</i>: {{ $usersCount }}
                    <br /><br />
                    <i>Salas creadas</i>: {{ $roomsCount }}
                        ({{ $publicRooms }} espacio p&uacute;blico)
                    <br /><br />
                    <i>Mobiliario</i>: {{ $furnitureCount }}
                    <br /><br />
                    <i>Grupos</i>: {{ $groupsCount }}
                    <br /><br />
                    <i>Usuarios baneados</i>: {{ $bansCount }}
                    <br /><br />
                    <i>Ecotron</i> : {{ $recycler }}
                    <br /><br />
                    <i>Comercio</i>: {{ $trading }}
                    <br /><br />
                    <i>M&aacute;ximo de conexiones</i>: {{ $maxConnections }}
                    <br /><br />
                </div>
            </div>
        </div>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix blue">
                <h2 class="title">&iquest;Qu&eacute; son las estad&iacute;sticas?</h2>
                <div id="notfound-looking-for" class="box-content">
                    <p><img class="statistics-image" src="/web-gallery/v2/images/hotelicone.gif" align="left" width="60" height="65" />Las estad&iacute;sticas son la informaci&oacute;n global del hotel: salas creadas, usuarios registrados, rango del usuario, etc.</p>
                </div>
            </div>
        </div>

        <div class="habblet-container">
            <div class="cbb clearfix blue">
                <h2 class="title">Informaci&oacute;n sobre el CMS</h2>
                <div id="notfound-looking-for" class="box-content">
                    <br /><u>CMS instalado:</u> HRW Cms&copy;<br /><u>Versi&oacute;n:</u> 2.0<br /><br /><small><i>HRW Cms&copy;, producci&oacute;n de <a href="http://habboretroweb.net">HabboRetroWeb</a>. Todos los derechos reservados.</i></small>
                </div>
            </div>
        </div>
    </div>
@endsection
