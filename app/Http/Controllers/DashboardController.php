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
    /**
     * Painel de estatisticas do negocio (apenas administradores).
     */
    public function index(): View
    {
        // --- Encomendas por estado (uma unica query) ---
        $ordersByStatus = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $pendingOrders  = (int) ($ordersByStatus['pending'] ?? 0);
        $closedOrders   = (int) ($ordersByStatus['closed'] ?? 0);
        $canceledOrders = (int) ($ordersByStatus['canceled'] ?? 0);
        $totalOrders    = $pendingOrders + $closedOrders + $canceledOrders;

        // --- Faturacao (apenas encomendas fechadas) ---
        $totalRevenue     = (float) Order::where('status', 'closed')->sum('total_price');
        $avgOrder         = (float) Order::where('status', 'closed')->avg('total_price');
        $revenueThisMonth = (float) Order::where('status', 'closed')
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('total_price');
        $revenueThisYear  = (float) Order::where('status', 'closed')
            ->whereYear('date', now()->year)
            ->sum('total_price');

        // --- Total de t-shirts vendidas (itens de encomendas fechadas) ---
        $tshirtsSold = (int) OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'closed')
            ->sum('order_items.qty');

        // --- Clientes e catalogo ---
        $totalClients    = User::where('user_type', 'C')->count();
        $catalogImages   = TshirtImage::whereNull('customer_id')->count();
        $totalCategories = Category::count();
        $totalColors     = Color::count();

        // --- Faturacao dos ultimos 12 meses ---
        $monthlyRaw = Order::where('status', 'closed')
            ->where('date', '>=', now()->subMonths(11)->startOfMonth()->toDateString())
            ->selectRaw("strftime('%Y-%m', date) as ym, sum(total_price) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyRevenue[] = [
                'label' => $month->format('m/Y'),
                'total' => (float) ($monthlyRaw[$month->format('Y-m')] ?? 0),
            ];
        }

        // --- Top 5 imagens mais vendidas ---
        $topImages = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('tshirt_images', 'tshirt_images.id', '=', 'order_items.tshirt_image_id')
            ->where('orders.status', 'closed')
            ->groupBy('tshirt_images.id', 'tshirt_images.name')
            ->selectRaw('tshirt_images.name as name, sum(order_items.qty) as qty, sum(order_items.sub_total) as revenue')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        // --- Top 5 categorias por faturacao ---
        $topCategories = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('tshirt_images', 'tshirt_images.id', '=', 'order_items.tshirt_image_id')
            ->join('categories', 'categories.id', '=', 'tshirt_images.category_id')
            ->where('orders.status', 'closed')
            ->groupBy('categories.id', 'categories.name')
            ->selectRaw('categories.name as name, sum(order_items.qty) as qty, sum(order_items.sub_total) as revenue')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // --- Top 5 clientes por valor gasto ---
        $topClients = Order::query()
            ->join('users', 'users.id', '=', 'orders.customer_id')
            ->where('orders.status', 'closed')
            ->groupBy('users.id', 'users.name')
            ->selectRaw('users.name as name, sum(orders.total_price) as total, count(*) as n')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // --- Ultimas 5 encomendas ---
        $recentOrders = Order::orderByDesc('id')->limit(5)->get();

        return view('dashboard.index', compact(
            'pendingOrders', 'closedOrders', 'canceledOrders', 'totalOrders',
            'totalRevenue', 'avgOrder', 'revenueThisMonth', 'revenueThisYear',
            'tshirtsSold', 'totalClients', 'catalogImages', 'totalCategories', 'totalColors',
            'monthlyRevenue', 'topImages', 'topCategories', 'topClients', 'recentOrders'
        ));
    }
}
