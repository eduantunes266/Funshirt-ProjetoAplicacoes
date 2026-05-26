<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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

        $customer = Auth::check() && Auth::user()->isCustomer() ? Auth::user()->customer : null;

        return view('checkout.index', compact('customer'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isCustomer()) {
            return redirect()->route('login')->with('status', 'Tem de iniciar sessão como cliente para concluir a compra.');
        }

        $user = Auth::user();

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
            'notes' => 'nullable|string'
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back();
        }

        $priceRule = Price::first();
        $total = 0;
        $itemsToSave = [];

        foreach ($cart as $id => $item) {
            $quantity = $item['quantity'] ?? $item['qty'];
            $isCustom = $item['is_custom'] ?? false;

            $hasDiscount = $quantity >= $priceRule->qty_discount;

            if ($isCustom) {
                $unitPrice = $hasDiscount ? $priceRule->unit_price_own_discount : $priceRule->unit_price_own;
            } else {
                $unitPrice = $hasDiscount ? $priceRule->unit_price_catalog_discount : $priceRule->unit_price_catalog;
            }

            $subtotal = $unitPrice * $quantity;
            $total += $subtotal;

            if ($isCustom || str_starts_with((string) $id, 'custom_')) {
                $tshirtImage = new TshirtImage();
                $tshirtImage->customer_id = $user->id;
                $tshirtImage->name = $item['name'] ?? 'Custom Design';
                $tshirtImage->description = $item['description'] ?? '';
                $tshirtImage->image_url = $item['image_url'];
                $tshirtImage->save();
                $tshirtImageId = $tshirtImage->id;
            } else {
                $tshirtImageId = $item['tshirt_id'] ?? $item['tshirt_image_id'] ?? explode('_', (string) $id)[0];
            }

            $itemsToSave[] = [
                'tshirt_image_id' => $tshirtImageId,
                'qty' => $quantity,
                'unit_price' => $unitPrice,
                'sub_total' => $subtotal,
                'color_code' => $item['color_code'] ?? 'ac283b',
                'size' => $item['size'] ?? 'M',
            ];
        }

        $paymentResponse = Http::post('https://ainet-payments-api.vercel.app/api/payments', [
            'type' => $request->payment_type,
            'reference' => $request->payment_ref,
            'value' => $total,
        ]);

        if (!$paymentResponse->successful()) {
            $mensagemErro = 'Falha na transação.';
            $paymentData = $paymentResponse->json();
            
            if (is_array($paymentData) && array_key_exists('message', $paymentData)) {
                $mensagemErro = $paymentData['message'];
            }
            
            return back()->withInput()->withErrors(['payment_error' => 'Pagamento rejeitado: ' . $mensagemErro]);
        }

        $order = new Order();
        $order->customer_id = $user->id;
        $order->status = 'pending';
        $order->date = now()->toDateString();
        $order->total_price = $total;
        $order->nif = $request->nif;
        $order->address = $request->address;
        $order->payment_type = $request->payment_type;
        $order->payment_ref = $request->payment_ref;
        $order->notes = $request->notes;
        $order->save();

        foreach ($itemsToSave as $itemData) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->tshirt_image_id = $itemData['tshirt_image_id'];
            $orderItem->color_code = $itemData['color_code'];
            $orderItem->size = $itemData['size'];
            $orderItem->qty = $itemData['qty'];
            $orderItem->unit_price = $itemData['unit_price'];
            $orderItem->sub_total = $itemData['sub_total'];
            $orderItem->save();
        }

        Mail::to($user->email)->send(new OrderPendingMail($order));

        session()->forget('cart');

        return redirect()->route('checkout.success');
    }

    public function success()
    {
        return view('checkout.success');
    }
}