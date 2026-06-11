<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TshirtImage;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    // Mostra o painel administrativo com estatísticas da loja
    public function index(): View
    {
        // Conta o número de encomendas agrupadas por estado
        $ordersByStatus = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Guarda o total de encomendas por estado
        $pendingOrders  = (int) ($ordersByStatus['pending'] ?? 0);
        $closedOrders   = (int) ($ordersByStatus['closed'] ?? 0);
        $canceledOrders = (int) ($ordersByStatus['canceled'] ?? 0);

        // Calcula o total de encomendas
        $totalOrders = $pendingOrders + $closedOrders + $canceledOrders;

        // Calcula o total faturado apenas com encomendas fechadas
        $totalRevenue = (float) Order::where('status', 'closed')->sum('total_price');

        // Calcula o valor médio das encomendas fechadas
        $avgOrder = (float) Order::where('status', 'closed')->avg('total_price');

        // Calcula a faturação do mês atual
        $revenueThisMonth = (float) Order::where('status', 'closed')
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('total_price');

        // Calcula a faturação do ano atual
        $revenueThisYear = (float) Order::where('status', 'closed')
            ->whereYear('date', now()->year)
            ->sum('total_price');

        // Calcula o número total de t-shirts vendidas em encomendas fechadas
        $tshirtsSold = (int) OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'closed')
            ->sum('order_items.qty');

        // Conta o número total de clientes
        $totalClients = User::where('user_type', 'C')->count();

        // Conta o número de imagens do catálogo
        $catalogImages = TshirtImage::whereNull('customer_id')->count();

        // Conta o número total de categorias
        $totalCategories = Category::count();

        // Conta o número total de cores
        $totalColors = Color::count();

        // Obtém a faturação mensal dos últimos 12 meses
        $monthlyRaw = Order::where('status', 'closed')
            ->where('date', '>=', now()->subMonths(11)->startOfMonth()->toDateString())
            ->selectRaw("strftime('%Y-%m', date) as ym, sum(total_price) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym');

        // Prepara os dados mensais para apresentar no gráfico
        $monthlyRevenue = [];

        // Garante que todos os últimos 12 meses aparecem,
        // mesmo que algum mês não tenha vendas
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);

            $monthlyRevenue[] = [
                'label' => $month->format('m/Y'),
                'total' => (float) ($monthlyRaw[$month->format('Y-m')] ?? 0),
            ];
        }

        // Obtém as 5 imagens/t-shirts mais vendidas
        $topImages = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('tshirt_images', 'tshirt_images.id', '=', 'order_items.tshirt_image_id')
            ->where('orders.status', 'closed')
            ->groupBy('tshirt_images.id', 'tshirt_images.name')
            ->selectRaw(
                'tshirt_images.name as name, 
                 sum(order_items.qty) as qty, 
                 sum(order_items.sub_total) as revenue'
            )
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        // Obtém as 5 categorias com maior faturação
        $topCategories = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('tshirt_images', 'tshirt_images.id', '=', 'order_items.tshirt_image_id')
            ->join('categories', 'categories.id', '=', 'tshirt_images.category_id')
            ->where('orders.status', 'closed')
            ->groupBy('categories.id', 'categories.name')
            ->selectRaw(
                'categories.name as name, 
                 sum(order_items.qty) as qty, 
                 sum(order_items.sub_total) as revenue'
            )
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // Obtém os 5 clientes que mais gastaram
        $topClients = Order::query()
            ->join('users', 'users.id', '=', 'orders.customer_id')
            ->where('orders.status', 'closed')
            ->groupBy('users.id', 'users.name')
            ->selectRaw(
                'users.name as name, 
                 sum(orders.total_price) as total, 
                 count(*) as n'
            )
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Obtém as 5 encomendas mais recentes
        $recentOrders = Order::orderByDesc('id')
            ->limit(5)
            ->get();

        // Envia todos os dados calculados para a view do dashboard
        return view('dashboard.index', compact(
            'pendingOrders',
            'closedOrders',
            'canceledOrders',
            'totalOrders',
            'totalRevenue',
            'avgOrder',
            'revenueThisMonth',
            'revenueThisYear',
            'tshirtsSold',
            'totalClients',
            'catalogImages',
            'totalCategories',
            'totalColors',
            'monthlyRevenue',
            'topImages',
            'topCategories',
            'topClients',
            'recentOrders'
        ));
    }
}