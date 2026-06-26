@extends('layouts.community')

@section('title', 'Comunidad')
@php($activeNav = 'community')

@section('subnav')
    @include('partials.navi2', ['section' => 'community', 'active' => 'comunidad'])
@endsection

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix red">
                <div class="box-tabs-container clearfix">
                    <h2>Salas del momento</h2>
                    <ul class="box-tabs"></ul>
                </div>
                <div id="tab-0-2-content">
                    <div id="rooms-habblet-list-container-h105" class="recommendedrooms-lite-habblet-list-container">
                        <ul class="habblet-list">
                            @forelse($rooms as $i => $room)
                                @php($even = $i % 2 === 0 ? 'even' : 'odd')
                                @php($max = max(1, (int) ($room->visitors_max ?? 1)))
                                @php($now = max(1, (int) ($room->visitors_now ?? 0)))
                                @php($pct = ($now / $max) * 100)
                                @php($fill = $pct >= 99 ? 5 : ($pct > 65 ? 4 : ($pct > 32 ? 3 : ($pct > 0 ? 2 : 1))))
                                <li class="{{ $even }}">
                                    <span class="clearfix enter-room-link room-occupancy-{{ $fill }}" title="Ir a la sala" roomid="{{ $room->id }}">
                                        <span class="room-enter">Entrar</span>
                                        <span class="room-name">{{ $room->name }}</span>
                                        <span class="room-description">{{ $room->description }}</span>
                                        <span class="room-owner">Visitantes: <b>{{ (int) ($room->visitors_now ?? 0) }}</b></span>
                                        <span class="room-owner">Creador: <a href="/user_profile.php?name={{ urlencode($room->owner) }}">{{ $room->owner }}</a></span>
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

        <div class="habblet-container">
            <div class="cbb clearfix activehomes">
                <h2 class="title">{{ $shortname }}s aleatorios - &iexcl;Haz clic!</h2>
                <div id="homes-habblet-list-container" class="habblet-list-container">
                    <img class="active-habbo-imagemap" src="/web-gallery/v2/images/activehomes/transparent_area.gif" width="435px" height="230px" usemap="#habbomap" alt="" />

                    @foreach($users as $i => $user)
                        <div id="active-habbo-data-{{ $i }}" class="active-habbo-data">
                            <div class="active-habbo-data-container">
                                <div class="active-name {{ (int) ($user->online ?? 0) === 1 ? 'online' : 'offline' }}">{{ $user->name }}</div>
                                Habbo creado el: {{ $user->hbirth }}
                                <p class="moto">{{ $user->mission }}</p>
                            </div>
                        </div>
                        <input type="hidden" id="active-habbo-url-{{ $i }}" value="/user_profile.php?name={{ urlencode($user->name) }}" />
                        <input type="hidden" id="active-habbo-image-{{ $i }}" class="active-habbo-image" value="/habbo-imaging/avatarimage.php?figure={{ urlencode($user->figure ?? '') }}&size=b&direction=3&head_direction=3&gesture=sml" />
                    @endforeach

                    <div id="placeholder-container">
                        @for($p = 0; $p < 18; $p++)
                            <div id="active-habbo-image-placeholder-{{ $p }}" class="active-habbo-image-placeholder"></div>
                        @endfor
                    </div>
                </div>

                <map id="habbomap" name="habbomap">
                    @php($coords = [
                        '55,53,95,103', '120,53,160,103', '185,53,225,103', '250,53,290,103', '315,53,355,103', '380,53,420,103',
                        '28,103,68,153', '93,103,133,153', '158,103,198,153', '223,103,263,153', '288,103,328,153', '353,103,393,153',
                        '55,153,95,203', '120,153,160,203', '185,153,225,203', '250,153,290,203', '315,153,355,203', '380,153,420,203',
                    ])
                    @foreach($coords as $idx => $c)
                        <area id="imagemap-area-{{ $idx }}" shape="rect" coords="{{ $c }}" href="#" alt="" />
                    @endforeach
                </map>
                <script type="text/javascript">
                    var activeHabbosHabblet = new ActiveHabbosHabblet();
                    document.observe("dom:loaded", function () { activeHabbosHabblet.generateRandomImages(); });
                </script>
            </div>
        </div>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container news-promo">
            <div class="cbb clearfix notitle">
                <div id="newspromo">
                    @php($top = $news->first())
                    <div id="topstories">
                        @if($top)
                            <div class="topstory" style="background-image: url({{ $top->topstory }})">
                                <h4>&Uacute;ltima noticia</h4>
                                <h3><a href="/news?id={{ $top->num }}">{{ $top->title }}</a></h3>
                                <p class="summary">{{ $top->short_story }}</p>
                                <p><a href="/news?id={{ $top->num }}">Leer m&aacute;s &raquo;</a></p>
                            </div>
                        @else
                            <div class="topstory">
                                <h4>&Uacute;ltima noticia</h4>
                                <h3>Sin noticias</h3>
                            </div>
                        @endif
                    </div>
                    <ul class="widelist">
                        @foreach($news->slice(1) as $i => $item)
                            <li class="{{ $i % 2 === 0 ? 'odd' : 'even' }}">
                                <a href="/news?id={{ $item->num }}">{{ $item->title }}</a>
                                <div class="newsitem-date">{{ $item->date }}</div>
                            </li>
                        @endforeach
                        <li class="last"><a href="/news">M&aacute;s noticias &raquo;</a></li>
                    </ul>
                </div>
                <script type="text/javascript">
                    document.observe("dom:loaded", function () { NewsPromo.init(); });
                </script>
            </div>
        </div>

        <div class="habblet-container">
            <div class="cbb clearfix red">
                <h2 class="title">A los usuarios les gusta..</h2>
                <div class="habblet box-content">
                    @if($tags->isNotEmpty())
                        @php($quantities = $tags->pluck('quantity')->map(fn ($q) => (int) $q))
                        @php($max = $quantities->max())
                        @php($min = $quantities->min())
                        @php($spread = max(1, $max - $min))
                        @php($step = (200 - 100) / $spread)
                        <ul class="tag-list">
                            @foreach($tags as $tag)
                                @php($size = (int) ceil(100 + (((int) $tag->quantity - $min) * $step)))
                                <li><a href="/tags.php?tag={{ urlencode(strtolower($tag->tag)) }}" class="tag" style="font-size:{{ $size }}%">{{ trim(strtolower($tag->tag)) }}</a> </li>
                            @endforeach
                        </ul>
                    @else
                        <div>Sin etiquetas.</div>
                    @endif
                    <div class="tag-search-form">
                        <form name="tag_search_form" action="/tags.php" class="search-box">
                            <input type="text" name="tag" id="search_query" value="" class="search-box-query" style="float: left" />
                            <a onclick="$(this).up('form').submit(); return false;" href="#" class="new-button search-icon" style="float: left"><b><span></span></b><i></i></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
