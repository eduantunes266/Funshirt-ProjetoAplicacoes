<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Historico de encomendas do proprio cliente, com filtros e ordenacao opcionais.
     */
    public function index(Request $request): View
    {
        $query = Order::where('customer_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        match ($request->input('sort')) {
            'antiga' => $query->orderBy('date')->orderBy('id'),
            'cara'   => $query->orderByDesc('total_price'),
            'barata' => $query->orderBy('total_price'),
            default  => $query->orderByDesc('date')->orderByDesc('id'),
        };

        $orders = $query->paginate(10)->withQueryString();

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
