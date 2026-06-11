<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    // Mostra a lista de encomendas do cliente autenticado
    public function index(Request $request)
    {
        // Cria uma query apenas com as encomendas do utilizador autenticado
        $query = Order::where('customer_id', $request->user()->id);

        // Filtra as encomendas por estado, se for selecionado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtra as encomendas a partir de uma data inicial
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        // Filtra as encomendas até uma data final
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Define a ordenação escolhida pelo utilizador
        // Caso não exista escolha, usa "recente"
        $sort = $request->input('sort', 'recente');

        // Aplica a ordenação conforme a opção selecionada
        match ($sort) {
            'antiga' => $query->orderBy('date', 'asc')->orderBy('id', 'asc'),
            'cara' => $query->orderBy('total_price', 'desc'),
            'barata' => $query->orderBy('total_price', 'asc'),
            default => $query->orderBy('date', 'desc')->orderBy('id', 'desc'),
        };

        // Pagina os resultados, mostrando 10 encomendas por página
        // Mantém os filtros aplicados na paginação
        $orders = $query->paginate(10)->withQueryString();

        // Envia as encomendas para a view
        return view('customer.orders.index', compact('orders'));
    }

    // Mostra os detalhes de uma encomenda específica
    public function show(Request $request, Order $order)
    {
        // Garante que o cliente só consegue ver as suas próprias encomendas
        abort_unless($order->customer_id === $request->user()->id, 403);

        // Vai buscar os itens da encomenda, incluindo a imagem da t-shirt associada
        $items = OrderItem::with('tshirtImage')
            ->where('order_id', $order->id)
            ->get();

        // Envia a encomenda e os respetivos itens para a view
        return view('customer.orders.show', compact('order', 'items'));
    }
}