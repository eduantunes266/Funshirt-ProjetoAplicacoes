<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Histórico de encomendas do próprio cliente,
     * com filtros e ordenação opcionais.
     */
    public function index(Request $request): View
    {
        // Cria uma query apenas com as encomendas do utilizador autenticado
        $query = Order::where('customer_id', auth()->id());

        // Filtra por estado da encomenda, se for selecionado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtra encomendas a partir de uma data inicial
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        // Filtra encomendas até uma data final
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Ordena as encomendas conforme a opção escolhida
        match ($request->input('sort')) {
            'antiga' => $query->orderBy('date')->orderBy('id'),
            'cara'   => $query->orderByDesc('total_price'),
            'barata' => $query->orderBy('total_price'),
            default  => $query->orderByDesc('date')->orderByDesc('id'),
        };

        // Pagina os resultados, mantendo os filtros na URL
        $orders = $query->paginate(10)->withQueryString();

        // Envia as encomendas para a view
        return view('orders.my-index', compact('orders'));
    }

    /**
     * Detalhe de uma encomenda do próprio cliente.
     */
    public function show(Order $order): View
    {
        // Verifica se o utilizador tem permissão para ver esta encomenda
        $this->authorize('view', $order);

        // Carrega os itens da encomenda e a imagem da t-shirt associada
        $order->load('items.tshirtImage');

        // Envia a encomenda para a view de detalhe
        return view('orders.my-show', compact('order'));
    }
}