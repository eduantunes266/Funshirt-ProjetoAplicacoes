<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Historico de encomendas do proprio cliente.
     */
    public function index(): View
    {
        $orders = Order::where('customer_id', auth()->id())
            ->orderByDesc('id')
            ->paginate(10);

        return view('orders.my-index', compact('orders'));
    }

    /**
     * Detalhe de uma encomenda do proprio cliente.
     */
    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load('items.tshirtImage');

        return view('orders.my-show', compact('order'));
    }
}
