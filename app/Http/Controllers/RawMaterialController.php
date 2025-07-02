<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
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
            ? RawMaterial::with('user')->paginate(10)
            : RawMaterial::where('user_id', $user->id)->with('user')->paginate(10);

        return view('raw_materials.index', compact('materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Allow suppliers and systemadmin to create raw materials
        if (!in_array(Auth::user()->category, ['systemadmin', 'supplier'])) {
            abort(403);
        }
        return view('raw_materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->category, ['systemadmin', 'supplier'])) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:raw_materials,name',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'type' => 'required|string|max:100',
        ]);

        RawMaterial::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'type' => $request->type,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('raw_materials.index')->with('success', 'Raw material created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RawMaterial $rawMaterial)
    {
        // Allow only the owner (supplier) or systemadmin to edit
        $user = Auth::user();
        if ($user->category !== 'systemadmin' && $rawMaterial->user_id !== $user->id) {
            abort(403);
        }

        return view('raw_materials.edit', ['material' => $rawMaterial]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RawMaterial $rawMaterial)
    {
        $user = Auth::user();
        if ($user->category !== 'systemadmin' && $rawMaterial->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:raw_materials,name,' . $rawMaterial->id,
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'type' => 'required|string|max:100',
        ]);

        $rawMaterial->update($request->only(['name', 'quantity', 'unit_price', 'type']));

        return redirect()->route('raw_materials.index')->with('success', 'Raw material updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RawMaterial $rawMaterial)
    {
        $user = Auth::user();
        if ($user->category !== 'systemadmin' && $rawMaterial->user_id !== $user->id) {
            abort(403);
        }

        $rawMaterial->delete();

        return redirect()->route('raw_materials.index')->with('success', 'Raw material deleted successfully.');
    }

    /**
     * Approve a raw material (systemadmin only)
     */
    public function approve(RawMaterial $rawMaterial)
    {
        if (Auth::user()->category !== 'systemadmin') {
            abort(403);
        }

        $rawMaterial->update(['status' => 'approved']);
        return back()->with('success', 'Material approved successfully');
    }

    /**
     * Reject a raw material (systemadmin only)
     */
    public function reject(RawMaterial $rawMaterial)
    {
        if (Auth::user()->category !== 'systemadmin') {
            abort(403);
        }

        $rawMaterial->update(['status' => 'rejected']);
        return back()->with('success', 'Material rejected');
    }
}
