<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Mail\OrderPendingMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Price;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Formulario de checkout, pre-preenchido com os dados do perfil do cliente.
     */
    public function index(): View|RedirectResponse
    {
        if (empty(session('cart', []))) {
            return redirect()->route('cart.index');
        }

        return view('checkout.index', [
            'customer' => auth()->user()->customer,
        ]);
    }

    /**
     * Finaliza a encomenda: valida, processa o pagamento na plataforma externa
     * e, so em caso de sucesso, regista a encomenda no estado "pending".
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $priceRule = Price::first();
        $total = 0;
        $itemsToSave = [];

        foreach ($cart as $item) {
            $quantity = (int) $item['quantity'];
            $isCustom = $item['is_custom'] ?? false;
            $hasDiscount = $quantity >= $priceRule->qty_discount;

            if ($isCustom) {
                $unitPrice = $hasDiscount ? $priceRule->unit_price_own_discount : $priceRule->unit_price_own;
            } else {
                $unitPrice = $hasDiscount ? $priceRule->unit_price_catalog_discount : $priceRule->unit_price_catalog;
            }

            $subtotal = $unitPrice * $quantity;
            $total += $subtotal;

            $itemsToSave[] = [
                'tshirt_image_id' => $item['tshirt_id'],
                'qty' => $quantity,
                'unit_price' => $unitPrice,
                'sub_total' => $subtotal,
                'color_code' => $item['color_code'],
                'size' => $item['size'],
            ];
        }

        // Pagamento simulado na plataforma externa (enunciado, seccao 7).
        $paymentError = $this->processPayment($request->payment_type, $request->payment_ref, $total);
        if ($paymentError !== null) {
            return back()->withInput()->with('error', $paymentError);
        }

        $customerId = auth()->id();

        $order = DB::transaction(function () use ($request, $customerId, $total, $itemsToSave) {
            $order = Order::create([
                'customer_id' => $customerId,
                'status' => 'pending',
                'date' => now()->toDateString(),
                'total_price' => $total,
                'nif' => $request->nif,
                'address' => $request->address,
                'payment_type' => $request->payment_type,
                'payment_ref' => $request->payment_ref,
                'notes' => $request->notes,
            ]);

            foreach ($itemsToSave as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            return $order;
        });

        // O email nao deve fazer falhar a encomenda (servico externo - mailtrap).
        try {
            Mail::to(auth()->user()->email)->send(new OrderPendingMail($order));
        } catch (\Throwable $e) {
            report($e);
        }

        session()->forget('cart');

        return redirect()->route('checkout.success');
    }

    public function success(): View
    {
        return view('checkout.success');
    }

    /**
     * Envia o pedido de pagamento. Devolve null em caso de sucesso (201) ou,
     * caso contrario, a mensagem de erro a apresentar ao cliente.
     */
    private function processPayment(string $type, string $reference, float $value): ?string
    {
        try {
            $response = Http::acceptJson()
                ->post(rtrim(config('services.payments.url'), '/').'/api/payments', [
                    'type' => $type,
                    'reference' => $reference,
                    'value' => round($value, 2),
                ]);

            if ($response->created()) {
                return null;
            }

            $apiMessage = $response->json('message');

            return 'Pagamento recusado: '.($apiMessage ?: 'verifique os dados de pagamento e tente novamente.');
        } catch (\Throwable $e) {
            report($e);

            return 'Nao foi possivel contactar a plataforma de pagamentos. Tente novamente.';
        }
    }
}
