<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    // Mostra o ecrã a pedir a verificação de email
    public function __invoke(Request $request): RedirectResponse|View
    {
        // Se o email já estiver verificado, redireciona para a dashboard
        // Caso contrário, mostra a view a pedir a verificação
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : view('auth.verify-email');
    }
}
