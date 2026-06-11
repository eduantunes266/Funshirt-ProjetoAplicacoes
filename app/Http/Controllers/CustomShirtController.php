<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Http\Request;

class CustomShirtController extends Controller
{
    // Tamanhos válidos disponíveis para personalização
    private const VALID_SIZES = ['XS', 'S', 'M', 'L', 'XL'];

    // Mostra o formulário para criar uma t-shirt personalizada
    public function create()
    {
        // Apenas clientes podem aceder à personalização
        if (!request()->user()->isCustomer()) {
            return redirect()->route('home');
        }

        // Obtém os preços, cores e tamanhos disponíveis
        $price = Price::first();
        $colors = Color::orderBy('name')->get();
        $sizes = self::VALID_SIZES;

        // Envia os dados para a view
        return view('custom.create', compact('price', 'colors', 'sizes'));
    }

    // Guarda a t-shirt personalizada e adiciona-a ao carrinho
    public function store(Request $request)
    {
        // Apenas clientes podem criar t-shirts personalizadas
        if (!$request->user()->isCustomer()) {
            return redirect()->route('home');
        }

        // Validação dos dados enviados pelo formulário
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

        // Guarda a imagem enviada pelo utilizador em armazenamento privado
        $imagePath = $request->file('image')
            ->store('tshirt_images_private', 'local');

        // Cria o registo da imagem personalizada na base de dados
        $tshirtImage = TshirtImage::create([
            'customer_id' => $request->user()->id,
            'name' => 'T-Shirt Personalizada',
            'description' => 'T-shirt com imagem personalizada',
            'image_url' => $imagePath,
        ]);

        // Obtém o carrinho atual da sessão
        $cart = session()->get('cart', []);

        // Cria uma chave única para a t-shirt personalizada
        $customId = 'custom_' . $tshirtImage->id;

        // Adiciona a t-shirt personalizada ao carrinho
        $cart[$customId] = [
            'tshirt_image_id' => $tshirtImage->id,
            'name' => 'T-Shirt Personalizada',
            'image_url' => $imagePath,
            'description' => 'T-shirt com imagem personalizada',
            'size' => $request->size,
            'color_code' => $request->color_code,
            'quantity' => (int) $request->quantity,
            'is_custom' => true,
        ];

        // Guarda o carrinho atualizado na sessão
        session()->put('cart', $cart);

        // Redireciona para o carrinho com mensagem de sucesso
        return redirect()
            ->route('cart.index')
            ->with('success', 'T-shirt personalizada adicionada ao carrinho.');
    }
}