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
    // Mostra a página de checkout
    public function index(): View|RedirectResponse
    {
        // Se o carrinho estiver vazio, volta para a página do carrinho
        if (empty(session('cart', []))) {
            return redirect()->route('cart.index');
        }

        // Mostra a página de checkout com os dados do cliente autenticado
        return view('checkout.index', [
            'customer' => auth()->user()->customer,
        ]);
    }

    // Guarda a encomenda depois do checkout
    public function store(CheckoutRequest $request): RedirectResponse
    {
        // Vai buscar o carrinho guardado na sessão
        $cart = session('cart', []);

        // Se o carrinho estiver vazio, volta para o carrinho
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        // Vai buscar as regras de preço
        $priceRule = Price::first();

        // Total inicial da encomenda
        $total = 0;

        // Array temporário para guardar os itens da encomenda
        $itemsToSave = [];

        // Percorre todos os produtos do carrinho
        foreach ($cart as $item) {
            // Quantidade do produto
            $quantity = (int) $item['quantity'];

            // Verifica se é uma t-shirt personalizada
            $isCustom = $item['is_custom'] ?? false;

            // Verifica se a quantidade tem desconto
            $hasDiscount = $quantity >= $priceRule->qty_discount;

            // Define o preço unitário conforme o tipo de produto e desconto
            if ($isCustom) {
                $unitPrice = $hasDiscount
                    ? $priceRule->unit_price_own_discount
                    : $priceRule->unit_price_own;
            } else {
                $unitPrice = $hasDiscount
                    ? $priceRule->unit_price_catalog_discount
                    : $priceRule->unit_price_catalog;
            }

            // Calcula subtotal do item
            $subtotal = $unitPrice * $quantity;

            // Soma ao total da encomenda
            $total += $subtotal;

            // Guarda os dados do item para serem inseridos na base de dados
            $itemsToSave[] = [
                'tshirt_image_id' => $item['tshirt_id'],
                'qty' => $quantity,
                'unit_price' => $unitPrice,
                'sub_total' => $subtotal,
                'color_code' => $item['color_code'],
                'size' => $item['size'],
            ];
        }

        // Tenta processar o pagamento através da plataforma externa
        $paymentError = $this->processPayment(
            $request->payment_type,
            $request->payment_ref,
            $total
        );

        // Se houver erro no pagamento, volta ao checkout com mensagem de erro
        if ($paymentError !== null) {
            return back()->withInput()->with('error', $paymentError);
        }

        // ID do utilizador autenticado
        $customerId = auth()->id();

        // Cria a encomenda e os itens dentro de uma transação
        $order = DB::transaction(function () use ($request, $customerId, $total, $itemsToSave) {
            // Cria a encomenda principal
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

            // Cria os itens associados à encomenda
            foreach ($itemsToSave as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            // Devolve a encomenda criada
            return $order;
        });

        // Tenta enviar email ao cliente a informar que a encomenda está pendente
        try {
            Mail::to(auth()->user()->email)->send(new OrderPendingMail($order));
        } catch (\Throwable $e) {
            // Se o email falhar, apenas regista o erro
            report($e);
        }

        // Limpa o carrinho depois da encomenda ser criada
        session()->forget('cart');

        // Redireciona para a página de sucesso
        return redirect()->route('checkout.success');
    }

    // Mostra a página de sucesso do checkout
    public function success(): View
    {
        return view('checkout.success');
    }

    // Processa o pagamento através da API externa de pagamentos
    private function processPayment(string $type, string $reference, float $value): ?string
    {
        try {
            // Envia os dados de pagamento para a API externa
            $response = Http::acceptJson()
                ->post(rtrim(config('services.payments.url'), '/').'/api/payments', [
                    'type' => $type,
                    'reference' => $reference,
                    'value' => round($value, 2),
                ]);

            // Se a API devolver estado 201 Created, o pagamento foi aceite
            if ($response->created()) {
                return null;
            }

            // Vai buscar a mensagem de erro enviada pela API
            $apiMessage = $response->json('message');

            // Devolve mensagem de erro para mostrar ao utilizador
            return 'Pagamento recusado: '.($apiMessage ?: 'verifique os dados de pagamento e tente novamente.');
        } catch (\Throwable $e) {
            // Regista erro caso não consiga contactar a API
            report($e);

            // Devolve mensagem amigável para o utilizador
            return 'Nao foi possivel contactar a plataforma de pagamentos. Tente novamente.';
        }
    }
}