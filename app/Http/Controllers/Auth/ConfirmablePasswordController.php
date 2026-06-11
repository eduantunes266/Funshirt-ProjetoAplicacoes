<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Mostra a página de confirmação de palavra-passe.
     */
    public function show(): View
    {
        // Apresenta o formulário para confirmar a palavra-passe
        return view('auth.confirm-password');
    }

    /**
     * Confirma a palavra-passe do utilizador autenticado.
     */
    public function store(Request $request): RedirectResponse
    {
        // Verifica se a palavra-passe introduzida corresponde
        // à conta atualmente autenticada
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {

            // Caso a palavra-passe esteja incorreta,
            // lança uma exceção de validação
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // Guarda o instante da confirmação da palavra-passe
        // na sessão do utilizador
        $request->session()->put(
            'auth.password_confirmed_at',
            time()
        );

        // Redireciona para a página pretendida
        return redirect()->intended(
            route('dashboard', absolute: false)
        );
    }
}