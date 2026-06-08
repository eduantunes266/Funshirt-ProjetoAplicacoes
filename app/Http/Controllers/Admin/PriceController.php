<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePriceRequest;
use App\Models\Price;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PriceController extends Controller
{
    public function edit(): View
    {

        $price = Price::firstOrFail();
        return view('admin.prices.edit', compact('price'));
    }

    public function update(UpdatePriceRequest $request): RedirectResponse
    {
        $price = Price::firstOrFail();
        $price->update($request->validated());

        return redirect()->route('admin.prices.edit')
            ->with('success', 'Configuracao de precos atualizada.');
    }
}
