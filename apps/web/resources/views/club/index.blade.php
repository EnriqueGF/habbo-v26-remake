@extends('layouts.community')

@section('title', $shortname.' Club')
@php($activeNav = 'credits')

@section('subnav')
    @include('partials.navi2', ['section' => 'credits', 'active' => 'club'])
@endsection

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix hcred">
                <h2 class="title">{{ $shortname }} Club: &iexcl;h&aacute;zte HC!</h2>
                <div id="habboclub-products">
                    <div id="habboclub-clothes-container">
                        <div class="habboclub-extra-image"></div>
                        <div class="habboclub-clothes-image"></div>
                    </div>
                    <div class="clearfix"></div>
                    <div id="habboclub-furniture-container">
                        <div class="habboclub-furniture-image"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="habblet-container">
            <div class="cbb clearfix lightbrown">
                <h2 class="title">Ventajas</h2>
                <div id="habboclub-info" class="box-content">
                    <p>El {{ $shortname }} Club es el club m&aacute;s exclusivo del hotel. Solo los mejores son aceptados y sus miembros son la envidia del hotel.</p>
                    <h3 class="heading">1. Ropa y accesorios extra</h3>
                    <p class="content habboclub-clothing">Luce un look &uacute;nico con una gran variedad de ropa, accesorios y cortes de pelo exclusivos.</p>
                    <h3 class="heading">2. Muebles gratis</h3>
                    <p class="content habboclub-furni">&iexcl;Cada mes un nuevo mueble de regalo!</p>
                    <p class="content">Nota: si abandonas el Habbo Club y vuelves m&aacute;s tarde, tu suscripci&oacute;n contin&uacute;a desde donde la dejaste.</p>
                    <h3 class="heading">3. Formas de sala exclusivas</h3>
                    <p class="content">&iexcl;Dise&ntilde;os de sala exclusivos para lucir tus muebles!</p>
                    <h3 class="heading">4. Acceso prioritario</h3>
                    <p class="content">Entra antes que nadie y accede a salas exclusivas para miembros HC.</p>
                    <h3 class="heading">5. Actualizaciones de p&aacute;gina personal</h3>
                    <p class="content">&iexcl;Adios a los banners de publicidad! Con el Habbo Club tienes widgets y fondos de pantalla HC exclusivos.</p>
                    <h3 class="heading">6. M&aacute;s amigos</h3>
                    <p class="content habboclub-communicator">&iexcl;600 personas! Una lista de amigos enorme.</p>
                    <h3 class="heading">7. Comandos especiales</h3>
                    <p class="content habboclub-commands right">Usa el comando :chooser para ver qui&eacute;n est&aacute; en la sala. &iexcl;Muy &uacute;til!</p>
                </div>
            </div>
        </div>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix hcred">
                <h2 class="title">Mi suscripci&oacute;n</h2>

                @if(session('status'))
                    <div id="hc-purchase-result" class="box-content">
                        <img src="/web-gallery/album1/piccolo_happy.gif" style="float: left;" height="87" width="32" alt="" />
                        <b>&iexcl;Compra realizada!</b><br /><br />
                        {{ session('status') }}
                    </div>
                @endif

                @if(session('error'))
                    <div id="hc-purchase-error" class="box-content">
                        <b>Error</b><br /><br />
                        {{ session('error') }}
                    </div>
                @endif

                <div id="hc-membership-info" class="box-content">
                    <p>{{ $isMember ? 'Eres' : 'No eres' }} miembro del {{ $shortname }} Club</p>
                    <p>
                        @if($isMember)
                            Tienes {{ $daysLeft }} d&iacute;as HC
                        @else
                            &nbsp;
                        @endif
                    </p>
                </div>

                @if(($chromeUser->credits ?? 0) >= 20)
                    <div id="hc-buy-container" class="box-content">
                        <div id="hc-buy-buttons" class="hc-buy-buttons rounded rounded-hcred">
                            <table>
                                @foreach($packages as $months => $price)
                                    <tr>
                                        <td>
                                            <form method="post" action="/club/purchase">
                                                @csrf
                                                <input type="hidden" name="months" value="{{ $months }}" />
                                                <button type="submit" class="new-button fill"><b>Comprar {{ $months }} {{ $months === 1 ? 'mes' : 'meses' }}</b><i></i></button>
                                            </form>
                                        </td>
                                        <td>&nbsp;{{ $price }} Cr&eacute;ditos</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @else
                    <div id="hc-buy-container" class="box-content">
                        <div id="hc-buy-buttons" class="hc-buy-buttons rounded rounded-hcred">
                            <p class="credits-notice">Para unirte al {{ $shortname }} Club necesitas cr&eacute;ditos. El {{ $shortname }} Club cuesta m&iacute;nimo 20 cr&eacute;ditos.</p>
                            <a class="new-button fill" href="/credits"><b>&iexcl;Obt&eacute;n cr&eacute;ditos!</b><i></i></a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
