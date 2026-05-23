<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TshirtImage;
use App\Models\Price;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $priceRule = Price::first();
        $total = 0;

        foreach ($cart as $key => $item) {
            $quantity = $item['quantity'];
            $isCustom = $item['is_custom'] ?? false;

            if ($quantity >= $priceRule->qty_discount) {
                $unitPrice = $isCustom ? $priceRule->unit_price_own_discount : $priceRule->unit_price_catalog_discount;
            } else {
                $unitPrice = $isCustom ? $priceRule->unit_price_own : $priceRule->unit_price_catalog;
            }

            $subtotal = $unitPrice * $quantity;
            $total += $subtotal;

            $cart[$key]['display_image_url'] = $isCustom ? 'placeholder_custom.png' : $item['image_url'];
            $cart[$key]['unit_price'] = $unitPrice;
            $cart[$key]['subtotal'] = $subtotal;
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|string',
            'color' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $tshirt = TshirtImage::findOrFail($id);
        $cart = session()->get('cart', []);

        $cartKey = $id . '_' . $request->size . '_' . $request->color;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'tshirt_id' => $id,
                'name' => $tshirt->name,
                'image_url' => $tshirt->image_url,
                'description' => $tshirt->description,
                'size' => $request->size,
                'color' => $request->color,
                'quantity' => $request->quantity,
                'is_custom' => false,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }

    public function update(Request $request, $key)
    {
        $request->validate([
            'size' => 'required|string',
            'color' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$key])) {
            return redirect()->back();
        }

        $item = $cart[$key];

        unset($cart[$key]);

        $newKey = $item['tshirt_id'] . '_' . $request->size . '_' . $request->color;

        if (isset($cart[$newKey])) {
            $cart[$newKey]['quantity'] += $request->quantity;
        } else {
            $item['size'] = $request->size;
            $item['color'] = $request->color;
            $item['quantity'] = $request->quantity;

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