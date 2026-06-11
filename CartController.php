<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Tamanhos válidos permitidos para as t-shirts
    private const VALID_SIZES = ['XS', 'S', 'M', 'L', 'XL'];

    // Mostra a página do carrinho
    public function index()
    {
        // Vai buscar o carrinho guardado na sessão
        $cart = session()->get('cart', []);

        // Vai buscar a regra de preços da base de dados
        $priceRule = Price::first();

        // Vai buscar todas as cores disponíveis, ordenadas por nome
        $colors = Color::orderBy('name')->get();

        // Guarda os tamanhos disponíveis
        $sizes = self::VALID_SIZES;

        // Total inicial do carrinho
        $total = 0;

        // Percorre todos os produtos do carrinho
        foreach ($cart as $key => $item) {
            $quantity = $item['quantity'];

            // Verifica se é uma t-shirt personalizada
            $isCustom = $item['is_custom'] ?? false;

            // Verifica se a quantidade tem direito a desconto
            $hasDiscount = $quantity >= $priceRule->qty_discount;

            // Define o preço unitário conforme o tipo de produto e se tem desconto
            if ($isCustom) {
                $unitPrice = $hasDiscount
                    ? $priceRule->unit_price_own_discount
                    : $priceRule->unit_price_own;
            } else {
                $unitPrice = $hasDiscount
                    ? $priceRule->unit_price_catalog_discount
                    : $priceRule->unit_price_catalog;
            }

            // Calcula o subtotal do produto
            $subtotal = $unitPrice * $quantity;

            // Soma ao total do carrinho
            $total += $subtotal;

            // Define a imagem a mostrar no carrinho
            $cart[$key]['display_image_url'] = $isCustom
                ? route('custom-images.file', $item['tshirt_id'])
                : asset('storage/tshirt_images/'.$item['image_url']);

            // Guarda dados calculados para mostrar na view
            $cart[$key]['unit_price'] = $unitPrice;
            $cart[$key]['subtotal'] = $subtotal;
            $cart[$key]['has_discount'] = $hasDiscount;
        }

        // Envia os dados para a view do carrinho
        return view('cart.index', compact('cart', 'total', 'colors', 'sizes', 'priceRule'));
    }

    // Adiciona uma t-shirt ao carrinho
    public function add(Request $request, $id)
    {
        // Valida os dados recebidos do formulário
        $request->validate([
            'size' => ['required', 'in:' . implode(',', self::VALID_SIZES)],
            'color_code' => ['required', 'string', 'exists:colors,code'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        // Procura a t-shirt pelo ID
        $tshirt = TshirtImage::findOrFail($id);

        // Se a t-shirt for personalizada, confirma se pertence ao utilizador autenticado
        if ($tshirt->customer_id !== null) {
            abort_unless(auth()->id() === $tshirt->customer_id, 403);
        }

        // Vai buscar o carrinho atual da sessão
        $cart = session()->get('cart', []);

        // Cria uma chave única com base na t-shirt, tamanho e cor
        $cartKey = $id . '_' . $request->size . '_' . $request->color_code;

        // Se o produto já existir no carrinho, apenas aumenta a quantidade
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            // Caso contrário, adiciona o produto ao carrinho
            $cart[$cartKey] = [
                'tshirt_id' => $id,
                'name' => $tshirt->name,
                'image_url' => $tshirt->image_url,
                'description' => $tshirt->description,
                'size' => $request->size,
                'color_code' => $request->color_code,
                'quantity' => (int) $request->quantity,
                'is_custom' => $tshirt->customer_id !== null,
            ];
        }

        // Guarda o carrinho atualizado na sessão
        session()->put('cart', $cart);

        // Volta para a página anterior com mensagem de sucesso
        return redirect()->back()->with('success', 'Produto adicionado ao carrinho.');
    }

    // Atualiza um item do carrinho
    public function update(Request $request, $key)
    {
        // Valida os dados enviados
        $request->validate([
            'size' => ['required', 'in:' . implode(',', self::VALID_SIZES)],
            'color_code' => ['required', 'string', 'exists:colors,code'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        // Vai buscar o carrinho da sessão
        $cart = session()->get('cart', []);

        // Se o item não existir, volta para trás
        if (!isset($cart[$key])) {
            return redirect()->back();
        }

        // Se a quantidade for 0, remove o item do carrinho
        if ((int) $request->quantity === 0) {
            unset($cart[$key]);
            session()->put('cart', $cart);

            return redirect()->back()->with('success', 'Item removido do carrinho.');
        }

        // Guarda o item antigo
        $item = $cart[$key];

        // Remove a versão antiga do item
        unset($cart[$key]);

        // Cria uma nova chave com o tamanho e cor atualizados
        $newKey = $item['tshirt_id'] . '_' . $request->size . '_' . $request->color_code;

        // Se já existir outro item igual, junta as quantidades
        if (isset($cart[$newKey])) {
            $cart[$newKey]['quantity'] += (int) $request->quantity;
        } else {
            // Caso contrário, atualiza os dados do item
            $item['size'] = $request->size;
            $item['color_code'] = $request->color_code;
            $item['quantity'] = (int) $request->quantity;

            // Guarda o item atualizado no carrinho
            $cart[$newKey] = $item;
        }

        // Guarda o carrinho atualizado na sessão
        session()->put('cart', $cart);

        // Volta para a página anterior
        return redirect()->back();
    }

    // Remove um item específico do carrinho
    public function remove($key)
    {
        // Vai buscar o carrinho da sessão
        $cart = session()->get('cart', []);

        // Se o item existir, remove-o
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        // Volta para a página anterior
        return redirect()->back();
    }

    // Limpa o carrinho inteiro
    public function clear()
    {
        // Remove o carrinho da sessão
        session()->forget('cart');

        // Volta para a página anterior
        return redirect()->back();
    }
}