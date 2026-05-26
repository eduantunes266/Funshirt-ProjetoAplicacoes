<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
public function index(Request $request)
{
    $query = Order::where('customer_id', $request->user()->id);

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('start_date')) {
        $query->whereDate('date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('date', '<=', $request->end_date);
    }

    $sort = $request->input('sort', 'recente');
    
    match ($sort) {
        'antiga' => $query->orderBy('date', 'asc')->orderBy('id', 'asc'),
        'cara' => $query->orderBy('total_price', 'desc'),
        'barata' => $query->orderBy('total_price', 'asc'),
        default => $query->orderBy('date', 'desc')->orderBy('id', 'desc'),
    };

    $orders = $query->paginate(10)->withQueryString();

    return view('customer.orders.index', compact('orders'));
}
    public function show(Request $request, Order $order)
    {
        abort_unless($order->customer_id === $request->user()->id, 403);

        $items = OrderItem::with('tshirtImage')
            ->where('order_id', $order->id)
            ->get();

        return view('customer.orders.show', compact('order', 'items'));
    }
}