<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\WholesalerRetailerInventory;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WholesalerRetailerInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // List all inventory items for the logged-in wholesaler/retailer
        $inventories = WholesalerRetailerInventory::where('user_id', Auth::id())->with('product')->get();
        return view('wholesaler_retailer_inventory.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show form to add a new inventory item
        $products = Product::all();
        return view('wholesaler_retailer_inventory.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
            'stock_status' => 'required|string',
        ]);
        WholesalerRetailerInventory::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'stock_status' => $request->stock_status,
        ]);
        return redirect()->route('wholesaler-retailer-inventory.index')->with('success', 'Inventory item added!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $inventory = WholesalerRetailerInventory::where('user_id', Auth::id())->with('product')->findOrFail($id);
        return view('wholesaler_retailer_inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $inventory = WholesalerRetailerInventory::where('user_id', Auth::id())->findOrFail($id);
        $products = Product::all();
        return view('wholesaler_retailer_inventory.edit', compact('inventory', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $inventory = WholesalerRetailerInventory::where('user_id', Auth::id())->findOrFail($id);
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'stock_status' => 'required|string',
        ]);
        $inventory->update([
            'quantity' => $request->quantity,
            'stock_status' => $request->stock_status,
        ]);
        return redirect()->route('wholesaler-retailer-inventory.index')->with('success', 'Inventory updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $inventory = WholesalerRetailerInventory::where('user_id', Auth::id())->findOrFail($id);
        $inventory->delete();
        return redirect()->route('wholesaler-retailer-inventory.index')->with('success', 'Inventory deleted!');
    }

    /**
     * Increment inventory for a user and product (e.g. on return/restock).
     */
    public function incrementInventory($userId, $productId, $quantity = 1)
    {
        // Convert string user_id to numeric id if needed
        if (!is_numeric($userId)) {
            $user = \App\Models\User::where('user_id', $userId)->first();
            if ($user) {
                $userId = $user->id;
            }
        }
        $inventory = WholesalerRetailerInventory::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
        if ($inventory) {
            $inventory->quantity += $quantity;
        } else {
            // Create the inventory record if it doesn't exist
            $inventory = WholesalerRetailerInventory::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'stock_status' => $quantity <= 0 ? 'out_of_stock' : ($quantity < 5 ? 'low_stock' : 'in_stock'),
            ]);
            return; // Already saved, no need to continue
        }
        // Optionally update stock_status
        if ($inventory->quantity <= 0) {
            $inventory->stock_status = 'out_of_stock';
        } elseif ($inventory->quantity < 5) {
            $inventory->stock_status = 'low_stock';
        } else {
            $inventory->stock_status = 'in_stock';
        }
        $inventory->save();
    }
}
