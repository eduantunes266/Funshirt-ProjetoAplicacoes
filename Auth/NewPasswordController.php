<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Mostra o formulário de redefinição de palavra-passe.
     */
    public function create(Request $request): View
    {
        // Apresenta a página de redefinição de palavra-passe
        // enviando o pedido para obter o token e email
        return view('auth.reset-password', [
            'request' => $request
        ]);
    }

    /**
     * Processa a redefinição da palavra-passe.
     */
    public function store(Request $request): RedirectResponse
    {
        // Valida os dados enviados pelo formulário
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults()
            ],
        ]);

        // Tenta redefinir a palavra-passe utilizando o token recebido
        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),

            // Executado se o token for válido
            function (User $user) use ($request) {

                // Atualiza a palavra-passe do utilizador
                $user->forceFill([
                    'password' => Hash::make($request->password),

                    // Gera um novo token "remember me"
                    'remember_token' => Str::random(60),
                ])->save();

                // Dispara o evento de redefinição de palavra-passe
                event(new PasswordReset($user));
            }
        );

        // Se a redefinição foi bem sucedida
        return $status == Password::PASSWORD_RESET

            ? redirect()
                ->route('login')
                ->with('status', __($status))

            // Caso contrário volta ao formulário com erro
            : back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => __($status)
                ]);
    }
}