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
    // Mostra a página de edição do perfil do utilizador
    public function edit(Request $request): View
    {
        // Vai buscar o utilizador autenticado
        $user = $request->user();

        // Envia os dados para a view de edição de perfil
        return view('profile.edit', [
            'user' => $user,
            // Se o utilizador for cliente, envia também os dados de cliente
            'customer' => $user->isCustomer() ? $user->customer : null,
        ]);
    }

    // Atualiza as informações do perfil do utilizador
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Vai buscar o utilizador autenticado
        $user = $request->user();
        
        // Preenche os dados do utilizador com os dados validados do formulário
        $user->fill($request->safe()->only(['name', 'email', 'gender']));

        // Se o email foi alterado, remove a data de verificação de email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Verifica se foi enviada uma nova foto
        if ($request->hasFile('photo')) {
            // Se o utilizador já tiver uma foto (que não seja a anónima), apaga a antiga
            if ($user->photo_url && $user->photo_url !== 'anonymous.png') {
                Storage::disk('public')->delete('photos/'.$user->photo_url);
            }

            // Vai buscar o ficheiro enviado
            $file = $request->file('photo');
            
            // Gera um nome único para a foto usando UUID e a extensão original
            $filename = Str::uuid().'.'.$file->extension();
            
            // Guarda a foto no disco público, na pasta 'photos'
            $file->storeAs('photos', $filename, 'public');
            
            // Atualiza o URL da foto no modelo do utilizador
            $user->photo_url = $filename;
        }

        // Guarda as alterações do utilizador na base de dados
        $user->save();

        // Volta para a página de edição de perfil com mensagem de sucesso
        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    // Atualiza os dados de faturação do cliente
    public function updateBilling(CustomerBillingRequest $request): RedirectResponse
    {
        // Atualiza ou cria o registo de cliente associado ao utilizador com os dados validados
        $request->user()->customer()->updateOrCreate([], $request->validated());

        // Volta para a página de edição de perfil com mensagem de sucesso
        return redirect()->route('profile.edit')->with('status', 'billing-updated');
    }
}
