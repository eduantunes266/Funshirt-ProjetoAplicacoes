<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class AccountStatusController extends Controller
{

    public function __invoke(User $user): RedirectResponse
    {
        $this->authorize('block', $user);

        $user->update(['blocked' => ! $user->blocked]);

        $route = $user->user_type === 'C' ? 'admin.customers.index' : 'admin.staff.index';

        return redirect()->route($route)
            ->with('success', $user->blocked ? 'Conta bloqueada.' : 'Conta desbloqueada.');
    }
}
