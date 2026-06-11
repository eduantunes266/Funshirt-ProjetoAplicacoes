<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePriceRequest;
use App\Models\Price;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PriceController extends Controller
{
    /**
     * Mostra a página de configuração de preços.
     */
    public function edit(): View
    {
        // Obtém a configuração de preços existente
        $price = Price::firstOrFail();

        // Envia os dados para a view de edição
        return view('admin.prices.edit', compact('price'));
    }

    /**
     * Atualiza a configuração de preços da aplicação.
     */
    public function update(UpdatePriceRequest $request): RedirectResponse
    {
        // Obtém o registo de preços atual
        $price = Price::firstOrFail();

        // Atualiza os preços com os dados validados
        $price->update($request->validated());

        // Redireciona para a página de configuração
        return redirect()
            ->route('admin.prices.edit')
            ->with('success', 'Configuracao de precos atualizada.');
    }
}