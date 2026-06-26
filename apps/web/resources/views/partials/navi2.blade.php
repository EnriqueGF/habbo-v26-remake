{{--
    Barra de navegación de segundo nivel (#navi2), reproducción fiel del legacy
    templates/community/header.php (que la pintaba según $pageid).
    Uso: @include('partials.navi2', ['section' => 'home|community|credits', 'active' => '<clave>'])
    El layout community ya la envuelve en #navi2-container/#navi2.
--}}
@php($active = $active ?? '')
@php($me = $loggedIn ? $chromeUser->name : '')
<ul>
    @if($section === 'home')
        <li class="{{ $active === 'inicio' ? 'selected' : '' }}">@if($active === 'inicio')Inicio @else<a href="/index.php">Inicio</a>@endif</li>
        <li class="{{ $active === 'homepage' ? 'selected' : '' }}">@if($active === 'homepage')Mi Homepage @else<a href="/profile?name={{ urlencode($me) }}">Mi Homepage</a>@endif</li>
        <li class="{{ $active === 'preferencias' ? 'selected' : '' }}">@if($active === 'preferencias')Mis preferencias @else<a href="/account">Mis preferencias</a>@endif</li>
        <li class="{{ $active === 'hand' ? 'selected' : '' }}">@if($active === 'hand')Vac&iacute;a tu mano @else<a href="/deletehand.php">Vac&iacute;a tu mano</a>@endif</li>
        <li class="last"><a href="/club">{{ $shortname }} Club</a></li>
    @elseif($section === 'community')
        <li class="{{ $active === 'comunidad' ? 'selected' : '' }}">@if($active === 'comunidad')Comunidad @else<a href="/community">Comunidad</a>@endif</li>
        <li class="{{ $active === 'noticias' ? 'selected' : '' }}">@if($active === 'noticias')Noticias @else<a href="/news">Noticias</a>@endif</li>
        <li class="{{ $active === 'staff' ? 'selected' : '' }}">@if($active === 'staff')El equipo @else<a href="/staff.php">El equipo</a>@endif</li>
        <li class="{{ $active === 'forum' ? 'selected' : '' }}">@if($active === 'forum')Forum @else<a href="/forum.php">Forum</a>@endif</li>
        <li class="{{ $active === 'tags' ? 'selected' : '' }}">@if($active === 'tags')Tags @else<a href="/tags.php">Tags</a>@endif</li>
        <li class="{{ $active === 'stats' ? 'selected last' : 'last' }}">@if($active === 'stats')Estad&iacute;sticas @else<a href="/statistics.php">Estad&iacute;sticas</a>@endif</li>
    @elseif($section === 'credits')
        <li class="{{ $active === 'creditos' ? 'selected' : '' }}">@if($active === 'creditos')Cr&eacute;ditos @else<a href="/credits">Cr&eacute;ditos</a>@endif</li>
        <li class="{{ $active === 'club' ? 'selected' : '' }}">@if($active === 'club'){{ $shortname }} Club @else<a href="/club">{{ $shortname }} Club</a>@endif</li>
        <li class="{{ $active === 'hand' ? 'selected' : '' }}">@if($active === 'hand')Vac&iacute;a tu mano @else<a href="/deletehand.php">Vac&iacute;a tu mano</a>@endif</li>
        <li class="{{ $active === 'collectables' ? 'selected' : '' }}">@if($active === 'collectables')Collectors @else<a href="/collectables.php">Collectors</a>@endif</li>
        <li class="last"><a href="/pixels.php">Pixels</a></li>
    @endif
</ul>
