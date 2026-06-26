@extends('layouts.community')

@section('title', 'Tags')
@section('bodyId', 'tags')
@php($activeNav = 'community')

@section('subnav')
    @include('partials.navi2', ['section' => 'community', 'active' => 'tags'])
@endsection

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container ">
            <div class="cbb clearfix default ">

                <h2 class="title">Etiquetas aleatorias</h2>
                <div id="tag-related-habblet-container" class="habblet box-contents">
                    {{-- Nube de etiquetas: reproducción de tagcloud.php --}}
                    @if($tags->isNotEmpty())
                        @php($quantities = $tags->pluck('quantity')->map(fn ($q) => (int) $q))
                        @php($max = $quantities->max())
                        @php($min = $quantities->min())
                        @php($spread = max(1, $max - $min))
                        @php($step = (200 - 100) / $spread)
                        <ul class="tag-list">
                            @foreach($tags as $tag)
                                @php($size = (int) ceil(100 + (((int) $tag->quantity - $min) * $step)))
                                <li><a href="/tags?tag={{ urlencode(strtolower($tag->tag)) }}" class="tag" style="font-size:{{ $size }}%">{{ trim(strtolower($tag->tag)) }}</a> </li>
                            @endforeach
                        </ul>
                    @else
                        <div>Sin etiquetas.</div>
                    @endif
                </div>

            </div>
        </div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

        <div class="habblet-container ">
            <div class="cbb clearfix default ">

                <h2 class="title">Duelo de etiquetas</h2>
                <div id="tag-fight-habblet-container">
                    <div class="fight-process" id="fight-process">Y el ganador es..</div>
                    <div id="fightForm" class="fight-form">
                        <div class="tag-field-container">1a etiqueta:<br /><input maxlength="30" type="text" class="tag-input" name="tag1" id="tag1"/></div>
                        <div class="tag-field-container">2a etiqueta:<br /><input maxlength="30" type="text" class="tag-input" name="tag2" id="tag2"/></div>
                    </div>
                    <div id="fightResults" class="fight-results">
                        <div class="fight-image">
                            <img src="/web-gallery/images/tagfight/tagfight_start.gif" alt="" name="fightanimation" id="fightanimation" />
                            <a id="tag-fight-button" href="#" class="new-button" onclick="TagFight.init(); return false;"><b>&iexcl;Duelo!</b><i></i></a>
                        </div>
                    </div>
                    <div class="tagfight-preload">
                        <img src="/web-gallery/images/tagfight/tagfight_end_0.gif" width="1" height="1"/>
                        <img src="/web-gallery/images/tagfight/tagfight_end_1.gif" width="1" height="1"/>
                        <img src="/web-gallery/images/tagfight/tagfight_end_2.gif" width="1" height="1"/>
                        <img src="/web-gallery/images/tagfight/tagfight_loop.gif" width="1" height="1"/>
                        <img src="/web-gallery/images/tagfight/tagfight_start.gif" width="1" height="1"/>
                    </div>
                </div>

            </div>
        </div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

        <div class="habblet-container "></div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container ">
            <div class="cbb clearfix default ">

                <h2 class="title">Buscar etiquetas @if($validSearch)({{ $results }})@endif</h2>
                <div id="tag-search-habblet-container">
                    <form name="tag_search_form" action="/tags" class="search-box">
                        <input type="text" name="tag" id="search_query" value="{{ $query ?? '' }}" class="search-box-query" style="float: left"/>
                        <a onclick="$(this).up('form').submit(); return false;" href="#" class="new-button search-icon" style="float: left"><b><span></span></b><i></i></a>
                    </form>
                    @if($results > 0)
                        <br /><p class="search-result-count">1 - {{ $results }} / {{ $results }}</p>
                    @else
                        <p class="search-result-count">Sin resultados.</p>
                    @endif
                    {{--
                        El legacy mostraba aquí un enlace "¿Añadir esta etiqueta?" (?tag=X&add=true)
                        que insertaba en cms_tags. Esa ÚNICA escritura se difiere: requiere sesión y
                        validación (2-20 caracteres alfanuméricos, máx 20 tags/usuario, sin duplicados)
                        y debe rehacerse como POST con @csrf. Solo se muestra la nube y la búsqueda.
                    --}}
                    <p class="search-result-divider"></p>

                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="search-result">
                        <tbody>
                        @if($validSearch && $results > 0)
                            @foreach($taggers as $i => $userdata)
                                <tr class="{{ ($i + 1) % 2 === 0 ? 'even' : 'odd' }}">
                                    <td class="image" style="width:39px;">
                                        <img src="/habbo-imaging/avatarimage.php?figure={{ urlencode($userdata->figure ?? '') }}&size=s&direction=4&head_direction=4&gesture=sml" alt="{{ $userdata->name }}" align="left"/>
                                    </td>
                                    <td class="text">
                                        <a href="/user_profile.php?name={{ urlencode($userdata->name) }}" class="result-title">{{ $userdata->name }}</a><br/>
                                        <span class="result-description">{{ $userdata->mission }}</span>
                                        <ul class="tag-list">
                                            @foreach($userdata->user_tags as $userTag)
                                                <li><a href="/tags?tag={{ urlencode(strtolower($userTag)) }}" class="tag" style="font-size:10px">{{ strtolower($userTag) }}</a> </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
    </div>
@endsection
