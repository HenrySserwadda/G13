<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rawmaterial;
use App\Models\Inventory;
use App\Models\RawMaterialOrder;
use Illuminate\Support\Carbon;
use App\Models\Product;

class StaffController extends Controller
{
    public function dashboard()
    {
        // Pending Material Orders
        $pendingMaterialOrders = RawMaterialOrder::where('status', 'pending')->count();
        // Low Stock Items
        $lowStockItems = Inventory::where('on_hand', '<=', 10)->count();
        // Recent Deliveries (last 7 days)
        $recentDeliveries = RawMaterialOrder::where('status', 'delivered')
            ->where('updated_at', '>=', now()->subDays(7))
            ->count();
        // Tasks Due (example: pending orders + low stock)
        $tasksDue = $pendingMaterialOrders + $lowStockItems;
        // Example tasks (replace with real tasks if you have a Task model)
        $tasks = collect([
            (object)[
                'title' => 'Approve incoming shipment',
                'due_date' => Carbon::now()->addHours(3),
                'category' => 'Inventory',
                'completed' => false,
            ],
            (object)[
                'title' => 'Update stock levels',
                'due_date' => Carbon::now()->addDay(),
                'category' => 'Maintenance',
                'completed' => false,
            ],
        ]);
        // Recent Activities (example: last 5 material orders)
        $recentActivities = RawMaterialOrder::orderBy('updated_at', 'desc')->take(5)->get()->map(function($order) {
            return (object) [
                'description' => 'Order #' . $order->id . ' was ' . $order->status,
                'icon' => $order->status === 'delivered' ? 'check-circle' : 'clipboard-list',
                'created_at' => $order->updated_at,
            ];
        });
        // Fetch products for staff dashboard
        $products = Product::latest()->paginate(12);
        return view('dashboard.staff', compact(
            'pendingMaterialOrders',
            'lowStockItems',
            'recentDeliveries',
            'tasksDue',
            'tasks',
            'recentActivities',
            'products' // Pass products to the view
        ));
    }
} 