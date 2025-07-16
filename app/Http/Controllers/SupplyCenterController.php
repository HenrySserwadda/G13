<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyCenter; // Don't forget to import the model

class SupplyCenterController extends Controller
{
    public function index()
    {
        $centers = SupplyCenter::all();
        $workers = []; // Add workers data if needed
        return view('workforce.manage', compact('centers', 'workers'));
    }

    public function create()
    {
        return view('workforce.create'); // Separate view for creating
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'nullable'
        ]);
        
        SupplyCenter::create($request->only('name', 'location'));
        
        return redirect()->route('workforce.manage')->with('success', 'Supply Center Added');
    }

    public function edit(SupplyCenter $supply_center)
    {
        return view('workforce.edit', compact('supply_center'));
    }

    public function update(Request $request, SupplyCenter $supply_center)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'nullable'
        ]);
        
        $supply_center->update($request->only('name', 'location'));
        return redirect()->route('workforce.manage')->with('success', 'Supply Center Updated');
    }

    public function destroy(SupplyCenter $supply_center)
    {
        $supply_center->delete();
        return redirect()->route('workforce.manage')->with('success', 'Supply Center Deleted');
    }
}