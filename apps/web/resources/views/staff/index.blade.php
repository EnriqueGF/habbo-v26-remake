@extends('layouts.community')

@section('title', 'El equipo')
@php($activeNav = 'community')

@section('subnav')
    @include('partials.navi2', ['section' => 'community', 'active' => 'staff'])
@endsection

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container ">
            <div class="cbb clearfix blue">

                <h2 class="title">
                    Informaci&oacute;n sobre los rangos
                </h2>
                <div id="notfound-looking-for" class="box-content">
                    <p><i>{{ $sitename }} est&aacute; dirigido por un equipo de Staffs y moderadores. Estos se encargan de la gesti&oacute;n del hotel y de la animaci&oacute;n.
                        </i><br>
                        <br>
                        <img src="/c_images/Badges/ADM.gif" alt="" align="left"><b>Los Staffs</b> son los responsables principales...
                        <br>

                        <img src="/c_images/Badges/ADM.gif" alt="" align="left"><b>Los Moderadores</b> moderan el hotel. Se encargan de la justicia y recorren las salas para verificar que se cumplan las normas. Pueden expulsar a cualquier {{ $shortname }} que no respete las reglas.
                        <br>
                        <br>
                        <img src="/c_images/Badges/HBA.gif" alt="" align="left"><b>Los Golds y Silvers</b> ayudan a los Staffs y Moderadores en sus tareas.
                        <br><br>
                        <br>
                        <img src="/c_images/Badges/XXX.gif" alt="" align="left"><b>Los X's</b> son usuarios normales que ayudan a los nuevos y responden preguntas de la comunidad.
                        <br>
                        <br>

                        <u>Todo el equipo tiene una placa para cada rango, para que los puedas identificar en el hotel.</u></p>
                </div>

            </div>
        </div>

        <div class="habblet-container ">
            <div class="cbb clearfix green">

                <h2 class="title">&iquest;Unirte al equipo?</h2>

                <div id="notfound-looking-for" class="box-content">
                    Para unirte al equipo tendr&aacute;s que tener paciencia y esperar los nuevos recrutamientos que se anunciar&aacute;n en las noticias.
                </div>

            </div>
        </div>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container ">
            <div class="cbb clearfix brown ">

                <h2 class="title"><b>El equipo</b></h2>
                <div id="notfound-looking-for" class="box-content"></div>
                <div class="habblet box-content">
                    <div class="clearfix red ">

                        @foreach($teams as $team)
                            <br>
                            <center><h2 class="title">{{ $team['heading'] }}</h2></center>
                            @forelse($team['members'] as $member)
                                @php($mission = trim((string) $member->mission) !== '' ? $member->mission : $team['default_mission'])
                                @php($online = $member->isOnline())
                                @php($onlineImg = $online ? 'online_anim' : 'offline')
                                @php($onlineCaption = $online ? '&iexcl;Conectado!' : 'Desconectado')
                                @php($badge = $member->currentBadge())
                                @php($groupBadge = $member->currentGroupBadge())
                                @php($groupId = $groupBadge !== null ? $member->currentGroupId() : null)
                                <p><center>
                                    <img src="/habbo-imaging/avatarimage.php?figure={{ urlencode($member->figure ?? '') }}&size=b&action=wlk,crr=9&direction=2&head_direction=3&gesture=sml" alt="{{ $member->name }}" align="center" /><br/>
                                    <b><a href="/user_profile.php?name={{ urlencode($member->name) }}">{{ $member->name }}</a></b>&nbsp;<img src="/web-gallery/v2/images/habbo_{{ $onlineImg }}.gif" title="{!! $onlineCaption !!}" alt="{!! $onlineCaption !!}" border="0"><br />
                                    <i>{{ $mission }}</i><br />
                                    <br />
                                    <i><b><u>R</u></b>ango</i>: {!! $team['label'] !!}<br />
                                    &Uacute;ltima visita: {{ $member->lastvisit }}<br />
                                    <br />
                                    @if($badge !== null)
                                        {{-- URL de insignia personal igual que el legacy ($cimagesurl . $badgesurl . badge) --}}
                                        <img src="http://images.habbohotel.co.uk/c_images/album1584/{{ $badge }}.gif" />
                                    @endif
                                    @if($groupBadge !== null)
                                        <a href="/group_profile.php?id={{ $groupId }}"><img src="/habbo-imaging/badge.php?badge={{ urlencode($groupBadge) }}"></a>
                                    @endif
                                    <br /><br />
                                </p></center>
                            @empty
                                {!! $team['empty'] !!}
                            @endforelse
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
