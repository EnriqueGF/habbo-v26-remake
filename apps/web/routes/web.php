<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CreditsController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserProfileController;
use App\Legacy\LegacyRunner;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/*
|--------------------------------------------------------------------------
| Estado / salud del front controller
|--------------------------------------------------------------------------
*/
Route::get('/health', [StatusController::class, 'health'])->name('health');
Route::get('/status', [StatusController::class, 'status'])->name('status');

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
| El formulario de login del landing legacy hace POST a index.php, que Apache
| sirve por public/index.php (front controller), de modo que Laravel lo recibe
| como POST "/". Por eso el login se atiende en POST "/". GET "/" sigue siendo
| el landing legacy. El form legacy no lleva token CSRF -> se omiten esos
| middleware (no es regresión: el legacy nunca tuvo CSRF).
*/
Route::post('/', [LoginController::class, 'login'])->withoutMiddleware([
    EncryptCookies::class, StartSession::class, ShareErrorsFromSession::class, PreventRequestForgery::class,
]);
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Páginas migradas a Laravel nativo (URLs limpias, convencionales)
|--------------------------------------------------------------------------
| Llevan el middleware legacy.user, que resuelve el usuario actual desde la
| sesión PHP compartida con el legacy y lo expone vía $request->user().
*/
Route::middleware('legacy.user')->group(function () {
    Route::get('/me', [MeController::class, 'index'])->name('me');
    Route::post('/me/feed/remove', [MeController::class, 'removeFeedItem'])->name('me.feed.remove');

    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::post('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile');
    Route::post('/account/password', [AccountController::class, 'updatePassword'])->name('account.password');

    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');

    Route::get('/community', [CommunityController::class, 'index'])->name('community');
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/help', [HelpController::class, 'index'])->name('help');

    Route::get('/credits', [CreditsController::class, 'index'])->name('credits');
    Route::get('/club', [ClubController::class, 'index'])->name('club');
    Route::post('/club/purchase', [ClubController::class, 'purchase'])->name('club.purchase');

    Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
    Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('disclaimer');
});

/*
|--------------------------------------------------------------------------
| Compatibilidad: URLs .php del legacy -> redirección 301 a la URL limpia
|--------------------------------------------------------------------------
| Mientras conviva el legacy, sus enlaces (y marcadores antiguos) usan .php.
| Redirigimos los de páginas YA migradas a su ruta limpia para no caer en el
| LegacyRunner. Se eliminará cuando el legacy se retire (Fase 5).
*/
$legacyAliases = [
    'me.php' => 'me', 'account.php' => 'account', 'user_profile.php' => 'profile',
    'community.php' => 'community', 'news.php' => 'news', 'help.php' => 'help',
    'credits.php' => 'credits', 'club.php' => 'club',
    'privacy.php' => 'privacy', 'disclaimer.php' => 'disclaimer',
];
foreach ((env('DISABLE_PHP_REDIRECTS') ? [] : $legacyAliases) as $php => $name) {
    Route::get('/'.$php, fn () => redirect()->route($name, request()->query(), 301));
}
Route::match(['get', 'post'], '/logout.php', fn () => redirect()->route('logout'));

/*
|--------------------------------------------------------------------------
| Estrangulador: todo lo no migrado -> HoloCMS legacy, in-process
|--------------------------------------------------------------------------
| Sigue siendo Laravel quien hace el dispatch (no hay proxy). El legacy usa
| sesión PHP nativa y no tolera el CSRF de Laravel, así que se omiten esos
| middleware del grupo "web".
*/
Route::any('/{any?}', LegacyRunner::class)
    ->where('any', '.*')
    ->withoutMiddleware([
        EncryptCookies::class, StartSession::class, ShareErrorsFromSession::class, PreventRequestForgery::class,
    ]);
