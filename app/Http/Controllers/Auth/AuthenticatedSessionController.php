<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Mostra o formulário de login.
     */
    public function create(): View
    {
        // Apresenta a página de autenticação
        return view('auth.login');
    }

    /**
     * Efetua o processo de autenticação do utilizador.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Valida as credenciais e autentica o utilizador
        $request->authenticate();

        // Regenera a sessão por motivos de segurança
        // (proteção contra Session Fixation)
        $request->session()->regenerate();

        // Redireciona para a página pretendida
        // ou para a página inicial caso não exista destino anterior
        return redirect()->intended(route('home'));
    }

    /**
     * Termina a sessão do utilizador.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Efetua logout do utilizador autenticado
        Auth::guard('web')->logout();

        // Invalida a sessão atual
        $request->session()->invalidate();

        // Regenera o token CSRF por segurança
        $request->session()->regenerateToken();

        // Redireciona para a página inicial
        return redirect('/');
    }
}