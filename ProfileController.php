<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerBillingRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Mostra a página de perfil do utilizador.
     */
    public function edit(Request $request): View
    {
        // Obtém o utilizador autenticado
        $user = $request->user();

        // Envia os dados do utilizador e do cliente para a view
        return view('profile.edit', [
            'user' => $user,

            // Apenas clientes possuem dados de faturação
            'customer' => $user->isCustomer()
                ? $user->customer
                : null,
        ]);
    }

    /**
     * Atualiza os dados pessoais do utilizador.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Obtém o utilizador autenticado
        $user = $request->user();

        // Atualiza apenas os campos permitidos
        $user->fill(
            $request->safe()->only([
                'name',
                'email',
                'gender'
            ])
        );

        // Se o email foi alterado,
        // remove a verificação anterior
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Se foi enviada uma nova fotografia
        if ($request->hasFile('photo')) {

            // Remove a fotografia antiga
            // exceto a imagem padrão
            if (
                $user->photo_url &&
                $user->photo_url !== 'anonymous.png'
            ) {
                Storage::disk('public')
                    ->delete('photos/' . $user->photo_url);
            }

            // Obtém o ficheiro enviado
            $file = $request->file('photo');

            // Gera um nome único para a imagem
            $filename = Str::uuid() . '.' . $file->extension();

            // Guarda a imagem no armazenamento público
            $file->storeAs('photos', $filename, 'public');

            // Atualiza o nome da fotografia no utilizador
            $user->photo_url = $filename;
        }

        // Guarda as alterações na base de dados
        $user->save();

        // Redireciona para o perfil com mensagem de sucesso
        return redirect()
            ->route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Atualiza os dados de faturação do cliente.
     */
    public function updateBilling(CustomerBillingRequest $request): RedirectResponse
    {
        // Cria ou atualiza os dados de faturação do cliente
        $request->user()
            ->customer()
            ->updateOrCreate([], $request->validated());

        // Redireciona para o perfil com mensagem de sucesso
        return redirect()
            ->route('profile.edit')
            ->with('status', 'billing-updated');
    }
}