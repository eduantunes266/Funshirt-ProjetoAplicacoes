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

        foreach ($cart as $id => $item) {
            $quantity = $item['quantity'];
            $isCustom = isset($item['is_custom']) && $item['is_custom'];

            if ($quantity >= $priceRule->qty_discount) {
                $unitPrice = $isCustom ? $priceRule->unit_price_own_discount : $priceRule->unit_price_catalog_discount;
            } else {
                $unitPrice = $isCustom ? $priceRule->unit_price_own : $priceRule->unit_price_catalog;
            }

            $subtotal = $unitPrice * $quantity;
            $total += $subtotal;

        
            if ($isCustom) {
                $cart[$id]['display_image_url'] = 'placeholder_custom.png';
            } else {
                $cart[$id]['display_image_url'] = $item['image_url'];
            }
            // ----------------------------------------------------

            $cart[$id]['unit_price'] = $unitPrice;
            $cart[$id]['subtotal'] = $subtotal;
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, $id)
    {
        $tshirt = TshirtImage::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name' => $tshirt->name,
                'image_url' => $tshirt->image_url,
                'description' => $tshirt->description,
                'quantity' => 1
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back();
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }
}