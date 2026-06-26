<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BadgesController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\CollectablesController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CreditsController;
use App\Http\Controllers\DeleteHandController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PixelsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\VipController;
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

// Páginas de invitado (chrome de login). Usan el grupo web (sesión + CSRF); los
// formularios nativos llevan @csrf.
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/forgot', [ForgotController::class, 'show'])->name('forgot');
Route::post('/forgot', [ForgotController::class, 'submit'])->name('forgot.submit');

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

    Route::get('/community', [CommunityController::class, 'index'])->name('community');
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/help', [HelpController::class, 'index'])->name('help');

    Route::get('/credits', [CreditsController::class, 'index'])->name('credits');
    Route::get('/club', [ClubController::class, 'index'])->name('club');
    Route::post('/club/purchase', [ClubController::class, 'purchase'])->name('club.purchase');

    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::post('/shop/purchase', [ShopController::class, 'purchase'])->name('shop.purchase');

    Route::get('/vip', [VipController::class, 'index'])->name('vip');
    Route::get('/badges', [BadgesController::class, 'index'])->name('badges');

    // Sección créditos
    Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions');
    Route::get('/deletehand', [DeleteHandController::class, 'index'])->name('deletehand');
    Route::post('/deletehand', [DeleteHandController::class, 'empty'])->name('deletehand.empty');
    Route::get('/collectables', [CollectablesController::class, 'index'])->name('collectables');
    Route::get('/pixels', [PixelsController::class, 'index'])->name('pixels');

    // Sección comunidad
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
    Route::get('/staff', [StaffController::class, 'index'])->name('staff');
    Route::get('/tags', [TagsController::class, 'index'])->name('tags');
    Route::get('/forum', [ForumController::class, 'index'])->name('forum');

    // Solicitudes de staff (formulario de reclutamiento)
    Route::get('/applications', [ApplicationsController::class, 'index'])->name('applications');
    Route::get('/applications/{form}', [ApplicationsController::class, 'show'])->name('applications.show')->whereNumber('form');
    Route::post('/applications/{form}', [ApplicationsController::class, 'submit'])->name('applications.submit')->whereNumber('form');

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
    'me.php' => 'me', 'account.php' => 'account',
    'community.php' => 'community', 'news.php' => 'news', 'help.php' => 'help',
    'credits.php' => 'credits', 'club.php' => 'club', 'vip.php' => 'vip',
    'shop_furni.php' => 'shop', 'badges.php' => 'badges',
    'transactions.php' => 'transactions', 'deletehand.php' => 'deletehand',
    'collectables.php' => 'collectables', 'pixels.php' => 'pixels',
    'statistics.php' => 'statistics', 'staff.php' => 'staff', 'tags.php' => 'tags',
    'forum.php' => 'forum',
    'register.php' => 'register', 'forgot.php' => 'forgot',
    'privacy.php' => 'privacy', 'disclaimer.php' => 'disclaimer',
];
foreach ((env('DISABLE_PHP_REDIRECTS') ? [] : $legacyAliases) as $php => $name) {
    Route::get('/'.$php, fn () => redirect()->route($name, request()->query(), 301));
}
// applications.php?id=N lleva el id como query -> lo mapeamos a la URL REST limpia.
if (! env('DISABLE_PHP_REDIRECTS')) {
    Route::get('/applications.php', fn () => request()->filled('id')
        ? redirect()->route('applications.show', ['form' => request('id')], 301)
        : redirect()->route('applications', [], 301));
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
