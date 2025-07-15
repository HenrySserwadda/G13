<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class WholesalerDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Total orders
        $totalOrders = \App\Models\Order::where('user_id', $user->user_id)->count();

        // Total spent (only completed orders)
        $totalSpent = \App\Models\Order::where('user_id', $user->user_id)
            ->where('status', 'completed')
            ->sum('total');

        // Total inventory items
        $totalInventory = \App\Models\WholesalerRetailerInventory::where('user_id', $user->id)->sum('quantity');

        
        $recentOrders = \App\Models\Order::where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.wholesaler', compact('totalOrders', 'totalSpent', 'totalInventory', 'recentOrders'));
    }
} 