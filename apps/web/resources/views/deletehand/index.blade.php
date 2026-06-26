@extends('layouts.community')

@section('title', 'Vac&iacute;a tu mano')
@php($activeNav = 'credits')

@section('subnav')
    @include('partials.navi2', ['section' => 'credits', 'active' => 'hand'])
@endsection

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container" id="collectible-current">
            <div class="cbb clearfix green">
                <h2 class="title">Vaciar la mano</h2>
                <div class="habblet box-content">
                    @if(session('status'))
                        <p><strong>{{ session('status') }}</strong></p>
                    @endif
                    <h4>{{ $loggedIn ? $chromeUser->name : '' }},</h4><br />
                    &iquest;Tienes demasiados muebles? &iquest;Te cansas de esperar 20 minutos para llegar al final de tu mano? &iexcl;STOP! Aqu&iacute; puedes vaciar tu mano.<br /><br />
                    Haz clic en "&iexcl;Vac&iacute;o mi mano!" para borrar todos los muebles.

                    <p id="collectibles-purchase">
                        <form method="post" action="{{ route('deletehand.empty') }}">
                            @csrf
                            <div class="habblet-button-row clearfix">
                                <button type="submit" class="new-button" id="delete_hand"><b>&iexcl;Vac&iacute;o mi mano!</b><i></i></button>
                            </div>
                        </form>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix red">
                <h2 class="title">Atenci&oacute;n</h2>
                <div id="notfound-looking-for" class="box-content">
                    Atenci&oacute;n: si haces clic en "Vaciar mi mano" se eliminar&aacute;n todos los muebles. Si lo haces por error, o si todav&iacute;a te quedaba un mueble en la mano, no podremos recuperarlo y no nos hacemos responsables de tu p&eacute;rdida.<br />
                    <br /><img src="/web-gallery/images/frank/sorry.gif" alt="" width="57" height="88" />
                </div>
            </div>
        </div>
    </div>
@endsection
