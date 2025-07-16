<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index(){
        $orders = Order::with('user')
            ->latest()
            ->paginate(15);
        return view('orders.manage-index', compact('orders'));
    }
    public function show($id){
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('orders.manage-show', compact('order'));
    }
    public function updateStatus(Request $request, $id){
        $request->validate([
            'status' => 'required| in:pending,approved, rejected,completed']);
        $order = Order::with('items')->findOrFail($id);
        // Only increment inventory if status is being set to completed and wasn't completed before
        if ($order->status !== 'completed' && $request->status === 'completed') {
            $inventoryController = new \App\Http\Controllers\WholesalerRetailerInventoryController();
            foreach ($order->items as $item) {
                $inventoryController->incrementInventory($order->user_id, $item->product_id, $item->quantity);
            }
        }
        $order->status = $request->status;
        $order->save();
        return redirect()->route('orders.manage.show', $order->id)->with('success', 'Order status updated!');
        
    }
}
