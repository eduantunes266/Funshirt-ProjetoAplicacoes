<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    // Mostra a página de registo de um novo utilizador
    public function create(): View
    {
        return view('auth.register');
    }

    // Processa o registo de um novo utilizador
    public function store(Request $request): RedirectResponse
    {
        // Valida os dados enviados no formulário de registo
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'gender' => ['required', 'in:M,F'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Cria o novo utilizador na base de dados com o tipo 'C' (Cliente) e não bloqueado
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'user_type' => 'C',
            'gender' => $request->gender,
            'blocked' => false,
        ]);

        // Cria também o registo correspondente na tabela de clientes
        Customer::create(['id' => $user->id]);

        // Dispara o evento de novo utilizador registado
        event(new Registered($user));

        // Autentica o utilizador recém-criado
        Auth::login($user);

        // Redireciona o utilizador para a página inicial
        return redirect(route('home', absolute: false));
    }
}
