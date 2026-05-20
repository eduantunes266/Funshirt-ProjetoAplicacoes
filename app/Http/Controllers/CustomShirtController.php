<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Price;
class CustomShirtController extends Controller
{
    public function create()
    {
        $price = Price::first();
    return view('custom.create', compact('price'));
    }

  public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'color' => 'required|string',
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1|max:10',
        ], [
            'image.required' => '⚠️ É obrigatório inserir uma imagem para criar a tua t-shirt.',
            'image.image' => 'O ficheiro carregado tem de ser uma imagem válida.',
            'image.max' => 'A imagem não pode ter mais de 2MB.',
        ]);

        $imagePath = $request->file('image')->store('tshirt_images/custom', 'public');
        $cart = session()->get('cart', []);
        $customId = 'custom_' . uniqid();

        $cart[$customId] = [
            'name' => 'T-Shirt Personalizada',
            'image_url' => 'custom/' . basename($imagePath),
            'description' => 'Cor: ' . $request->color . ' | Tamanho: ' . $request->size,
            'quantity' => (int) $request->quantity,
            'is_custom' => true
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }
}