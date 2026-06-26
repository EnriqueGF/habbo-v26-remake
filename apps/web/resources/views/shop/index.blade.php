@extends('layouts.community')

@section('title', 'Tienda de muebles')
@php($activeNav = 'shop')

@push('head')
    <script src="/web-gallery/static/js/shop_furni.js" type="text/javascript"></script>
@endpush

@section('content')
    <div class="column" style="width: 212px">
        <div class="habblet-container ">
            <div class="cbb clearfix blue ">
                <h2 class="title">Buscar</h2>
                <div id="credits-safety" class="box-content credits-info">
                    <div style="text-align: center">
                        <form action="/shop" method="get">
                            <input type="text" name="search" value="" />
                            <input type="submit" value="Buscar" class="submit" id="submit-button" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="habblet-container ">
            <div class="cbb clearfix blue ">
                <h2 class="title">Categor&iacute;as</h2>
                <div id="credits-safety" class="box-content credits-info">
                    <div class="credit-info-text clearfix">
                        @foreach($categories as $row)
                            @if($search === '' && $category === (int) $row->indexid)
                                <strong>{{ $row->displayname }}</strong><br />
                            @else
                                <a href="/shop?category={{ $row->indexid }}">{{ $row->displayname }}</a><br />
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column" style="width: 527px">
        @if(session('status'))
            <div class="rounded rounded-green">
                {{ session('status') }}
            </div>
        @elseif(session('error'))
            <div class="rounded rounded-red">
                {!! session('error') !!}
            </div>
        @endif

        <div class="habblet-container ">
            <div class="cbb clearfix notitle ">

                @if($search === '')
                    @if($category === 1)
                        <div id="credits-safety" class="box-content credits-info">
                            <div class="credit-info-text clearfix">
                                <center>
                                    <br /><img src="/web-gallery/images/catalogue/headers/Frontpage.gif" />
                                    <br />Selecciona una categor&iacute;a a la izquierda para comprar muebles.
                                </center>
                            </div>
                        </div>
                    @else
                        <div id="credits-safety" class="box-content credits-info">
                            <div class="credit-info-text clearfix">
                                <center>
                                    <img src="/web-gallery/images/catalogue/headers/{{ $currentCategory->displayname ?? '' }}.gif" />
                                </center>
                                @foreach($items as $i => $row)
                                    @php($even = ($i + 1) % 2 === 0 ? 'even' : 'odd')
                                    <li class="{{ $even }}">
                                        <table style="width: 100%">
                                            <tr align="center">
                                                <td style="width: 25%"><img src="/web-gallery/images/catalogue/{{ $row->picture }}" /></td>
                                                <td style="width: 35%"><b>{{ $row->catalogue_name }}</b><br />{{ $row->catalogue_description }}</td>
                                                <td style="width: 15%">{{ $row->catalogue_cost }}</td>
                                                <td style="width: 25%">
                                                    <form method="post" action="{{ route('shop.purchase') }}" style="display:inline">
                                                        @csrf
                                                        <input type="hidden" name="furniID" value="{{ $row->tid }}" />
                                                        <input type="hidden" name="quantity" value="1" />
                                                        <a class="new-button fill" href="#" onclick="this.closest('form').submit();return false;"><b>Comprar</b><i></i></a>
                                                    </form>
                                                </td>
                                            </tr>
                                        </table>
                                    </li>
                                @endforeach
                                <center>
                                    @for($p = 1; $p <= $totalPages; $p++)
                                        @if($p === $page)
                                            <strong>{{ $p }}</strong>@if($p < $totalPages) | @endif
                                        @else
                                            <a href="/shop?category={{ $category }}&page={{ $p }}">{{ $p }}</a>@if($p < $totalPages) | @endif
                                        @endif
                                    @endfor
                                </center>
                            </div>
                        </div>
                    @endif
                @else
                    <div id="credits-safety" class="box-content credits-info">
                        <div class="credit-info-text clearfix">
                            @foreach($items as $i => $row)
                                @php($even = ($i + 1) % 2 === 0 ? 'even' : 'odd')
                                <li class="{{ $even }}">
                                    <table style="width: 100%">
                                        <tr align="center">
                                            <td style="width: 25%"><img src="/web-gallery/images/catalogue/{{ $row->picture }}" /></td>
                                            <td style="width: 35%"><b>{{ $row->catalogue_name }}</b><br />{{ $row->catalogue_description }}</td>
                                            <td style="width: 15%">{{ $row->catalogue_cost }}</td>
                                            <td style="width: 25%">
                                                <form method="post" action="{{ route('shop.purchase') }}" style="display:inline">
                                                    @csrf
                                                    <input type="hidden" name="furniID" value="{{ $row->tid }}" />
                                                    <input type="hidden" name="quantity" value="1" />
                                                    <a class="new-button fill" href="#" onclick="this.closest('form').submit();return false;"><b>Comprar</b><i></i></a>
                                                </form>
                                            </td>
                                        </tr>
                                    </table>
                                </li>
                            @endforeach
                            <center>Resultados de tu b&uacute;squeda.</center>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <script type="text/javascript">
            HabboView.run();
        </script>
        <script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
    </div>
@endsection
