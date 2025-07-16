<?php

namespace App\Services;

use App\Models\SupplyCenter;
use App\Models\WorkforceAllocation;

class WorkforceService
{
    public function generateAllocations()
    {
        $centers = SupplyCenter::with(['sales', 'stocks', 'workers'])->get();

        $allocations = [];

        foreach ($centers as $center) {
            $sales = $center->sales->sum('amount') ?? 0;
            $stock = $center->stocks->sum('quantity') ?? 0;
            $workers = $center->workers->count() ?? 0;

            $status = $this->determineStatus($sales, $stock, $workers);
            $reason = $this->generateReason($status);

            $allocations[] = WorkforceAllocation::updateOrCreate(
                ['supply_center_id' => $center->id],
                [
                    'sales' => $sales,
                    'stock' => $stock,
                    'allocated_workers' => $workers,
                    'status' => $status,
                    'recommendation_reason' => $reason,
                    'performance_score' => $this->calculatePerformance($sales, $workers)
                ]
            );
        }

        return $allocations;
    }

    public function analyzeCapacity($supplyCenter)
    {
        $totalSales = $supplyCenter->sales->sum('amount') ?? 0;
        $totalStock = $supplyCenter->stocks->sum('quantity') ?? 0;
        $workersCount = $supplyCenter->workers->count() ?? 0;

        $status = $this->determineStatus($totalSales, $totalStock, $workersCount);
        $reason = $this->generateReason($status);

        return [
            'sales' => $totalSales,
            'stock' => $totalStock,
            'workers' => $workersCount,
            'status' => $status,
            'recommendation_reason' => $reason,
            'reason' => $reason, // Add this line for Blade compatibility
        ];
    }

    private function determineStatus($sales, $stock, $workers)
    {
        // Protect against division by zero
        if ($workers === 0) {
            return 'Deficit';
        }

        if ($workers >= ($sales / 1000000) && $workers <= ($stock / 100)) {
            return 'Adequate';
        } elseif ($workers > ($sales / 1000000)) {
            return 'Surplus';
        } else {
            return 'Deficit';
        }
    }

    private function generateReason($status)
    {
        return match ($status) {
            'Adequate' => 'Sufficient workers for the available stock and matching sales',
            'Surplus' => 'More workers with low stock and sales',
            'Deficit' => 'Fewer workers than required for the stock and sales demand',
            default => 'No analysis available',
        };
    }

    private function calculatePerformance($sales, $workers)
    {
        return $workers > 0 ? round($sales / $workers, 2) : 0;
    }
}
