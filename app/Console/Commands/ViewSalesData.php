<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;

class ViewSalesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View sales data for all supply centers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sales Data by Supply Center:');
        $this->info('============================');

        $sales = Sale::with('supplyCenter')->orderBy('supply_center_id')->orderBy('sales_month')->get();

        if ($sales->isEmpty()) {
            $this->info('No sales data found.');
            return 0;
        }

        $currentCenter = null;
        $totalSales = 0;

        foreach ($sales as $sale) {
            if ($currentCenter !== $sale->supply_center_id) {
                if ($currentCenter !== null) {
                    $this->info("Total for {$currentCenter}: UGX " . number_format($totalSales));
                    $this->info('');
                }
                $currentCenter = $sale->supply_center_id;
                $totalSales = 0;
                $this->info("Supply Center: {$sale->supplyCenter->name}");
                $this->info('----------------------------------------');
            }

            $this->info("  {$sale->sales_month->format('F Y')}: UGX " . number_format($sale->monthly_sales));
            $totalSales += $sale->monthly_sales;
        }

        if ($currentCenter !== null) {
            $this->info("Total for {$currentCenter}: UGX " . number_format($totalSales));
        }

        $this->info('');
        $this->info('Grand Total: UGX ' . number_format($sales->sum('monthly_sales')));

        return 0;
    }
}
