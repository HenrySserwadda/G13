<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InventoryController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $user = Auth::user();
        
        if ($user->category === 'staff') {
            // Staff can view all inventories
            $inventories = Inventory::with(['rawMaterial', 'user'])
                ->latest()
                ->paginate(10);
        } else {
            // Others can only view their own inventories
            $inventories = Inventory::with('rawMaterial')
                ->where('user_id', Auth::id())
                ->latest()
                ->paginate(10);
        }

        // Get products for inventory management (staff and systemadmin)
        $products = null;
        if (in_array($user->category, ['staff', 'systemadmin'])) {
            $products = \App\Models\Product::latest()->get();
        }

        return view('inventories.index', compact('inventories', 'products'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->category, ['systemadmin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $rawMaterials = RawMaterial::all();
        $rawMaterialQuantities = $rawMaterials->pluck('quantity', 'id');
        return view('inventories.create', compact('rawMaterials', 'rawMaterialQuantities'));
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->category, ['systemadmin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
            'on_hand' => 'required|integer|min:0',
            'on_order' => 'required|integer|min:0',
            'stock_status' => 'required|in:in_stock,low,out',
            'delivery_status' => 'required|in:in_transit,received,need_to_order,in_progress',
            'delivered_on' => 'nullable|date',
            'expected_delivery' => 'nullable|date|after_or_equal:today',
        ]);

        Inventory::create([
            ...$request->all(),
            'user_id' => Auth::id()
        ]);

        return redirect()->route('inventories.index')->with('success', 'Inventory record added.');
    }

    public function edit(Inventory $inventory)
    {
        if (!in_array(Auth::user()->category, ['systemadmin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $rawMaterials = RawMaterial::where('user_id', Auth::id())->get();

        return view('inventories.edit', compact('inventory', 'rawMaterials'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        if (!in_array(Auth::user()->category, ['systemadmin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'on_hand' => 'required|integer|min:0',
            'on_order' => 'required|integer|min:0',
            'stock_status' => 'required|in:in_stock,low,out',
            'delivery_status' => 'required|in:in_transit,received,need_to_order,in_progress',
            'delivered_on' => 'nullable|date',
            'expected_delivery' => 'nullable|date|after_or_equal:today',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventories.index')->with('success', 'Inventory updated.');
    }

    public function destroy(Inventory $inventory)
    {
        $this->authorize('delete', $inventory); // Optional
        $inventory->delete();

        return redirect()->route('inventories.index')->with('success', 'Inventory deleted.');
    }
}
