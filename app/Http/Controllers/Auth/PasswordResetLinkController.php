<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    // Mostra a página para pedir a recuperação de password
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    // Processa o pedido de envio do link de recuperação de password
    public function store(Request $request): RedirectResponse
    {
        // Valida se o email foi enviado e tem um formato válido
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Tenta enviar o link de recuperação para o email indicado
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Se o link foi enviado com sucesso, volta para a página anterior com mensagem de sucesso
        // Caso contrário, volta com os erros e o email preenchido
        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
