<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\SupplyCenter;
use App\Services\WorkforceService;

class WorkerController extends Controller
{
    protected $workforceService;

    public function __construct(WorkforceService $workforceService)
    {
        $this->workforceService = $workforceService;
    }

    public function index()
    {
        $workers = Worker::with('supplyCenter')->get();
        $centers = SupplyCenter::all();
        return view('manage', compact('workers', 'centers'));
    }

    public function create()
    {
        $centers = SupplyCenter::all();
        return view('workers.create', compact('centers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'supply_center_id' => 'nullable|exists:supply_centers,id'
        ]);

        Worker::create($request->only('name', 'supply_center_id'));
        
        // Update workforce allocations after adding a new worker
        $this->workforceService->generateAllocations();

        return redirect()->route('manage')
            ->with('success', 'Worker added successfully!');
    }

    public function edit(Worker $worker)
    {
        $centers = SupplyCenter::all();
        return view('workers.edit', compact('worker', 'centers'));
    }

    public function update(Request $request, Worker $worker)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'supply_center_id' => 'nullable|exists:supply_centers,id'
        ]);

        $worker->update($request->only('name', 'supply_center_id'));
        
        // Update workforce allocations after modifying a worker
        $this->workforceService->generateAllocations();

        return redirect()->route('manage')
            ->with('success', 'Worker updated successfully!');
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();
        
        // Update workforce allocations after removing a worker
        $this->workforceService->generateAllocations();

        return redirect()->route('manage')
            ->with('success', 'Worker deleted successfully!');
    }
}