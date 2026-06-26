@extends('layouts.community')

@section('title', 'Transacciones')
@php($activeNav = 'credits')

@section('subnav')
    @include('partials.navi2', ['section' => 'credits', 'active' => 'creditos'])
@endsection

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix brown">
                <h2 class="title">Transacciones de tu cuenta</h2>
                <div id="tx-log">
                    <div class="box-content">
                        Aqu&iacute; puedes ver el historial de transacciones de cr&eacute;ditos. Se actualiza en tiempo real. Puedes ver hasta 50 registros.
                    </div>

                    <table class="tx-history">
                        <thead>
                            <tr>
                                <th class="tx-date">Fecha</th>
                                <th class="tx-amount">Actividad</th>
                                <th class="tx-description">Descripci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $tick => $row)
                                <tr class="{{ $tick % 2 === 0 ? 'even' : 'odd' }}">
                                    <td class="tx-date">{{ $row->date }}</td>
                                    <td class="tx-amount">{{ $row->amount }}</td>
                                    <td class="tx-description">{{ $row->descr }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix brown">
                <h2 class="title">Tu monedero</h2>
                <div id="purse-habblet">
                    <form method="post" action="/credits" id="voucher-form">
                        <ul>
                            <li class="even icon-purse">
                                <div>Tienes actualmente:</div>
                                <span class="purse-balance-amount">{{ $chromeUser->credits ?? 0 }} Cr&eacute;ditos</span>
                                <div class="purse-tx"><a href="/transactions">Mis transacciones</a></div>
                            </li>
                            <li class="odd">
                                <div class="box-content">
                                    <div>Introduce un c&oacute;digo de cr&eacute;ditos:</div>
                                    <input type="text" name="voucherCode" value="" id="purse-habblet-redeemcode-string" class="redeemcode" />
                                    <a href="#" id="purse-redeemcode-button" class="new-button purse-icon" style="float:left"><b><span></span>Enviar</b><i></i></a>
                                </div>
                            </li>
                        </ul>
                        <div id="purse-redeem-result"></div>
                    </form>
                </div>
                <script type="text/javascript">
                    new PurseHabblet();
                </script>
            </div>
        </div>
    </div>
@endsection
