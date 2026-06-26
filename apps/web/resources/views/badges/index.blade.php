@extends('layouts.community')

@section('title', 'Tienda de placas')
@php($activeNav = 'shop')

{{-- pageid="bshop" en el legacy: sección TIENDA, sin barra navi2 (no @section('subnav')). --}}

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix red">
                <h2 class="title">Tienda de placas</h2>
                <p class="credits-countries-select" align="left"></p>
                <p id="collectibles-purchase">
                    <center>
                        <u>&iexcl;Bienvenido a la tienda de placas!<br />
                        Estas son las placas en venta actualmente:</u>
                    </center>
                    <br />
                    <div id="hc-buy-buttons" class="hc-buy-buttons rounded rounded-hcred">
                        <center>
                            <form>
                                <table>
                                    @foreach($badges as $badge)
                                        <tr>
                                            <td>
                                                <img src="{{ $badge['image'] }}" width="50" height="50" border="174" alt="" />
                                                <a class="new-button fill" onclick="habboclub.buttonClick(1,'{{ strtoupper($shortname) }} CLUB'); return false;" href="{{ $badge['link'] }}"><b>Comprar</b><i></i></a>
                                                Precio: {{ $badge['price'] }} Cr&eacute;ditos
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </form>
                        </center>
                        <br /><br /><br />
                    </div>
                </p>
            </div>
        </div>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix blue">
                <h2 class="title">Informaci&oacute;n de la tienda</h2>
                <div id="credits-promo" class="box-content credits-info">
                    <div class="credit-info-text clearfix">
                        <p><img class="credits-image" src="/web-gallery/info_machine.gif" align="center" />
                        Esta tienda te permite comprar placas por unos pocos cr&eacute;ditos. &iexcl;Se a&ntilde;aden nuevas placas constantemente!</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="habblet-container">
            <div class="cbb clearfix green">
                <h2 class="title">&iquest;Comprar una placa?</h2>
                <div id="credits-promo" class="box-content credits-info">
                    <div class="credit-info-text clearfix">
                        <p><img class="credits-image" src="/web-gallery/v2/images/credits/poor.png" align="left" />
                        Para comprar una placa, nada m&aacute;s f&aacute;cil. Elige tu placa y haz clic en "Comprar".</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
