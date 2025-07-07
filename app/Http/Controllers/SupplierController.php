<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function dashboard()
    {
        $supplierId = Auth::id();
        $rawMaterialCount = \App\Models\RawMaterial::where('user_id', $supplierId)->count();
        $orderCount = \App\Models\RawMaterialOrder::where('supplier_user_id', $supplierId)->count();
        $inventoryCount = \App\Models\Inventory::where('user_id', $supplierId)->count();
        return view('dashboard.supplier', compact('rawMaterialCount', 'orderCount', 'inventoryCount'));
    }
} 