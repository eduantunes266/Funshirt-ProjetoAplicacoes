<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Http\Request;

class CustomShirtController extends Controller
{
    private const VALID_SIZES = ['XS', 'S', 'M', 'L', 'XL'];

    public function create()
    {
        $price = Price::first();
        $colors = Color::orderBy('name')->get();
        $sizes = self::VALID_SIZES;

        return view('custom.create', compact('price', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'color_code' => ['required', 'string', 'exists:colors,code'],
            'size' => ['required', 'in:' . implode(',', self::VALID_SIZES)],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ], [
            'image.required' => 'E obrigatorio inserir uma imagem para criar a tua t-shirt.',
            'image.image' => 'O ficheiro carregado tem de ser uma imagem valida.',
            'image.max' => 'A imagem nao pode ter mais de 2MB.',
        ]);

        $imagePath = $request->file('image')->store('tshirt_images', 'public');
        $filename = basename($imagePath);

        $tshirtImage = TshirtImage::create([
            'customer_id' => $request->user()->id,
            'name' => 'T-Shirt Personalizada',
            'description' => 'T-shirt com imagem personalizada',
            'image_url' => $filename,
            'custom' => 1,
        ]);

        $cart = session()->get('cart', []);
        $customId = 'custom_' . $tshirtImage->id;

        $cart[$customId] = [
            'tshirt_image_id' => $tshirtImage->id,
            'name' => 'T-Shirt Personalizada',
            'image_url' => $filename,
            'description' => 'T-shirt com imagem personalizada',
            'size' => $request->size,
            'color_code' => $request->color_code,
            'quantity' => (int) $request->quantity,
            'is_custom' => true,
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'T-shirt personalizada adicionada ao carrinho.');
    }
}