<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPendingMail;
use App\Models\User;
class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back();
        }

        return view('checkout.index');
    }

    public function store(Request $request)
    {
      $request->validate([
            'nif' => 'required|string|size:9',
            'address' => 'required|string|max:255',
            'payment_type' => 'required|in:Visa,PayPal,MB WAY',
            'payment_ref' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $type = $request->payment_type;
                    if ($type === 'MB WAY' && !preg_match('/^9[0-9]{8}$/', $value)) {
                        $fail('A referência MB WAY deve ser um número de telemóvel com 9 dígitos começado por 9.');
                    }
                    if ($type === 'Visa' && !preg_match('/^4[0-9]{15}$/', $value)) {
                        $fail('O cartão Visa deve ter 16 dígitos e começar por 4.');
                    }
                    if ($type === 'PayPal' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('A referência PayPal deve ser um endereço de email válido.');
                    }
                },
            ],
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back();
        }

        $customerId = 22;
        if (auth()->check()) {
            $customerId = auth()->id();
        }

        $priceRule = Price::first();
        $total = 0;
        $itemsToSave = [];

        foreach ($cart as $id => $item) {
            $quantity = $item['quantity'];
            
            $isCustom = false;
            if (isset($item['is_custom'])) {
                if ($item['is_custom']) {
                    $isCustom = true;
                }
            }

            if ($quantity >= $priceRule->qty_discount) {
                if ($isCustom) {
                    $unitPrice = $priceRule->unit_price_own_discount;
                } else {
                    $unitPrice = $priceRule->unit_price_catalog_discount;
                }
            } else {
                if ($isCustom) {
                    $unitPrice = $priceRule->unit_price_own;
                } else {
                    $unitPrice = $priceRule->unit_price_catalog;
                }
            }

            $subtotal = $unitPrice * $quantity;
            $total += $subtotal;

            $tshirtImageId = null;
            if (!str_starts_with($id, 'custom_')) {
                $tshirtImageId = $id;
            } else {
                $newImage = TshirtImage::create([
                    'customer_id' => $customerId,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'image_url' => $item['image_url'],
                ]);
                $tshirtImageId = $newImage->id;
            }

            $colorCode = 'ac283b';
            if (isset($item['color'])) {
                $colorCode = $item['color'];
            }

            $size = 'M';
            if (isset($item['size'])) {
                $size = $item['size'];
            }

            $itemsToSave[] = [
                'tshirt_image_id' => $tshirtImageId,
                'qty' => $quantity,
                'unit_price' => $unitPrice,
                'sub_total' => $subtotal,
                'color_code' => $colorCode,
                'size' => $size,
            ];
        }

        

        $order = Order::create([
            'customer_id' => $customerId, 
            'status' => 'pending',
            'date' => now()->toDateString(), 
            'total_price' => $total,
            'nif' => $request->nif,
            'address' => $request->address,
            'payment_type' => $request->payment_type,
            'payment_ref' => $request->payment_ref,
        ]);

        
        foreach ($itemsToSave as $itemData) {
            $itemData['order_id'] = $order->id;
            OrderItem::create($itemData);
        }

        $user = User::find($customerId);
        if ($user) {
            Mail::to($user->email)->send(new OrderPendingMail($order));
        }

        session()->forget('cart');
        session()->forget('cart');

        return redirect()->route('checkout.success');
    }

    public function success()
    {
        return view('checkout.success');
    }
}