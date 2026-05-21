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
     * Mostra o formulario de perfil do utilizador autenticado.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
            'customer' => $user->isCustomer() ? $user->customer : null,
        ]);
    }

    /**
     * Atualiza os dados pessoais (nome, email, genero e foto de perfil).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->safe()->only(['name', 'email', 'gender']));

        // Alterar o email obriga a nova verificacao.
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('photo')) {
            // Remove a foto antiga (exceto o avatar partilhado).
            if ($user->photo_url && $user->photo_url !== 'anonymous.png') {
                Storage::disk('public')->delete('photos/'.$user->photo_url);
            }

            $file = $request->file('photo');
            $filename = Str::uuid().'.'.$file->extension();
            $file->storeAs('photos', $filename, 'public');
            $user->photo_url = $filename;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Atualiza os dados de faturacao predefinidos (apenas clientes).
     */
    public function updateBilling(CustomerBillingRequest $request): RedirectResponse
    {
        $request->user()->customer()->updateOrCreate([], $request->validated());

        return redirect()->route('profile.edit')->with('status', 'billing-updated');
    }
}
