<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplyCenterController extends Controller
{
    public function index()
{
    $centers = SupplyCenter::all();
    return view('supply_centers.index', compact('centers'));
}

public function create()
{
    return view('supply_centers.create');
}

public function store(Request $request)
{
    $request->validate(['name' => 'required', 'location' => 'nullable']);
    SupplyCenter::create($request->only('name', 'location'));
    return redirect()->route('supply-centers.index')->with('success', 'Supply Center Added');
}

public function edit(SupplyCenter $supply_center)
{
    return view('supply_centers.edit', compact('supply_center'));
}

public function update(Request $request, SupplyCenter $supply_center)
{
    $request->validate(['name' => 'required', 'location' => 'nullable']);
    $supply_center->update($request->only('name', 'location'));
    return redirect()->route('supply-centers.index')->with('success', 'Supply Center Updated');
}

public function destroy(SupplyCenter $supply_center)
{
    $supply_center->delete();
    return redirect()->route('supply-centers.index')->with('success', 'Supply Center Deleted');
}

}
