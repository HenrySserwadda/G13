<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    // List orders for the logged-in user (wholesaler/retailer)
    public function index()
    {
        $auth = Auth::user();
        $orders = Order::with('items.product')
            ->where('user_id', $auth->id)
            ->orWhere('user_id', $auth->user_id)
            ->latest()
            ->paginate(10);
        return view('orders.user-index', compact('orders'));
    }

    // Show a single order
    public function show($id)
    {
        $auth = Auth::user();
        $order = Order::with('items.product')
            ->where(function($query) use ($auth) {
                $query->where('user_id', $auth->id)
                      ->orWhere('user_id', $auth->user_id);
            })
            ->findOrFail($id);
        return view('orders.user-show', compact('order'));
    }
}
