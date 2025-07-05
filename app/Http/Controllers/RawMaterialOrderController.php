<?php

namespace App\Http\Controllers;

use App\Models\RawMaterialOrder;
use App\Models\RawMaterialOrderItem;
use App\Models\RawMaterial;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RawMaterialOrderController extends Controller
{
    /**
     * Show the form for editing the specified order (for suppliers to update status).
     */
    public function edit($id)
    {
        $user = Auth::user();
        $order = RawMaterialOrder::with(['items.rawMaterial', 'user'])
            ->where('id', $id)
            ->where('supplier_user_id', $user->id)
            ->firstOrFail();
        return view('raw_material_orders.edit', compact('order'));
    }

    /**
     * Update the status of the specified order (for suppliers).
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $order = RawMaterialOrder::with('items.rawMaterial')->where('id', $id)
            ->where('supplier_user_id', $user->id)
            ->firstOrFail();
        
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        try {
            DB::beginTransaction();

            // Only reduce quantities if status is being changed to 'completed' and wasn't completed before
            if ($oldStatus !== 'completed' && $newStatus === 'completed') {
                foreach ($order->items as $item) {
                    $rawMaterial = $item->rawMaterial;
                    if ($rawMaterial) {
                        // Check if we have enough quantity
                        if ($rawMaterial->quantity < $item->quantity) {
                            DB::rollBack();
                            return redirect()->back()->with('error', 'Insufficient quantity for ' . $rawMaterial->name . '. Available: ' . $rawMaterial->quantity . ', Required: ' . $item->quantity);
                        }
                        
                        // Reduce the quantity from supplier's inventory
                        $rawMaterial->quantity = $rawMaterial->quantity - $item->quantity;
                        $rawMaterial->save();

                        // Create or update inventory record for the system admin (order placer)
                        $existingInventory = Inventory::where('raw_material_id', $rawMaterial->id)
                            ->where('user_id', $order->user_id)
                            ->first();

                        if ($existingInventory) {
                            // Update existing inventory record
                            $existingInventory->on_hand += $item->quantity;
                            $existingInventory->delivery_status = 'received';
                            $existingInventory->delivered_on = now();
                            $existingInventory->save();
                        } else {
                            // Create new inventory record
                            Inventory::create([
                                'raw_material_id' => $rawMaterial->id,
                                'user_id' => $order->user_id,
                                'on_hand' => $item->quantity,
                                'on_order' => 0,
                                'stock_status' => 'in_stock',
                                'delivery_status' => 'received',
                                'delivered_on' => now(),
                                'expected_delivery' => null,
                            ]);
                        }
                    }
                }
            }

            $order->status = $newStatus;
            $order->save();

            DB::commit();

            $message = 'Order status updated!';
            if ($oldStatus !== 'completed' && $newStatus === 'completed') {
                $message .= ' Raw material quantities have been reduced and inventory records have been created.';
                // Add a session message for the system admin to see in their inventory
                session()->flash('inventory_added_from_order', 'Inventory has been updated from completed order #' . $order->id);
            }

            return redirect()->route('raw-material-orders.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while updating the order status. Please try again.');
        }
    }
    public function index()
    {
        $user = Auth::user();
        if ($user->category === 'supplier') {
            $orders = RawMaterialOrder::where('supplier_user_id', $user->id)
                ->whereHas('user', function($q) {
                    $q->whereIn('category', ['systemadmin', 'staff']);
                })
                ->with('user')
                ->latest()
                ->get();
        } else {
            $orders = RawMaterialOrder::where('user_id', $user->id)
                ->with('supplier')
                ->latest()
                ->get();
        }
        return view('raw_material_orders.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = User::where('category', 'supplier')->get();
        $rawMaterials = RawMaterial::all();
        return view('raw_material_orders.create', compact('suppliers', 'rawMaterials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $order = RawMaterialOrder::create([
            'supplier_user_id' => $request->supplier_user_id,
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        foreach ($request->items as $item) {
            RawMaterialOrderItem::create([
                'raw_material_order_id' => $order->id,
                'raw_material_id' => $item['raw_material_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return redirect()->route('raw-material-orders.index')->with('success', 'Order placed!');
    }

    public function show($id)
    {
        $order = RawMaterialOrder::with(['items.rawMaterial', 'supplier'])->findOrFail($id);
        return view('raw_material_orders.show', compact('order'));
    }
}