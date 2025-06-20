<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class RawMaterialController extends Controller
{
    public function index()
    {
         $materials = RawMaterial::with('supplier')
        ->orderBy('name')
        ->filter(request(['search', 'category', 'stock_status']))
        ->paginate(20);

    $categories = RawMaterial::distinct('category')->pluck('category');

    return view('raw-materials.index', [
        'materials' => $materials,
        'categories' => $categories,
    ]);
    }

    public function create()
    {
        return view('raw-materials.create', [
            'units' => ['kg', 'g', 'm', 'cm', 'sqm', 'roll', 'piece', 'liter', 'yard'],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:raw_materials',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'unit_of_measure' => 'required|string|max:20',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'location' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('raw-materials', 'public');
        }

        RawMaterial::create($validated);

        return redirect()->route('raw-materials.index')
            ->with('success', 'Raw material added successfully.');
    }

    public function show(RawMaterial $rawMaterial)
    {
        return view('raw-materials.show', [
            'material' => $rawMaterial->load('inventoryMovements'),
            'stockHistory' => $rawMaterial->inventoryMovements()
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }

    public function edit(RawMaterial $rawMaterial)
    {
        return view('raw-materials.edit', [
            'material' => $rawMaterial,
            'units' => ['kg', 'g', 'm', 'cm', 'sqm', 'roll', 'piece', 'liter', 'yard'],
        ]);
    }

    public function update(Request $request, RawMaterial $rawMaterial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('raw_materials')->ignore($rawMaterial->id)],
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'unit_of_measure' => 'required|string|max:20',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'location' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($rawMaterial->image_path) {
                Storage::disk('public')->delete($rawMaterial->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('raw-materials', 'public');
        }

        $rawMaterial->update($validated);

        return redirect()->route('raw-materials.show', $rawMaterial)
            ->with('success', 'Raw material updated successfully.');
    }

    public function destroy(RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();

        return redirect()->route('raw-materials.index')
            ->with('success', 'Raw material deleted successfully.');
    }

    public function stockAdjustment(Request $request, RawMaterial $rawMaterial)
    {
        $validated = $request->validate([
            'adjustment_type' => 'required|in:addition,deduction',
            'quantity' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $adjustment = $validated['adjustment_type'] === 'addition' 
            ? $validated['quantity'] 
            : -$validated['quantity'];

        // Update stock
        $rawMaterial->current_stock += $adjustment;
        $rawMaterial->save();

        // Record movement
        $rawMaterial->inventoryMovements()->create([
            'type' => $validated['adjustment_type'],
            'quantity' => $validated['quantity'],
            'new_stock' => $rawMaterial->current_stock,
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
            
        ]);

        return back()->with('success', 'Stock adjusted successfully.');
    }
}