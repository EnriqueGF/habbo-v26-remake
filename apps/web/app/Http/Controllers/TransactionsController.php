<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Historial de transacciones de créditos (reemplaza legacy/transactions.php).
 * Solo lectura: lista los últimos 50 movimientos de cms_transactions del usuario
 * logueado (consulta parametrizada vía Eloquent). Requiere estar conectado.
 */
class TransactionsController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // El legacy mostraba la página solo con sesión; aquí, sin login,
        // redirigimos al landing como pide el patrón nativo.
        if ($user === null) {
            return redirect()->to('/');
        }

        // Réplica de: SELECT date,amount,descr FROM cms_transactions
        // WHERE userid = $my_id ORDER BY id DESC LIMIT 50.
        $transactions = Transaction::query()
            ->where('userid', $user->id)
            ->orderByDesc('id')
            ->limit(50)
            ->get(['date', 'amount', 'descr']);

        return view('transactions.index', compact('transactions'));
    }
}
