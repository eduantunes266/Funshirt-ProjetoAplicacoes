<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    // Marca o endereço de email do utilizador autenticado como verificado
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Se o utilizador já tiver o email verificado, redireciona para a dashboard
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        // Tenta marcar o email como verificado e, se tiver sucesso, dispara o evento Verified
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Redireciona para a dashboard com indicação de sucesso
        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
