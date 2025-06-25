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
        $inventories = Inventory::with('rawMaterial')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('inventories.index', compact('inventories'));
    }

    public function create()
    {
        $rawMaterials = RawMaterial::all();
        $rawMaterialQuantities = $rawMaterials->pluck('quantity', 'id');
        return view('inventories.create', compact('rawMaterials', 'rawMaterialQuantities'));
    }

    public function store(Request $request)
    {
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
        $this->authorize('update', $inventory); // Optional policy if added
        $rawMaterials = RawMaterial::where('user_id', Auth::id())->get();

        return view('inventories.edit', compact('inventory', 'rawMaterials'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $this->authorize('update', $inventory); // Optional

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
