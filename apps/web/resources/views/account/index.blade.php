@extends('layouts.community')

@section('title', 'Mis datos')
@section('bodyId', 'profile')
@php($activeNav = 'home')

@section('subnav')
    <div id="navi2-container" class="pngbg">
        <div id="navi2" class="pngbg clearfix">
            <ul>
                <li class="">
                    <a href="/index.php">Inicio</a>
                </li>
                <li class="">
                    <a href="/profile?name={{ urlencode($chromeUser->name) }}">Mi Homepage</a>
                </li>
                <li class="selected">
                    Mis preferencias
                </li>
                <li class="">
                    <a href="/deletehand.php">Vac&iacute;a tu mano</a>
                </li>
                <li class="last">
                    <a href="/club">Habbo Club</a>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div>
        <div class="content">
            <div class="habblet-container" style="float:left; width:210px;">
                <div class="cbb settings">

                    <h2 class="title">Mis preferencias</h2>
                    <div class="box-content">
                        <div id="settingsNavigation">
                            <ul>
                                <li class='selected'>ASPECTO
                                </li><li><a href='/account?tab=2'>LEMA</a>
                                </li><li><a href='/account?tab=3'>EMAIL</a>
                                </li><li><a href='/account?tab=4'>CONTRASE&Ntilde;A</a>
                                </li><li><a href='/account?tab=5'>&iquest;CR&Eacute;DITOS?</a>
                                </li><li><a href='/account?tab=6'>GESTIONAR AMIGOS</a>
                                </li><li><a href='/account?tab=8'>LOADER</a>
                                </li>
                            </ul>
                        </div>
                    </div></div>
            </div>

            <div class="habblet-container" style="float:left; width: 560px;">
                <div class="cbb clearfix settings">

                    <h2 class="title">Cambia tu aspecto</h2>
                    <div class="box-content">

                        <div>&nbsp;</div>

                        <div id="settings-editor">
                            Para cambiar tu aspecto necesitas Flash Player de Adobe: <a target="_blank" href="http://www.adobe.com/go/getflashplayer">http://www.adobe.com/go/getflashplayer</a>
                        </div>

                        <div id="settings-wardrobe" style="display: none">
                            <ol id="wardrobe-slots">
                                <li>
                                    <p id="wardrobe-slot-1" style="background-image: url()">
                                        <span id="wardrobe-store-1" class="wardrobe-store"></span>
                                        <span id="wardrobe-dress-1" class="wardrobe-dress"></span>
                                    </p>
                                </li>
                                <li>
                                    <p id="wardrobe-slot-2" style="background-image: url()">
                                        <span id="wardrobe-store-2" class="wardrobe-store"></span>
                                        <span id="wardrobe-dress-2" class="wardrobe-dress"></span>
                                    </p>
                                </li>
                                <li>
                                    <p id="wardrobe-slot-3" style="background-image: url()">
                                        <span id="wardrobe-store-3" class="wardrobe-store"></span>
                                        <span id="wardrobe-dress-3" class="wardrobe-dress"></span>
                                    </p>
                                </li>
                                <li>
                                    <p id="wardrobe-slot-4" style="background-image: url()">
                                        <span id="wardrobe-store-4" class="wardrobe-store"></span>
                                        <span id="wardrobe-dress-4" class="wardrobe-dress"></span>
                                    </p>
                                </li>
                                <li>
                                    <p id="wardrobe-slot-5" style="background-image: url()">
                                        <span id="wardrobe-store-5" class="wardrobe-store"></span>
                                        <span id="wardrobe-dress-5" class="wardrobe-dress"></span>
                                    </p>
                                </li>
                            </ol>

                            <script type="text/javascript">
                                L10N.put("profile.figure.wardrobe_replace.title", "&iquest;Reemplazar?");
                                L10N.put("profile.figure.wardrobe_replace.dialog", "<p\>\n&iquest;Seguro que quieres reemplazar el look guardado por el nuevo?\n</p\>\n\n<p\>\n<a href=\"#\" class=\"new-button\" id=\"wardrobe-replace-cancel\"\><b\>Cancelar</b\><i\></i\></a\>\n<a href=\"#\" class=\"new-button\" id=\"wardrobe-replace-ok\"\><b\>OK</b\><i\></i\></a\>\n</p\>\n\n<div class=\"clear\"\></div\>\n");
                                L10N.put("profile.figure.wardrobe_invalid_data", "&iexcl;Error! Este look no se puede guardar.");
                                L10N.put("profile.figure.wardrobe_instructions", "Pulsa las flechas rojas para guardar hasta 5 looks en tu armario. Pulsa la flecha verde para seleccionar un look y guardar los cambios para usarlo.");
                                Wardrobe.init();
                            </script>
                        </div>
                        <div id="settings-hc" style="display: none">
                            <div class="rounded rounded-hcred clearfix">
                                <a href="/club" id="settings-hc-logo"></a>
                                La ropa con el s&iacute;mbolo <img src="./web-gallery/v2/images/habboclub/hc_mini.png" /> es exclusiva para miembros del Club. <a href="/club">&iexcl;S&uacute;mate ya!</a>
                            </div>
                        </div>

                        <div id="settings-oldfigure" style="display: none">
                            <div class="rounded rounded-lightbrown clearfix">
                                Tu Habbo necesita colores y ropa. &iexcl;Haz clic en la cabeza para ver el cuerpo!
                            </div>
                        </div>

                        <form method="post" action="/account?tab=1" id="settings-form" style="display: none">
                            <input type="hidden" name="tab" value="1" />
                            <input type="hidden" name="__app_key" value="HoloCMS" />
                            <input type="hidden" name="figureData" id="settings-figure" value="{{ $chromeUser->figure }}" />
                            <input type="hidden" name="newGender" id="settings-gender" value="{{ $chromeUser->sex }}" />
                            <input type="hidden" name="editorState" id="settings-state" value="" />
                            <a href="#" id="settings-submit" class="new-button disabled-button"><b>Guardar</b><i></i></a>

                            <!-- Flash look editor via Ruffle (WASM), isolated in an iframe (Ruffle's polyfill
                                 conflicts with the CMS's Prototype.js, so it must run on a clean page). -->
                            <script type="text/javascript">
                                (function(){
                                    var c = document.getElementById("settings-editor");
                                    if(c){
                                        c.innerHTML = '<iframe src="/flash_editor.php?figure={{ urlencode($chromeUser->figure) }}&gender={{ $chromeUser->sex }}&hc=1" width="435" height="400" frameborder="0" scrolling="no" style="border:0;overflow:hidden;"></iframe>';
                                        c.style.textAlign = "center";
                                    }
                                    var f = document.getElementById("settings-form"); if(f){ f.style.display = ""; }
                                    var w = document.getElementById("settings-wardrobe"); if(w){ w.style.display = ""; }
                                    // The editor iframe relays the chosen look via postMessage.
                                    window.addEventListener("message", function(ev){
                                        if(ev.data && ev.data.type === "habboFigure" && ev.data.figure){
                                            var fig = document.getElementById("settings-figure"); if(fig){ fig.value = ev.data.figure; }
                                            if(ev.data.gender){ var g = document.getElementById("settings-gender"); if(g){ g.value = ev.data.gender; } }
                                            var btn = document.getElementById("settings-submit");
                                            if(btn){
                                                btn.className = "new-button";  // enable Save
                                                btn.onclick = function(e){ if(e&&e.preventDefault){e.preventDefault();} document.getElementById("settings-form").submit(); return false; };
                                            }
                                        }
                                    }, false);
                                })();
                            </script>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Núcleo migrado a Laravel nativo: edición de perfil (lema/email) y cambio de
         contraseña. A ANCHO COMPLETO (sin column1/column2), bajo el editor de aspecto. --}}
    <div class="habblet-container" id="profile-edit" style="clear:both; float:left; width: 778px;">
        <div class="cbb clearfix settings">
            <h2 class="title">Editar perfil</h2>
            <div class="box-content">
                @if(session('status'))
                    <div class="rounded rounded-green">{{ session('status') }}</div>
                    <br />
                @endif
                @if($errors->any())
                    <div class="rounded rounded-red">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <br />
                @endif

                <form action="{{ rtrim(request()->getSchemeAndHttpHost(), '/') }}/account/profile" method="post">
                    @csrf
                    <h3>Tu lema</h3>
                    <p>
                        <span class="label">Lema:</span>
                        <input type="text" name="mission" size="32" maxlength="50" value="{{ old('mission', $chromeUser->mission) }}" id="avatarmotto" />
                    </p>
                    <h3>Tu e-mail</h3>
                    <p>
                        <label for="email">Direcci&oacute;n de e-mail:</label><br />
                        <input type="text" name="email" id="email" size="32" maxlength="100" value="{{ old('email', $chromeUser->email) }}" />
                    </p>
                    <div class="settings-buttons">
                        <input type="submit" value="Guardar" name="save" class="submit" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="habblet-container" id="password-edit" style="clear:both; float:left; width: 778px;">
        <div class="cbb clearfix settings">
            <h2 class="title">Cambiar contrase&ntilde;a</h2>
            <div class="box-content">
                <form action="{{ rtrim(request()->getSchemeAndHttpHost(), '/') }}/account/password" method="post" id="passwordform">
                    @csrf
                    <h3>1. Tu contrase&ntilde;a actual</h3>
                    <p>
                        <label for="current_password">Contrase&ntilde;a actual:</label><br />
                        <input type="password" size="32" maxlength="48" name="current_password" id="current_password" class="currentpassword" />
                    </p>
                    <h3>2. Elige una nueva contrase&ntilde;a</h3>
                    <p>
                        <label for="new_password">Nueva contrase&ntilde;a:</label><br />
                        <input type="password" name="new_password" id="new_password" size="32" maxlength="48" value="" />
                    </p>
                    <p>
                        <label for="new_password_confirmation">Confirma la contrase&ntilde;a:</label><br />
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" size="32" maxlength="48" value="" />
                    </p>
                    <div class="settings-buttons">
                        <input type="submit" value="Guardar" name="save" class="submit" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
