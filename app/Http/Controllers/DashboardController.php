<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingOrders = Order::where('status', 'pending')->count();
        $closedOrders = Order::where('status', 'closed')->count();
        $canceledOrders = Order::where('status', 'canceled')->count();
        
        $totalRevenue = Order::where('status', 'closed')->sum('total_price');
        $totalClients = User::where('user_type', 'C')->count();
        
        $recentOrders = Order::orderBy('id', 'desc')->take(5)->get();

        return view('dashboard.index', compact('pendingOrders', 'closedOrders', 'canceledOrders', 'totalRevenue', 'totalClients', 'recentOrders'));
    }
}