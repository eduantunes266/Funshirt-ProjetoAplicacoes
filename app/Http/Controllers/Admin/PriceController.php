<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePriceRequest;
use App\Models\Price;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PriceController extends Controller
{
    // Mostra o formulário para editar a configuração de preços
    public function edit(): View
    {
        // Vai buscar o único registo de preços existente (se não existir dá erro)
        $price = Price::firstOrFail();
        return view('admin.prices.edit', compact('price'));
    }

    // Atualiza a configuração de preços
    public function update(UpdatePriceRequest $request): RedirectResponse
    {
        // Vai buscar o registo atual
        $price = Price::firstOrFail();
        // Atualiza com os dados validados
        $price->update($request->validated());

        return redirect()->route('admin.prices.edit')
            ->with('success', 'Configuracao de precos atualizada.');
    }
}
