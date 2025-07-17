<?php

namespace App\Http\Controllers;

use App\Models\SupplyCenter;
use App\Models\Worker;
use App\Services\WorkforceService;
use App\Models\WorkforceTransfer;
use Illuminate\Http\Request;
use PDF; // barryvdh/laravel-dompdf
use Maatwebsite\Excel\Facades\Excel; // maatwebsite/excel
use App\Exports\WorkforceReportExport;

class WorkforceController extends Controller
{
    // Show the Dashboard
public function index()
{
    $service = app(WorkforceService::class);
    $service->generateAllocations();

    $allocations = \App\Models\WorkforceAllocation::with('supplyCenter')->get();

    // Prepare chart data as before
    $chartData = [
        'centers' => $allocations->pluck('supplyCenter.name')->toArray(),
        'sales' => $allocations->pluck('sales')->toArray(),
        'stock' => $allocations->pluck('stock')->toArray(),
        'workers' => $allocations->pluck('allocated_workers')->toArray(),
        'performance' => $allocations->pluck('performance_score')->toArray(),
    ];

    // Prepare $analysis (example logic, adjust as needed)
    $centers = \App\Models\SupplyCenter::with(['sales', 'stocks', 'workers'])->get();
    $analysis = $centers->map(function ($center) use ($service) {
        $analysisResult = $service->analyzeCapacity($center);
        return [
            'center' => $center,
            'analysis' => $analysisResult,
        ];
    });

    // Prepare $transfers, $stocks, $sales
    $transfers = \App\Models\WorkforceTransfer::with(['worker', 'fromCenter', 'toCenter'])->get();
    $stocks = \App\Models\Stock::with('supplyCenter')->get();
    $sales = \App\Models\Sale::with('supplyCenter')->get();
    $workers = \App\Models\Worker::with('supplyCenter')->get(); // Fetch all workers with their supply center

    return view('workforce.index', compact('allocations', 'chartData', 'analysis', 'transfers', 'stocks', 'sales', 'workers', 'centers'));
}

  // Allocate Workers between centers
    public function allocateWorkers(Request $request)
    {
        $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'to_center_id' => 'required|exists:supply_centers,id',
            'reason' => 'required|string|max:255', // Require reason for transfer
        ]);

        $worker = Worker::find($request->worker_id);
        $fromCenter = $worker->supply_center_id;
        $worker->update(['supply_center_id' => $request->to_center_id]);

        WorkforceTransfer::create([
            'worker_id' => $worker->id,
            'from_center_id' => $fromCenter,
            'to_center_id' => $request->to_center_id,
            'transfer_date' => now(),
            'reason' => $request->input('reason'), // Save the reason
        ]);

        return redirect()->back()->with('success', 'Worker allocated successfully.');
    }

// Service Controller
public function refresh(WorkforceService $service)
{
    $service->generateAllocations();
    return redirect()->route('workforce.index')->with('success', 'Workforce recommendations updated.');
}

//CRUD Management
public function manage()
{
    return view('workforce.manage', [
        'centers' => SupplyCenter::all(),
        'workers' => Worker::with('supplyCenter')->get(),
    ]);
}
// Excel
 public function exportExcelReport()
    {
        return Excel::download(new WorkforceExport, 'workforce_report.xlsx');
    }

    // Recommend workforce actions (hiring/layoff)
    public function recommendAction()
    {
        $centers = SupplyCenter::with(['stocks', 'sales', 'workers'])->get();
        $recommendations = $centers->map(function ($center) {
            $analysis = app('App\Services\WorkforceService')->analyzeCapacity($center);
            return [
                'center' => $center->name,
                'status' => $analysis['status'],
                'recommendation' => match ($analysis['status']) {
                    'Surplus' => 'Consider laying off or reallocating workers',
                    'Deficit' => 'Consider hiring or reallocating workers',
                    default => 'No action needed',
                }
            ];
        });
        return view('workforce.recommendations', compact('recommendations'));
    }

    // Export PDF Report
    public function exportPdfReport()
    {
        $centers = SupplyCenter::with(['stocks', 'sales', 'workers'])->get();
        $pdf = PDF::loadView('workforce.report_pdf', compact('centers'));
        return $pdf->download('workforce_report_' . now()->format('Y_m_d') . '.pdf');
    }

 

}
