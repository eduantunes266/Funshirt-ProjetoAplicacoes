<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class AccountStatusController extends Controller
{
    /**
     * Bloqueia ou desbloqueia uma conta (funcionario, administrador ou cliente).
     */
    public function __invoke(User $user): RedirectResponse
    {
        $this->authorize('block', $user);

        $user->update(['blocked' => ! $user->blocked]);

        // Volta para a listagem correta consoante o tipo de conta.
        $route = $user->user_type === 'C' ? 'admin.customers.index' : 'admin.staff.index';

        return redirect()->route($route)
            ->with('success', $user->blocked ? 'Conta bloqueada.' : 'Conta desbloqueada.');
    }
}
