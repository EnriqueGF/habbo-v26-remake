@extends('layouts.community')

@section('title', 'VIP Club')
@php($activeNav = 'vip')

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix red">
                <h2 class="title">VIP Club</h2>
                <p class="credits-countries-select" align="left">
                    <img class="credits-image" src="/web-gallery/v2/images/best.gif" align="left" alt="" />
                    Bienvenido a la p&aacute;gina del VIP Club.<br>
                    Aqu&iacute; podr&aacute;s suscribirte al Club en pocos minutos.<br><br>
                    <u><b>&iquest;Cu&aacute;les son las ventajas del VIP Club?</b></u><br><br>
                    <i>
                        - Tendr&aacute;s 10.000 cr&eacute;ditos extra.<br>
                        - Tendr&aacute;s el rango 3.<br>
                        - Tendr&aacute;s la placa VIP <img src="/Badges/VIP.gif" alt="VIP"><br>
                        - Tendr&aacute;s la placa DU3 <img src="/Badges/DU3.gif" alt="DU3"><br>
                        - Tendr&aacute;s acceso a un cat&aacute;logo completo con muebles raros.
                    </i>
                    <br><br><u><b>&iquest;C&oacute;mo suscribirse al Club?</b></u><br><br>
                    Para suscribirte al Club solo tienes que pagar 1 Allopass/Webopass.<br>
                    Usa el formulario de abajo para comprarlo y valida el c&oacute;digo. &iexcl;Ser&aacute;s VIP autom&aacute;ticamente!<br><br>
                    <img src="/imgsrcipt.png" alt="">
                    <br>
                </p>
            </div>
        </div>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix blue">
                <h2 class="title">&iquest;Qu&eacute; es el VIP Club?</h2>
                <div id="credits-promo" class="box-content credits-info">
                    <div class="credit-info-text clearfix">
                        <p>
                            <img class="credits-image" src="http://archive.habboretroweb.net/imgs/other/objects/ko-trophy.gif" align="center" alt="" />
                            El VIP Club es un rango especial que te ofrece privilegios &uacute;nicos respecto a los miembros normales.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="habblet-container">
            <div class="cbb clearfix green">
                <h2 class="title">&iquest;Hacerse VIP?</h2>
                <div id="credits-promo" class="box-content credits-info">
                    <div class="credit-info-text clearfix">
                        <p>
                            <img class="credits-image" src="http://archive.habboretroweb.net/imgs/other/objects/mobile_phone.gif" align="left" alt="" />
                            &iquest;Quieres unirte al Club VIP?<br>Solo tienes que pagar 1 Allopass/Webopass siguiendo las instrucciones de la izquierda.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="habblet-container">
            <div class="cbb clearfix pixellightblue">
                <h2 class="title">Ver los VIP</h2>
                <div id="credits-promo" class="box-content credits-info">
                    <div class="credit-info-text clearfix">
                        <p>
                            <img class="credits-image" src="http://archive.habboretroweb.net/imgs/other/objects/pile_of_stuff.gif" align="left" alt="" />
                            Para ver los VIP solo tienes que <a href="/vip_liste.php">hacer clic aqu&iacute;</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
