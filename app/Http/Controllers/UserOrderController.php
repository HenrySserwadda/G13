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
        $orders = Order::with('items.product')->where('user_id', Auth::id())->latest()->paginate(10);
        return view('orders.user-index', compact('orders'));
    }

    // Show a single order
    public function show($id)
    {
        $order = Order::with('items.product')->where('user_id', Auth::id())->findOrFail($id);
        return view('orders.user-show', compact('order'));
    }
}
