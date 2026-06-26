@extends('layouts.community')

@section('title', $profileUser->name ?? 'Perfil')
@php($activeNav = 'home')

@section('subnav')
    @include('partials.navi2', ['section' => 'home', 'active' => 'homepage'])
@endsection

@section('content')
    @if($profileUser === null)
        {{-- Usuario no encontrado: mensaje amable, sin 500 (espejo de error.php del legacy). --}}
        <div id="column1" class="column">
            <div class="habblet-container">
                <div class="cbb clearfix red">
                    <h2 class="title">P&aacute;gina no encontrada</h2>
                    <div id="notfound-content" class="box-content">
                        <p class="error-text">Lo sentimos, no hemos encontrado a ning&uacute;n usuario con ese nombre.</p>
                        <p class="error-text">&iquest;Buscabas a un amigo? Comp&uacute;ebalo en la
                            <a href="/community">p&aacute;gina de Comunidad</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        @php($uid = (int) $profileUser->id)
        <div id="mypage-wrapper" class="cbb blue">
            <div class="box-tabs-container box-tabs-left clearfix">
                <h2 class="page-owner">{{ $profileUser->name }}</h2>
                <ul class="box-tabs"></ul>
            </div>
            <div id="mypage-content">
                <div id="mypage-bg" class="{{ $background }}">
                    <div id="playground-outer">
                        <div id="playground">

                            @foreach($stickers as $sticker)
                                @php($type = (int) $sticker->type)
                                @php($left = (int) $sticker->x)
                                @php($top = (int) $sticker->y)
                                @php($z = (int) $sticker->z)
                                @php($skin = $sticker->skin ?: 'defaultskin')
                                @php($style = "left: {$left}px; top: {$top}px; z-index: {$z};")

                                @if($type === 3)
                                    {{-- Stickie (nota) --}}
                                    <div class="movable stickie n_skin_{{ $skin }}-c" style="{{ $style }}" id="stickie-{{ $sticker->id }}">
                                        <div class="n_skin_{{ $skin }}">
                                            <div class="stickie-header">
                                                <h3>&nbsp;</h3>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="stickie-body">
                                                <div class="stickie-content">
                                                    <div class="stickie-markup">{!! nl2br(e($sticker->data)) !!}</div>
                                                    <div class="stickie-footer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @elseif($type === 1)
                                    {{-- Sticker (imagen decorativa) --}}
                                    <div class="movable sticker s_{{ $sticker->data }}" style="{{ $style }}" id="sticker-{{ $sticker->id }}"></div>

                                @elseif($type === 2)
                                    @php($widget = $widgetNames[(int) $sticker->subtype] ?? null)

                                    @if($widget === 'ProfileWidget')
                                        {{-- ProfileWidget: figura, motto, online/last visit, placas --}}
                                        <div class="movable widget ProfileWidget" id="widget-{{ $sticker->id }}" style="{{ $style }}">
                                            <div class="w_skin_{{ $skin }}">
                                                <div class="widget-corner" id="widget-{{ $sticker->id }}-handle">
                                                    <div class="widget-headline">
                                                        <h3><span class="header-left">&nbsp;</span><span class="header-middle">MI {{ strtoupper($shortname) }}</span><span class="header-right">&nbsp;</span></h3>
                                                    </div>
                                                </div>
                                                <div class="widget-body">
                                                    <div class="widget-content">
                                                        <div class="profile-info">
                                                            <div class="name" style="float: left">
                                                                <span class="name-text">{{ $profileUser->name }}</span>
                                                            </div>
                                                            <br class="clear" />
                                                            @if((int) ($profileUser->online ?? 0) === 1)
                                                                <img alt="online" src="/web-gallery/images/myhabbo/habbo_online_anim_big.gif" />
                                                            @else
                                                                <img alt="offline" src="/web-gallery/images/myhabbo/habbo_offline_big.gif" />
                                                            @endif
                                                            <div class="birthday text">Creado el:</div>
                                                            <div class="birthday date">{{ $profileUser->hbirth ?? '-' }}</div>
                                                            @if(! empty($profileUser->lastvisit))
                                                                <div class="birthday text">&Uacute;ltima conexi&oacute;n:</div>
                                                                <div class="birthday date">{{ $profileUser->lastvisit }}</div>
                                                            @endif
                                                        </div>
                                                        <div class="profile-figure">
                                                            <img alt="{{ $profileUser->name }}" src="/habbo-imaging/avatarimage.php?figure={{ urlencode($profileUser->figure ?? '') }}&size=b&direction=4&head_direction=4&gesture=sml" />
                                                        </div>
                                                        @if(! empty($profileUser->mission))
                                                            <div class="profile-motto">
                                                                {{ $profileUser->mission }}
                                                                <div class="clear"></div>
                                                            </div>
                                                        @endif
                                                        <br clear="all" style="display: block; height: 1px" />
                                                        <div class="clear"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif($widget === 'GroupsWidget')
                                        {{-- GroupsWidget: grupos del usuario --}}
                                        <div class="movable widget GroupsWidget" id="widget-{{ $sticker->id }}" style="{{ $style }}">
                                            <div class="w_skin_{{ $skin }}">
                                                <div class="widget-corner" id="widget-{{ $sticker->id }}-handle">
                                                    <div class="widget-headline">
                                                        <h3><span class="header-left">&nbsp;</span><span class="header-middle">MIS GRUPOS (<span id="groups-list-size">{{ $groups->count() }}</span>)</span><span class="header-right">&nbsp;</span></h3>
                                                    </div>
                                                </div>
                                                <div class="widget-body">
                                                    <div class="widget-content">
                                                        <div class="groups-list-container">
                                                            <ul class="groups-list">
                                                                @forelse($groups as $group)
                                                                    <li title="{{ $group->name }}" id="groups-list-{{ $sticker->id }}-{{ $group->id }}">
                                                                        <div class="groups-list-icon"><a href="/group_profile.php?id={{ $group->id }}"><img src="/habbo-imaging/badge-fill/{{ $group->badge }}.gif" /></a></div>
                                                                        <div class="groups-list-open"></div>
                                                                        <h4><a href="/group_profile.php?id={{ $group->id }}">{{ $group->name }}</a></h4>
                                                                        <p>
                                                                            Grupo creado:<br />
                                                                            @if((int) $group->is_current === 1)
                                                                                <div class="favourite-group" title="Favorito"></div>
                                                                            @endif
                                                                            @if((int) $group->ownerid === $uid && (int) $group->member_rank > 1)
                                                                                <div class="owned-group" title="Propietario"></div>
                                                                            @elseif((int) $group->member_rank > 1)
                                                                                <div class="admin-group" title="Admin"></div>
                                                                            @endif
                                                                            <b>{{ $group->created }}</b>
                                                                        </p>
                                                                        <div class="clear"></div>
                                                                    </li>
                                                                @empty
                                                                    <li><p>Este usuario no pertenece a ning&uacute;n grupo.</p></li>
                                                                @endforelse
                                                            </ul>
                                                        </div>
                                                        <div class="groups-list-info"></div>
                                                        <div class="clear"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif($widget === 'GuestbookWidget')
                                        {{-- GuestbookWidget: libro de visitas (solo lectura) --}}
                                        @php($entries = $guestbooks[$sticker->id] ?? collect())
                                        <div class="movable widget GuestbookWidget" id="widget-{{ $sticker->id }}" style="{{ $style }}">
                                            <div class="w_skin_{{ $skin }}">
                                                <div class="widget-corner" id="widget-{{ $sticker->id }}-handle">
                                                    <div class="widget-headline">
                                                        <h3><span class="header-left">&nbsp;</span><span class="header-middle">Libro de visitas (<span id="guestbook-size">{{ $entries->count() }}</span>)</span><span class="header-right">&nbsp;</span></h3>
                                                    </div>
                                                </div>
                                                <div class="widget-body">
                                                    <div class="widget-content">
                                                        <div id="guestbook-wrapper" class="gb-public">
                                                            <ul class="guestbook-entries">
                                                                @forelse($entries as $entry)
                                                                    <li id="guestbook-entry-{{ $entry->id }}" class="guestbook-entry">
                                                                        <div class="guestbook-author">
                                                                            <img src="/habbo-imaging/avatarimage.php?figure={{ urlencode($entry->author_figure ?? '') }}&direction=2&head_direction=2&gesture=sml&size=s" alt="{{ $entry->author_name }}" title="{{ $entry->author_name }}" />
                                                                        </div>
                                                                        <div class="guestbook-message">
                                                                            <div><a href="/profile?name={{ urlencode($entry->author_name ?? '') }}">{{ $entry->author_name }}</a></div>
                                                                            <p>{!! nl2br(e($entry->message)) !!}</p>
                                                                        </div>
                                                                        <div class="guestbook-cleaner">&nbsp;</div>
                                                                        <div class="guestbook-entry-footer metadata">{{ $entry->time }}</div>
                                                                    </li>
                                                                @empty
                                                                    <div id="guestbook-empty-notes">Este libro de visitas no tiene entradas.</div>
                                                                @endforelse
                                                            </ul>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif($widget !== null)
                                        {{-- Otros widgets (Rooms/Friends/Trax/HighScores/Badges): cabecera simple. --}}
                                        <div class="movable widget {{ $widget }}" id="widget-{{ $sticker->id }}" style="{{ $style }}">
                                            <div class="w_skin_{{ $skin }}">
                                                <div class="widget-corner" id="widget-{{ $sticker->id }}-handle">
                                                    <div class="widget-headline">
                                                        <h3><span class="header-left">&nbsp;</span><span class="header-middle">{{ strtoupper($widget) }}</span><span class="header-right">&nbsp;</span></h3>
                                                    </div>
                                                </div>
                                                <div class="widget-body">
                                                    <div class="widget-content">
                                                        <div class="clear"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
