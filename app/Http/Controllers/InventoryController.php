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
    public function index(Request $request)
    {
        $user = Auth::user();
        $centers = \App\Models\SupplyCenter::all();
        $selectedCenterId = $request->get('center_id');
        
        // Default to first center if none selected and centers exist
        if (!$selectedCenterId && $centers->count()) {
            $selectedCenterId = $centers->first()->id;
        }

        if ($user->category === 'staff') {
            // Staff can view all inventories, no longer filtered by supply center
            $query = Inventory::with(['rawMaterial', 'user']);
            $inventories = $query->latest()->paginate(10);
        } else {
            // Others can only view their own inventories, no longer filtered by supply center
            $query = Inventory::with('rawMaterial')->where('user_id', Auth::id());
            $inventories = $query->latest()->paginate(10);
        }

        // Get products for inventory management (staff and systemadmin)
        $products = null;
        if (in_array($user->category, ['staff', 'systemadmin'])) {
            $productsQuery = \App\Models\Product::latest();
            
            // Filter products by selected supply center
            if ($selectedCenterId) {
                $productsQuery->where('supply_center_id', $selectedCenterId);
            }
            
            $products = $productsQuery->get();
        }

        return view('inventories.index', compact('inventories', 'products', 'centers', 'selectedCenterId'));
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
