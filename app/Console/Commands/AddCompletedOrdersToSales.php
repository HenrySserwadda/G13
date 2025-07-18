<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Sale;
use Carbon\Carbon;

class AddCompletedOrdersToSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:process-completed-orders {--force : Force processing even if orders were already processed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process completed orders and add their totals to sales table for respective supply centers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing completed orders to sales table...');

        // Get completed orders that haven't been processed yet
        $query = Order::where('status', 'completed')
                     ->whereNotNull('supply_center_id');

        if (!$this->option('force')) {
            // Only process orders that don't have corresponding sales records
            $processedOrderIds = Sale::pluck('order_id')->filter();
            if ($processedOrderIds->isNotEmpty()) {
                $query->whereNotIn('id', $processedOrderIds);
            }
        }

        $completedOrders = $query->get();

        if ($completedOrders->isEmpty()) {
            $this->info('No new completed orders to process.');
            return 0;
        }

        $this->info("Found {$completedOrders->count()} completed orders to process.");

        $bar = $this->output->createProgressBar($completedOrders->count());
        $bar->start();

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
                        'order_id' => $order->id, // Track which order this sale came from
                    ]);
                }

                $processedCount++;
                $bar->advance();

            } catch (\Exception $e) {
                $this->error("Error processing order {$order->id}: " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully processed {$processedCount} orders to sales table!");

        return 0;
    }
}
