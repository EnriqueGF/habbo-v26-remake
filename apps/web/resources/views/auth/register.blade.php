@extends('layouts.guest')

@section('title', 'Registro')
@section('bodyId', 'register')

@push('head')
    <link rel="stylesheet" href="/web-gallery/v2/styles/registration.css" type="text/css" />
    <script src="/web-gallery/static/js/registration.js" type="text/javascript"></script>
    <script src="/web-gallery/static/js/domready.js" type="text/javascript"></script>
@endpush

@section('content')
    <div id="process-content">
        <div id="column1" class="column">
            <div class="habblet-container">

                <form method="post" action="/register" id="registerform" autocomplete="off">
                    @csrf
                    <input type="hidden" name="bean_figure" id="register-figure" value="" />
                    <input type="hidden" name="bean_gender" id="register-gender" value="M" />
                    <input type="hidden" name="bean_editorState" id="register-editor-state" value="" />

                    <div id="register-column-left">
                        <div id="register-avatar-editor-title">
                            <h2 class="heading"><span class="numbering white">1.</span>Crea tu avatar</h2>
                        </div>

                        <div id="avatar-error-box"></div>
                        <div id="register-avatar-editor">
                            <p><b>Adobe Flash Player es necesario. Se usar&aacute; una versi&oacute;n alternativa para elegir tu Habbo.</b></p>
                        </div>

                        {{-- Editor de figura (Flash via Ruffle) aislado en un iframe; postea
                             el look elegido por postMessage a los inputs ocultos. --}}
                        <script type="text/javascript">
                            (function () {
                                var c = document.getElementById("register-avatar-editor");
                                if (c) {
                                    c.innerHTML = '<iframe src="/flash_editor.php?figure=&gender=M&hc=0" width="435" height="400" frameborder="0" scrolling="no" style="border:0;overflow:hidden;"></iframe>';
                                }
                                window.addEventListener("message", function (ev) {
                                    if (ev.data && ev.data.type === "habboFigure" && ev.data.figure) {
                                        var fig = document.getElementById("register-figure");
                                        if (fig) { fig.value = ev.data.figure; }
                                        if (ev.data.gender) {
                                            var g = document.getElementById("register-gender");
                                            if (g) { g.value = ev.data.gender; }
                                        }
                                    }
                                }, false);
                            })();
                        </script>
                    </div>

                    <div id="register-column-right">
                        <div id="register-section-2">
                            <div class="rounded rounded-blue">
                                <h2 class="heading"><span class="numbering white">2.</span>Elige tu nombre</h2>

                                <fieldset id="register-fieldset-name">
                                    <div class="register-label white">Tu nombre de usuario en {{ $shortname }}</div>
                                    <input type="text" name="name" id="register-name" class="register-text" value="{{ old('name') }}" size="25" />
                                </fieldset>
                                <div id="name-error-box">
                                    @error('name')
                                        <div class="register-error">
                                            <div class="rounded rounded-red">
                                                <div id="name-error-content">{{ $message }}</div>
                                            </div>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="register-section-3">
                            <div id="registration-overlay"></div>
                            <div class="cbb clearfix gray">
                                <h2 class="title heading"><span class="numbering white">3.</span>Tus datos personales</h2>
                                <div class="box-content">

                                    @error('password')
                                        <div class="register-error"><div class="rounded rounded-red">
                                            <div id="password-error-content"><div>{{ $message }}</div></div>
                                        </div></div>
                                    @enderror

                                    <fieldset id="register-fieldset-password">
                                        <div class="register-label"><label for="register-password">Mi contrase&ntilde;a es:</label></div>
                                        <div class="register-label"><input type="password" name="password" id="register-password" class="register-text" size="25" value="" /></div>
                                        <div class="register-label"><label for="register-password2">Conf&iacute;rmala:</label></div>
                                        <div class="register-label"><input type="password" name="password_confirmation" id="register-password2" class="register-text" size="25" value="" /></div>
                                    </fieldset>
                                    <div id="password-error-box"></div>

                                    <fieldset>
                                        <div class="register-label"><label>Fecha de nacimiento:</label></div>
                                        <div id="register-birthday">
                                            <select name="bean_day" id="bean_day" class="dateselector">
                                                <option value="">D&iacute;a</option>
                                                @for ($d = 1; $d <= 31; $d++)
                                                    <option value="{{ $d }}" @selected(old('bean_day') == $d)>{{ $d }}</option>
                                                @endfor
                                            </select>
                                            @php($months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
                                            <select name="bean_month" id="bean_month" class="dateselector">
                                                <option value="">Mes</option>
                                                @foreach ($months as $i => $monthName)
                                                    <option value="{{ $i + 1 }}" @selected(old('bean_month') == $i + 1)>{{ $monthName }}</option>
                                                @endforeach
                                            </select>
                                            <select name="bean_year" id="bean_year" class="dateselector">
                                                <option value="">A&ntilde;o</option>
                                                @for ($y = 2010; $y >= 1920; $y--)
                                                    <option value="{{ $y }}" @selected(old('bean_year') == $y)>{{ $y }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </fieldset>

                                    <fieldset>
                                        <div class="register-label"><label>Sexo:</label></div>
                                        <div class="register-label">
                                            <label><input type="radio" name="sex" value="M" @checked(old('sex', 'M') === 'M') /> Chico</label>
                                            <label><input type="radio" name="sex" value="F" @checked(old('sex') === 'F') /> Chica</label>
                                        </div>
                                    </fieldset>

                                    <div id="email-error-box">
                                        @error('email')
                                            <div class="register-error">
                                                <div class="rounded rounded-red">
                                                    <div id="email-error-content"><div>{{ $message }}</div></div>
                                                </div>
                                            </div>
                                        @enderror
                                    </div>

                                    <fieldset>
                                        <div class="register-label"><label for="register-email">Y mi e-mail es:</label></div>
                                        <div class="register-label"><input type="text" name="email" id="register-email" class="register-text" value="{{ old('email') }}" size="25" maxlength="48" /></div>
                                    </fieldset>

                                    <fieldset id="register-fieldset-terms">
                                        <div class="rounded rounded-darkgray" id="register-terms">
                                            <div id="register-terms-content">
                                                <p><a href="/disclaimer" target="_blank" id="register-terms-link">Condiciones de uso</a></p>
                                                <p class="last">
                                                    <input type="checkbox" name="terms" id="register-terms-check" value="true" checked="checked" />
                                                    <label for="register-terms-check">Acepto las condiciones.</label>
                                                </p>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="register-buttons">
                        <input type="submit" value="&iexcl;Jugar!" class="continue" id="register-button-continue" />
                        <a href="/index.php?registerCancel=true" class="cancel">Cancelar el registro</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
