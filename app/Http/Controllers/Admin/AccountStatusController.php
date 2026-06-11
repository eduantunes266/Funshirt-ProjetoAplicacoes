<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class AccountStatusController extends Controller
{
    // Altera o estado (bloqueado/desbloqueado) de uma conta
    public function __invoke(User $user): RedirectResponse
    {
        // Verifica se o utilizador autenticado tem permissões para bloquear
        $this->authorize('block', $user);

        // Inverte o estado atual de bloqueio do utilizador
        $user->update(['blocked' => ! $user->blocked]);

        // Define para que página redirecionar com base no tipo de utilizador
        $route = $user->user_type === 'C' ? 'admin.customers.index' : 'admin.staff.index';

        // Redireciona com uma mensagem de sucesso adequada
        return redirect()->route($route)
            ->with('success', $user->blocked ? 'Conta bloqueada.' : 'Conta desbloqueada.');
    }
}
