<?php

namespace App\Http\Controllers;

use App\Models\CatalogueItem;
use App\Models\CataloguePage;
use App\Services\EmulatorClient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Tienda de muebles (reemplaza legacy/shop_furni.php). Muestra el catálogo por
 * categorías y permite comprar muebles: deduce créditos, inserta las filas en
 * `furniture` y registra la transacción. Reproduce la lógica del legacy pero con
 * consultas parametrizadas (Eloquent/Query Builder, sin SQLi) y de forma atómica.
 */
class ShopController extends Controller
{
    /** Artículos por página, idéntico al legacy. */
    private const PER_PAGE = 20;

    public function index(Request $request): View
    {
        $rank = (int) ($request->user()?->rank ?? 0);

        $search = trim((string) ($request->input('search') ?? ''));

        $category = (int) ($request->query('category') ?: 1);

        $page = (int) $request->query('page', '1');
        if ($page < 1) {
            $page = 1;
        }

        // Categorías visibles para el rango del usuario (réplica del legacy).
        $categories = CataloguePage::query()
            ->where('minrank', '<=', $rank)
            ->orderBy('indexid')
            ->get();

        $items = collect();
        $totalPages = 0;
        $currentCategory = null;

        if ($search !== '') {
            // Búsqueda: solo categorías visibles para el rango, parametrizada.
            $items = CatalogueItem::query()
                ->join('catalogue_pages', 'catalogue_items.catalogue_id_page', '=', 'catalogue_pages.indexid')
                ->where('catalogue_pages.minrank', '<=', $rank)
                ->where(function ($q) use ($search) {
                    $q->where('catalogue_items.catalogue_name', 'like', '%'.$search.'%')
                        ->orWhere('catalogue_items.catalogue_description', 'like', '%'.$search.'%')
                        ->orWhere('catalogue_items.name_cct', 'like', '%'.$search.'%');
                })
                ->select('catalogue_items.*')
                ->limit(self::PER_PAGE)
                ->get();
        } elseif ($category !== 1) {
            // El legacy obtiene el nombre de la categoría con su propia consulta,
            // independiente de la lista filtrada por rango.
            $currentCategory = CataloguePage::query()->where('indexid', $category)->first();

            $base = CatalogueItem::query()->where('catalogue_id_page', $category);

            $total = (clone $base)->count();
            $totalPages = (int) ceil($total / self::PER_PAGE);

            $items = $base
                ->orderBy('catalogue_name')
                ->offset(($page - 1) * self::PER_PAGE)
                ->limit(self::PER_PAGE)
                ->get();
        }
        // category == 1 sin búsqueda => frontpage (sin items).

        return view('shop.index', [
            'categories' => $categories,
            'items' => $items,
            'search' => $search,
            'category' => $category,
            'page' => $page,
            'totalPages' => $totalPages,
            'currentCategory' => $currentCategory,
        ]);
    }

    public function purchase(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->to('/');
        }

        $validated = $request->validate([
            'furniID' => ['required', 'integer', 'exists:catalogue_items,tid'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $furniId = (int) $validated['furniID'];
        $quantity = (int) ($validated['quantity'] ?? 1);
        $userId = (int) $user->id;
        $rank = (int) ($user->rank ?? 0);

        $error = null;
        $boughtName = '';
        $category = 1;

        DB::transaction(function () use ($userId, $furniId, $quantity, $rank, &$error, &$boughtName, &$category): void {
            $item = DB::table('catalogue_items')->where('tid', $furniId)->first();
            if ($item === null) {
                $error = 'Ese mueble no existe.';

                return;
            }

            $boughtName = (string) $item->catalogue_name;
            $category = (int) $item->catalogue_id_page;
            $price = (int) $item->catalogue_cost * $quantity;

            // Comprobar el rango mínimo de la categoría del mueble.
            $catMinrank = (int) (DB::table('catalogue_pages')
                ->where('indexid', $item->catalogue_id_page)
                ->value('minrank') ?? 0);

            if ($catMinrank > $rank) {
                $error = 'No tienes acceso a este mueble.';

                return;
            }

            // Recargar el usuario dentro de la transacción (con bloqueo) para
            // evitar doble gasto / créditos obsoletos.
            $fresh = DB::table('users')->where('id', $userId)->lockForUpdate()->first();

            if ($fresh === null || (int) $fresh->credits < $price) {
                $error = 'No tienes suficientes cr&eacute;ditos para comprar esto.';

                return;
            }

            // 1. Deducir créditos.
            DB::table('users')->where('id', $userId)->decrement('credits', $price);

            // 2. Insertar `quantity` muebles en la mano del usuario (roomid 0).
            //    El legacy solo daba ownerid/roomid/tid y dejaba que MariaDB
            //    aplicara los defaults implícitos de x/y/z (modo no estricto). El
            //    driver MySQL de Laravel conecta en modo estricto, así que los
            //    fijamos explícitamente a 0 (un mueble en la mano no está colocado).
            for ($i = 0; $i < $quantity; $i++) {
                DB::table('furniture')->insert([
                    'ownerid' => $userId,
                    'roomid' => 0,
                    'tid' => $furniId,
                    'x' => 0,
                    'y' => 0,
                    'z' => 0,
                ]);
            }

            // 3. Registrar la transacción (descr réplica del legacy "Bought N nombre").
            DB::table('cms_transactions')->insert([
                'userid' => $userId,
                'amount' => -$price,
                'date' => date('Y-m-d'),
                'descr' => 'Bought '.$quantity.' '.$boughtName,
            ]);
        });

        if ($error !== null) {
            return redirect()->route('shop', ['category' => $category])->with('error', $error);
        }

        // Refrescar créditos y recargar la mano en el emulador (best-effort).
        try {
            $emulator = app(EmulatorClient::class);
            $emulator->updateUserCredits($userId);
            $emulator->send('UPRH'.$userId);
        } catch (Throwable) {
            // Emulador caído: ignorar, la compra ya está persistida.
        }

        return redirect()->route('shop', ['category' => $category])->with(
            'status',
            'Has comprado '.$quantity.' '.$boughtName.'.'
        );
    }
}
