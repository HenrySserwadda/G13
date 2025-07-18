<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Sale;
use Carbon\Carbon;

class ScheduledProcessCompletedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:process-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduled command to process completed orders to sales table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running scheduled sales processing...');

        // Get completed orders that haven't been processed yet
        $processedOrderIds = Sale::pluck('order_id')->filter();
        
        $query = Order::where('status', 'completed')
                     ->whereNotNull('supply_center_id');

        if ($processedOrderIds->isNotEmpty()) {
            $query->whereNotIn('id', $processedOrderIds);
        }

        $completedOrders = $query->get();

        if ($completedOrders->isEmpty()) {
            $this->info('No new completed orders to process.');
            return 0;
        }

        $this->info("Processing {$completedOrders->count()} completed orders...");

        $processedCount = 0;

        foreach ($completedOrders as $order) {
            try {
                // Group by supply center and month
                $salesMonth = $order->updated_at->format('Y-m');
                $supplyCenterId = $order->supply_center_id;

                // Check if sales record already exists for this supply center and month
                $existingSale = Sale::where('supply_center_id', $supplyCenterId)
                                   ->where('sales_month', $salesMonth . '-01')
                                   ->first();

                if ($existingSale) {
                    // Update existing sales record
                    $existingSale->monthly_sales += $order->total;
                    $existingSale->save();
                } else {
                    // Create new sales record
                    Sale::create([
                        'supply_center_id' => $supplyCenterId,
                        'monthly_sales' => $order->total,
                        'sales_month' => $salesMonth . '-01',
                        'order_id' => $order->id,
                    ]);
                }

                $processedCount++;

            } catch (\Exception $e) {
                $this->error("Error processing order {$order->id}: " . $e->getMessage());
            }
        }

        $this->info("Successfully processed {$processedCount} orders to sales table!");
        
        return 0;
    }
}
