@extends('layouts.community')

@section('title', 'Pixels')
@php($activeNav = 'credits')

@section('subnav')
    @include('partials.navi2', ['section' => 'credits', 'active' => 'pixels'])
@endsection

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix pixelblue">
                <h2 class="title">&iexcl;Aprende c&oacute;mo ganar p&iacute;xeles!</h2>
                <div class="pixels-infobox-container">
                    <div class="pixels-infobox-text">
                        <h3>&iquest;C&oacute;mo ganar p&iacute;xeles?</h3>
                        <ul>
                            <li><p></p></li>
                        </ul>
                        <p>1. Conect&aacute;te al hotel al menos una vez al d&iacute;a.</p>
                        <p>2. Pasa tiempo en el hotel cada d&iacute;a. &iexcl;Cuanto m&aacute;s tiempo, m&aacute;s p&iacute;xeles acumulas!</p>
                        <p>3. Completa todas las pruebas win-win.</p>
                        <p>4. &iexcl;S&uacute;scr&iacute;bete al Habbo Club!</p>

                        &iquest;C&oacute;mo gastarlos? &iexcl;Echa un vistazo al cat&aacute;logo y a la tienda de p&iacute;xeles!
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix pixelgreen">
                <h2 class="title">&iexcl;Para alquilar!</h2>
                <div id="pixels-info" class="box-content pixels-info">
                    <div class="pixels-info-text clearfix">
                        <img class="pixels-image" src="/web-gallery/v2/images/activitypoints/pixelpage_effectmachine.png" alt="" />
                        <p class="pixels-text">&iexcl;Sorprende a todos en tu sala con eventos especiales!</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="habblet-container">
            <div class="cbb clearfix pixellightblue">
                <h2 class="title">&iquest;Efectos?</h2>
                <div id="pixels-info" class="box-content pixels-info">
                    <div class="pixels-info-text clearfix">
                        <img class="pixels-image" src="/web-gallery/v2/images/activitypoints/pixelpage_personaleffect.png" alt="" />
                        <p class="pixels-text">Equipa a tu {{ $shortname }} para que luzca en cualquier ocasi&oacute;n. &iquest;Quieres ser el centro de atenci&oacute;n? &iexcl;Esta es tu oportunidad!</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="habblet-container">
            <div class="cbb clearfix pixeldarkblue">
                <h2 class="title">&iquest;Buenas ofertas?</h2>
                <div id="pixels-info" class="box-content pixels-info">
                    <div class="pixels-info-text clearfix">
                        <img class="pixels-image" src="/web-gallery/v2/images/activitypoints/pixelpage_discounts.png" alt="" />
                        <p class="pixels-text">Ganando p&iacute;xeles puedes pagar tus muebles m&aacute;s barato. &iexcl;Desc&uacute;bre c&oacute;mo!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
