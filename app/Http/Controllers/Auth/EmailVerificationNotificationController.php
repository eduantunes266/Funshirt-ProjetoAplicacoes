<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    // Envia uma nova notificação de verificação de email
    public function store(Request $request): RedirectResponse
    {
        // Se o email já estiver verificado, redireciona para a dashboard
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Envia o email de notificação com o link de verificação
        $request->user()->sendEmailVerificationNotification();

        // Volta para a página anterior com mensagem de sucesso
        return back()->with('status', 'verification-link-sent');
    }
}
