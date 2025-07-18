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
            
            // Process order to sales table
            $this->processOrderToSales($order);
        }
        $order->status = $request->status;
        $order->save();
        return redirect()->route('orders.manage.show', $order->id)->with('success', 'Order status updated!');
        
    }

    private function processOrderToSales($order)
    {
        if (!$order->supply_center_id) {
            return; // Skip if no supply center assigned
        }

        try {
            $salesMonth = $order->updated_at->format('Y-m');
            $supplyCenterId = $order->supply_center_id;

            // Check if sales record already exists for this supply center and month
            $existingSale = \App\Models\Sale::where('supply_center_id', $supplyCenterId)
                                           ->where('sales_month', $salesMonth . '-01')
                                           ->first();

            if ($existingSale) {
                // Update existing sales record
                $existingSale->monthly_sales += $order->total;
                $existingSale->save();
            } else {
                // Create new sales record
                \App\Models\Sale::create([
                    'supply_center_id' => $supplyCenterId,
                    'monthly_sales' => $order->total,
                    'sales_month' => $salesMonth . '-01',
                    'order_id' => $order->id,
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the order status update
            \Log::error("Error processing order {$order->id} to sales: " . $e->getMessage());
        }
    }

    public function updateSupplyCenter(Request $request, $id)
    {
        $request->validate([
            'supply_center_id' => 'nullable|exists:supply_centers,id'
        ]);

        $order = Order::findOrFail($id);
        $order->supply_center_id = $request->supply_center_id ?: null;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Supply center updated successfully'
        ]);
    }
}
