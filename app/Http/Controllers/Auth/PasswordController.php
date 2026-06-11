<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    // Atualiza a palavra-passe do utilizador autenticado
    public function update(Request $request): RedirectResponse
    {
        // Valida a palavra-passe atual e a nova palavra-passe
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Atualiza a palavra-passe do utilizador na base de dados
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Volta para a página anterior com mensagem de sucesso
        return back()->with('status', 'password-updated');
    }
}
