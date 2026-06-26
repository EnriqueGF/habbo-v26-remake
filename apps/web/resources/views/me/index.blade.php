@extends('layouts.community')

@section('title', $chromeUser->name)
@php($activeNav = 'home')

{{-- CSS/JS específicos de esta página que carga el legacy me.php (caja de mensajes, HC). --}}
@push('head')
    <link rel="stylesheet" href="/web-gallery/v2/styles/minimail.css" type="text/css" />
    <script src="/web-gallery/static/js/minimail.js" type="text/javascript"></script>
    <script src="/web-gallery/static/js/habboclub.js" type="text/javascript"></script>
@endpush

{{-- Barra de segundo nivel (#navi2), idéntica al legacy. El layout la envuelve en
     #navi2-container/#navi2 y solo la muestra si la vista define este section. --}}
@section('subnav')
    <ul>
        <li class="selected">
            Inicio
        </li>

        <li class="">
            <a href="/profile?name={{ urlencode($user->name) }}">Mi Homepage</a>
        </li>

        <li class="">
            <a href="/account">Mis preferencias</a>
        </li>

        <li class="">
            <a href="/deletehand.php">Vac&iacute;a tu mano</a>
        </li>

        <li class="last">
            <a href="/club">{{ $shortname }} Club</a>
        </li>
    </ul>
@endsection

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container ">

            <div id="new-personal-info" style="background-image:url(/web-gallery/v2/images/personal_info/hotel_views/htlview_br.png)" />
            <div id="enter-hotel">
                <div class="open">
                    <a href="/client_dp.php" target="client" onclick="openOrFocusHabbo(this); return false;">Entrar {{ $shortname }}<i></i></a>
                    <b></b>
                </div>
            </div>

            <div id="habbo-plate">
                <a href="/account?tab=1">
                    <img alt="{{ $user->name }}" src="/habbo-imaging/avatarimage.php?figure={{ urlencode($user->figure ?? '') }}&size=b&direction=4&head_direction=3&gesture=sml" width="64" height="110" />
                </a>
            </div>

            <div id="habbo-info">
                <div id="motto-container" class="clearfix"><strong>{{ $user->name }}:</strong><div><span title="&iquest;C&oacute;mo est&aacute;s hoy?">{{ $user->mission !== '' ? $user->mission : '¿Cómo estás hoy?' }}</span><p style="display: none"><input type="text" length="30" name="motto" value="{{ $user->mission }}"/></p></div></div><div id="motto-links" style="display: none"><a href="#" id="motto-cancel">Cancelar</a></div></div>
                <ul id="link-bar" class="clearfix">
                    <li class="change-looks"><a href="/account?tab=1">Cambia tu look &raquo;</a></li>
                    <li class="credits">
                        <a href="/credits">{{ (int) ($user->credits ?? 0) }}</a> cr&eacute;ditos
                    </li>
                    <li class="club">
                        <a href="/club">Un&eacute;te al {{ $shortname }} Club &raquo;</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <img src="/web-gallery/v2/images/pixellogo.png"><a href="/credits">{{ (int) ($user->tickets ?? 0) }}</a> P&iacute;xeles
                    </li>
                </ul>

                <div id="habbo-feed">
                    <ul id="feed-items">
                        @foreach($alerts as $alert)
                            @php($heading = (int) $alert->type === 2 ? 'Mensaje del '.$shortname.' Staff' : 'Notificación')
                            <li id="feed-item-{{ $alert->id }}" class="contributed">
                                <form method="post" action="/me/feed/remove" style="display:inline">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $alert->id }}" />
                                    <button type="submit" class="remove-feed-item" title="Eliminar notificaci&oacute;n">Eliminar notificaci&oacute;n</button>
                                </form>
                                <div>
                                    <b>{{ $heading }}</b><br />
                                    {!! nl2br(e(trim((string) $alert->alert))) !!}
                                </div>
                            </li>
                        @endforeach

                        <li class="small" id="feed-lastlogin">
                            &Uacute;ltima conexi&oacute;n:
                            {{ $user->lastvisit }}</li>


                    </ul>
                </div>

                <p class="last"></p>
            </div>
            <div class="habblet-container ">
                <div class="cbb clearfix orange ">
                    <h2 class="title">Destacados
                    </h2>
                    <div id="hotcampaigns-habblet-list-container">
                        <ul id="hotcampaigns-habblet-list">
                        </ul>
                    </div>
                </div>
            </div>


        </div>

        <div class="habblet-container minimail" id="mail">
            <div class="cbb clearfix blue ">

                <h2 class="title">Mis mensajes
                </h2>
                <div id="minimail">
                    <div class="minimail-contents">
                        <a href="#" class="new-button compose"><b>Nuevo</b><i></i></a>
                        <div class="clearfix labels nostandard">
                            <ul class="box-tabs">
                                <li class="selected"><a href="#" label="inbox">Bandeja</a><span class="tab-spacer"></span></li>
                                <li ><a href="#" label="sent">Enviados</a><span class="tab-spacer"></span></li>
                                <li ><a href="#" label="trash">Papelera</a><span class="tab-spacer"></span></li>
                            </ul>
                        </div>
                        <div id="message-list" class="label-inbox">
                            <div class="new-buttons clearfix">
                                <div class="labels inbox-refresh"><a href="#" class="new-button green-button" label="inbox" style="float: left; margin: 0"><b>Actualizar</b><i></i></a></div>
                            </div>

                            <div style="clear: both; height: 1px"></div>
                            <div class="navigation">
                                <div class="unread-selector"><input type="checkbox" class="unread-only" /> no le&iacute;dos</div>
                                @if($messageCount > 0)
                                    <p class="messages-count">
                                        Tienes <strong>{{ $messageCount }}</strong> mensaje(s).
                                    </p>
                                @else
                                    <p class="no-messages">
                                        No hay mensajes
                                    </p>
                                @endif
                                <div class="progress"></div>
                            </div>

                            <div class="navigation">
                                <div class="progress"></div>
                            </div>
                        </div>
                    </div>
                    <div id="message-compose-wait"></div>
                    <form style="display: none" id="message-compose">
                        <div>Para</div>
                        <div id="message-recipients-container" class="input-text" style="width: 426px; margin-bottom: 1em">
                            <input type="text" value="" id="message-recipients" />
                            <div class="autocomplete" id="message-recipients-auto">
                                <div class="default" style="display: none;">Escribe el nombre de tu amigo:</div>
                                <ul class="feed" style="display: none;"></ul>
                            </div>
                        </div>
                        <div>Asunto<br/>
                            <input type="text" style="margin: 5px 0" id="message-subject" class="message-text" maxlength="100" tabindex="2" />
                        </div>
                        <div>Mensaje<br/>
                            <textarea style="margin: 5px 0" rows="5" cols="10" id="message-body" class="message-text" tabindex="3"></textarea>
                        </div>
                        <div class="new-buttons clearfix">
                            <a href="#" class="new-button preview"><b>Vista previa</b><i></i></a>
                            <a href="#" class="new-button send"><b>Enviar</b><i></i></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="habblet-container ">
            <div class="cbb clearfix default ">
                <div class="box-tabs-container clearfix">
                    <h2>{{ $shortname }}s</h2>
                    <ul class="box-tabs">

                        <li id="tab-0-3-2" class="selected"><a href="#">Busca {{ $shortname }}s</a><span class="tab-spacer"></span></li>
                    </ul>
                </div>
                <div id="tab-0-3-2-content" >
                    <div class="habblet-content-info">
                        <a name="habbo-search">Escribe el nombre de un {{ $shortname }} para visitar su perfil.</a>
                    </div>
                    <div id="habbo-search-error-container" style="display: none;"><div id="habbo-search-error" class="rounded rounded-red"></div></div>
                    <br clear="all"/>
                    <div id="avatar-habblet-list-search">
                        <form name="habbo_search_form" action="/profile" style="display:inline">
                            <input type="text" name="tag" id="avatar-habblet-search-string"/>
                            <a href="#" onclick="$(this).up('form').submit(); return false;" id="avatar-habblet-search-button" class="new-button"><b>Buscar</b><i></i></a>
                        </form>
                    </div>
                    <br clear="all"/>
                    <div id="avatar-habblet-content">
                        <div id="avatar-habblet-list-container" class="habblet-list-container">
                            <ul class="habblet-list">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="habblet-container ">
            <div class="cbb clearfix blue ">
                <div class="box-tabs-container clearfix">
                    <h2>Grupos</h2>
                    <ul class="box-tabs">
                        <li id="tab-2-1"><a href="#">Grupos aleatorios</a><span class="tab-spacer"></span></li>
                        <li id="tab-2-2" class="selected"><a href="#">Mis grupos</a><span class="tab-spacer"></span></li>
                    </ul>
                </div>
                <div id="tab-2-2-content" >
                    <div id="groups-habblet-info" class="habblet-content-info">
                        &iexcl;Descubre los grupos de tus amigos y crea el tuyo!
                    </div>
                    <div id="groups-habblet-list-container" class="habblet-list-container groups-list">
                        <ul class="habblet-list two-cols clearfix">
                            @foreach($myGroups as $i => $group)
                                @php($pos = $i % 2 === 0 ? 'left' : 'right')
                                @php($oddeven = (int) ($i / 2) % 2 === 0 ? 'even' : 'odd')
                                <li class="{{ $oddeven }} {{ $pos }}" style="background-image: url(/habbo-imaging/badge.php?badge={{ urlencode($group->badge ?? '') }})">
                                    <a class="item" href="/group_profile.php?id={{ $group->id }}">{{ $group->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="habblet-button-row clearfix"><a class="new-button" id="purchase-group-button" href="/group_create.php"><b>Crear un grupo</b><i></i></a></div>
                    </div>
                    <div id="groups-habblet-group-purchase-button" class="habblet-list-container"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container news-promo">
            <div class="cbb clearfix notitle ">
                @php($top = $news->first())
                <div id="newspromo">
                    <div id="topstories">
                        <div class="topstory" style="background-image: url({{ $top->topstory ?? '' }})">
                            <h4>&Uacute;ltima noticia <a href="/rss.php"><img src="/web-gallery/v2/images/holo/feed-icon.gif" alt="" border="0"/></a></h4>
                            @if($top)
                                <h3><a href="/news?id={{ $top->num }}">{{ $top->title }}</a></h3>
                                <p class="summary">
                                    {{ $top->short_story }}
                                </p>
                                <p>
                                    <a href="/news?id={{ $top->num }}">Leer m&aacute;s &raquo;</a>
                                </p>
                            @else
                                <h3><a href="/news?id=">Noticia no encontrada</a></h3>
                                <p class="summary">
                                    Este art&iacute;culo no existe.
                                </p>
                                <p>
                                    <a href="/news?id=">Leer m&aacute;s &raquo;</a>
                                </p>
                            @endif
                        </div>
                        <div id="topstories-nav" style="display: none"><a href="#" class="prev">&laquo; Anterior</a><span>1</span> / 2<a href="#" class="next">Siguiente &raquo; </a></div>
                    </div>
                    <ul class="widelist">
                        @forelse($news->slice(1) as $i => $item)
                            <li class="{{ $i % 2 === 0 ? 'even' : 'odd' }}">
                                <a href="/news?id={{ $item->num }}">{{ $item->title }}</a><div class="newsitem-date">{{ $item->date }}</div>
                            </li>
                        @empty
                            <li class="even">
                                <a href="/news?id=">Noticia no encontrada</a><div class="newsitem-date">Este art&iacute;culo no existe.</div>
                            </li>
                        @endforelse
                        <li class="last"><a href="/news">Todas las noticias &raquo;</a></li>
                    </ul>
                </div>
                <script type="text/javascript">
                    document.observe("dom:loaded", function() { NewsPromo.init(); });
                </script>
            </div>
        </div>

        <div class="habblet-container ">
            <div class="cbb clearfix blue ">
                <div class="box-tabs-container clearfix">
                    <h2>&iexcl;Hola {{ $user->name }}!</h2>
                    <ul class="box-tabs">
                    </ul>
                </div>
                <div id="tab-2-2-content" >
                    <div id="groups-habblet-info" class="habblet-content-info">
                        <div id="invitation-link-container">
                            <h3>&iexcl;Invita a tus amigos y gana cr&eacute;ditos!</h3>
                            <div class="copytext">
                                <p>Desde ahora puedes compartir tu enlace y ganar cr&eacute;ditos. &iquest;C&oacute;mo hacerlo?</p>
                            </div>
                        </div>
                    </div>
                    <div class="habblet-button-row clearfix"><a class="new-button" href="/account?tab=5"><b>&iexcl;Invita a tus amigos!</b><i></i></a></div>
                </div>
            </div>
        </div>

        <div class="habblet-container ">
            <div class="cbb clearfix blue ">

                <h2 class="title">Recomendados
                </h2>
                <div id="promogroups-habblet-list-container" class="habblet-list-container groups-list">
                    <ul class="habblet-list two-cols clearfix">
                        @foreach($recommendedGroups as $i => $group)
                            @php($even = $i % 2 === 0 ? 'even right' : 'even left')
                            <li class="{{ $even }}" style="background-image: url(/habbo-imaging/badge-fill/{{ urlencode($group->badge ?? '') }}.gif)">
                                @if((int) ($group->roomid ?? 0) !== 0)
                                    <a href="/client_dp.php?forwardId=2&amp;roomId={{ $group->roomid }}" onclick="HabboClient.roomForward(this, '{{ $group->roomid }}', 'private'); return false;" target="client" class="group-room"></a>
                                @endif
                                <a class="item" href="/group_profile.php?id={{ $group->id }}">{{ $group->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="habblet-container ">
            <div class="cbb clearfix green ">
                <div class="box-tabs-container clearfix">
                    <h2>Tags</h2>
                    <ul class="box-tabs">
                        <li id="tab-3-1"><a href="#">A los usuarios les gusta..</a><span class="tab-spacer"></span></li>
                        <li id="tab-3-2" class="selected"><a href="#">Mis etiquetas</a><span class="tab-spacer"></span></li>
                    </ul>
                </div>
                <div id="tab-3-2-content" >
                    <div id="my-tag-info" class="habblet-content-info">
                        @if($tags->count() >= 20)
                            Has alcanzado el m&aacute;ximo de etiquetas.
                        @elseif($tags->isEmpty())
                            &iexcl;No tienes etiquetas, puedes a&ntilde;adir una ahora!
                        @else
                            &iexcl;Puedes a&ntilde;adir m&aacute;s etiquetas!
                        @endif
                    </div>
                    <div class="box-content">
                        <div class="habblet" id="my-tags-list">
                            @if($tags->isNotEmpty())
                                <ul class="tag-list make-clickable">
                                    @foreach($tags as $tag)
                                        <li><a href="/tags.php?tag={{ urlencode(strtolower($tag->tag)) }}" class="tag" style="font-size:10px">{{ trim(strtolower($tag->tag)) }}</a>
                                            <a class="tag-remove-link" title="Eliminar etiqueta" href="#"></a></li>
                                    @endforeach
                                </ul>
                            @endif
                            @if($tags->count() < 20)
                                <form method="post" action="/tags_ajax.php?key=add" onsubmit="TagHelper.addFormTagToMe();return false;" >
                                    <div class="add-tag-form clearfix">
                                        <a class="new-button" href="#" id="add-tag-button" onclick="TagHelper.addFormTagToMe();return false;"><b>A&ntilde;adir</b><i></i></a>
                                        <input type="text" id="add-tag-input" maxlength="20" style="float: right"/>
                                        <em class="tag-question">&iquest;Cu&aacute;l es tu actor favorito?</em>
                                    </div>
                                    <div style="clear: both"></div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="habblet-container ">
            <div class="cbb clearfix green ">
                <div class="box-tabs-container clearfix">
                    <h2>Salas aleatorias..</h2>
                    <ul class="box-tabs">
                    </ul>
                </div>
                <div id="tab-0-2-content" >
                    <div id="rooms-habblet-list-container-h105" class="recommendedrooms-lite-habblet-list-container">
                        <ul class="habblet-list">
                            @forelse($rooms as $i => $room)
                                @php($even = $i % 2 === 0 ? 'even' : 'odd')
                                @php($max = max(1, (int) ($room->visitors_max ?? 1)))
                                @php($now = max(0, (int) ($room->visitors_now ?? 0)))
                                @php($pct = ($now / $max) * 100)
                                @php($fill = $pct >= 99 ? 5 : ($pct > 65 ? 4 : ($pct > 32 ? 3 : ($pct > 0 ? 2 : 1))))
                                <li class="{{ $even }}">
                                    <span class="clearfix enter-room-link room-occupancy-{{ $fill }}" title="Ir a la sala" roomid="{{ $room->id }}">
                                        <span class="room-enter">Entrar</span>
                                        <span class="room-name">{{ $room->name }}</span>
                                        <span class="room-description">{{ $room->description }}</span>
                                        <span class="room-owner">Creador: <a href="/profile?name={{ urlencode($room->owner) }}">{{ $room->owner }}</a></span>
                                    </span>
                                </li>
                            @empty
                                <li class="even"><span class="clearfix"><span class="room-name">Sin salas activas</span></span></li>
                            @endforelse
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
