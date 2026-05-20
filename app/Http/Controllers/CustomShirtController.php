<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomShirtController extends Controller
{
    public function create()
    {
        return view('custom.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'color' => 'required|string',
            'size' => 'required|string',
        ]);

        $imagePath = $request->file('image')->store('tshirt_images/custom', 'public');

        $cart = session()->get('cart', []);
        $customId = 'custom_' . uniqid();

        $cart[$customId] = [
            'name' => 'T-Shirt Personalizada',
            'image_url' => 'custom/' . basename($imagePath),
            'description' => 'Cor: ' . $request->color . ' | Tamanho: ' . $request->size,
            'quantity' => 1,
            'is_custom' => true
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }
}