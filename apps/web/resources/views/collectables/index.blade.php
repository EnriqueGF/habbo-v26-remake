@extends('layouts.community')

@section('title', 'Collectors')
@php($activeNav = 'credits')

@push('head')
    <script src="/web-gallery/static/js/credits.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/web-gallery/v2/styles/collectibles.css" type="text/css" />
@endpush

@section('subnav')
    @include('partials.navi2', ['section' => 'credits', 'active' => 'collectables'])
@endsection

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container" id="collectible-current">
            <div class="cbb clearfix gray">
                <h2 class="title">Coleccionable del mes</h2>
                <div id="collectible-current-content" class="clearfix">
                    <div id="collectibles-current-img" style="background-image: url({{ $current->image_large ?? '' }})"></div>
                    <h4>{!! $current ? nl2br(e($current->title)) : '' !!}</h4>
                    <p>{{ date('F') }} {{ date('o') }}</p>
                    <p class="last">{!! $current ? nl2br(e($current->description)) : '' !!}</p>
                    @if($loggedIn)
                        <p id="collectibles-purchase">
                            <a href="#" class="new-button collectibles-purchase-current"><b>Comprar</b><i></i></a>
                            <span class="collectibles-timeleft">Tiempo restante: <span id="collectibles-timeleft-value"></span></span>
                        </p>
                    @endif
                </div>
                <script type="text/javascript">
                    L10N.put("collectibles.purchase.title", "Confirmar compra de coleccionable");
                    L10N.put("time.days", "{0}j");
                    L10N.put("time.hours", "{0}h");
                    L10N.put("time.minutes", "{0}min");
                    L10N.put("time.seconds", "{0}s");
                    Collectibles.init({{ $timeLeft }});
                </script>
            </div>
        </div>

        <div class="habblet-container">
            <div class="cbb clearfix red">
                <h2 class="title">Coleccionables</h2>
                <ul id="collectibles-list">
                    @foreach($collectables as $i => $row)
                        <li class="{{ $i % 2 === 0 ? 'odd' : 'even' }} clearfix">
                            <div class="collectibles-prodimg" style="background-image: url({{ $row->image_small }})"></div>
                            <h4>{{ $monthNames[(int) $row->month] ?? '' }} {{ $row->year }}: {!! nl2br(e($row->title)) !!}</h4>
                            <p class="collectibles-proddesc last">{!! nl2br(e($row->description)) !!}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix red">
                <h2 class="title">&iquest;Qu&eacute; es un coleccionable?</h2>
                <div id="collectibles-instructions" class="box-content">
                    Los coleccionables son muebles ultrarraros que se venden aqu&iacute;. En {{ $shortname }}s la mayor&iacute;a se venden a 25 cr&eacute;ditos.
                </div>
            </div>
        </div>

        <div class="habblet-container">
            <div class="cbb clearfix red">
                <h2 class="title">&iexcl;Invierte en un coleccionable!</h2>
                <div class="box-content">
                    <p class="collectibles-value-intro">
                        <img src="/web-gallery/v2/images/collectibles/ukplane.png" alt="" width="79" height="47" />
                        &iexcl;Alc&aacute;nza la riqueza del hotel! Con estos coleccionables, conv&iacute;ertete en el mayor coleccionista de raros. &iexcl;Pero cuidado, cuantos m&aacute;s tengas, m&aacute;s querr&aacute;s!
                    </p>
                    <p class="clear last">
                        <img src="/web-gallery/v2/images/collectibles/chart.png" alt="" width="272" height="117" />
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
