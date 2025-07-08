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

        // Add dynamic chart data
        $python = config('ml.python_path', 'python');
        $script = config('ml.scripts_path', base_path('ml-scripts')) . '/custom_chart.py';
        $output = [];
        $return_var = 0;
        $cmd = "$python $script --chart_type bar --x_axis Month --y_axis Sales --json";
        exec($cmd, $output, $return_var);
        $chartData = json_decode(implode('', $output), true);

        return view('dashboard.supplier', compact('rawMaterialCount', 'orderCount', 'inventoryCount', 'chartData'));
    }
} 