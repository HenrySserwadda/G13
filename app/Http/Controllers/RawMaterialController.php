<?php

namespace App\Http\Controllers;

use App\Models\Rawmaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $materials = $user->category === 'systemadmin'
            ? Rawmaterial::with('user')->paginate(10)
            : Rawmaterial::where('user_id', $user->id)->with('user')->paginate(10);

        return view('raw_materials.index', compact('materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('raw_materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|string|max:50',
            'type' => 'required|string|max:100',  // Added validation for 'type'
        ]);

        $rawMaterial = new Rawmaterial();
        $rawMaterial->name = $request->name;
        $rawMaterial->quantity = $request->quantity;
        $rawMaterial->unit_price = $request->unit_price;
        $rawMaterial->type = $request->type;  // Save the 'type'
        $rawMaterial->user_id = Auth::id();
        $rawMaterial->save();

        return redirect()->route('raw_materials.index')->with('success', 'Raw material created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rawmaterial $rawmaterial)  // changed param name to match model
    {
        if (Auth::user()->category === 'supplier') {
            abort(403);
        }

        return view('raw_materials.edit', compact('rawmaterial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (Auth::user()->category === 'supplier') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|string|max:50',
            'type' => 'required|string|max:100',  // Added validation for 'type'
        ]);

        $rawMaterial = Rawmaterial::findOrFail($id);
        $rawMaterial->update($request->only(['name', 'quantity', 'unit_price', 'type']));  // Include 'type'

        return redirect()->route('raw_materials.index')->with('success', 'Raw material updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->category === 'supplier') {
            abort(403);
        }

        $rawMaterial = Rawmaterial::findOrFail($id);
        $rawMaterial->delete();

        return redirect()->route('raw_materials.index')->with('success', 'Raw material deleted successfully.');
    }
}
