<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private const VALID_SIZES = ['XS', 'S', 'M', 'L', 'XL'];

    public function index()
    {
        $cart = session()->get('cart', []);
        $priceRule = Price::first();
        $colors = Color::orderBy('name')->get();
        $sizes = self::VALID_SIZES;
        $total = 0;

        foreach ($cart as $key => $item) {
            $quantity = $item['quantity'];
            $isCustom = $item['is_custom'] ?? false;
            $hasDiscount = $quantity >= $priceRule->qty_discount;

            if ($isCustom) {
                $unitPrice = $hasDiscount ? $priceRule->unit_price_own_discount : $priceRule->unit_price_own;
            } else {
                $unitPrice = $hasDiscount ? $priceRule->unit_price_catalog_discount : $priceRule->unit_price_catalog;
            }

            $subtotal = $unitPrice * $quantity;
            $total += $subtotal;

            $cart[$key]['display_image_url'] = $isCustom
                ? route('custom-images.file', $item['tshirt_id'])
                : asset('storage/tshirt_images/'.$item['image_url']);
            $cart[$key]['unit_price'] = $unitPrice;
            $cart[$key]['subtotal'] = $subtotal;
            $cart[$key]['has_discount'] = $hasDiscount;
        }

        return view('cart.index', compact('cart', 'total', 'colors', 'sizes', 'priceRule'));
    }

    public function add(Request $request, $id)
    {
        $request->validate([
            'size' => ['required', 'in:' . implode(',', self::VALID_SIZES)],
            'color_code' => ['required', 'string', 'exists:colors,code'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $tshirt = TshirtImage::findOrFail($id);

        if ($tshirt->customer_id !== null) {
            abort_unless(auth()->id() === $tshirt->customer_id, 403);
        }

        $cart = session()->get('cart', []);

        $cartKey = $id . '_' . $request->size . '_' . $request->color_code;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
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

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produto adicionado ao carrinho.');
    }

    public function update(Request $request, $key)
    {
        $request->validate([
            'size' => ['required', 'in:' . implode(',', self::VALID_SIZES)],
            'color_code' => ['required', 'string', 'exists:colors,code'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$key])) {
            return redirect()->back();
        }

        if ((int) $request->quantity === 0) {
            unset($cart[$key]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Item removido do carrinho.');
        }

        $item = $cart[$key];
        unset($cart[$key]);

        $newKey = $item['tshirt_id'] . '_' . $request->size . '_' . $request->color_code;

        if (isset($cart[$newKey])) {
            $cart[$newKey]['quantity'] += (int) $request->quantity;
        } else {
            $item['size'] = $request->size;
            $item['color_code'] = $request->color_code;
            $item['quantity'] = (int) $request->quantity;
            $cart[$newKey] = $item;
        }

        session()->put('cart', $cart);

        return redirect()->back();
    }

    public function remove($key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect()->back();
    }
}
