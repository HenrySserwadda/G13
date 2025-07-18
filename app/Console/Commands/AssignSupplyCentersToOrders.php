<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\SupplyCenter;

class AssignSupplyCentersToOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:assign-supply-centers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Randomly assign supply centers to orders that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $supplyCenters = SupplyCenter::all();
        
        if ($supplyCenters->isEmpty()) {
            $this->error('No supply centers found. Please create supply centers first.');
            return 1;
        }

        $ordersWithoutCenter = Order::whereNull('supply_center_id')->get();
        
        if ($ordersWithoutCenter->isEmpty()) {
            $this->info('All orders already have supply centers assigned.');
            return 0;
        }

        $this->info("Found {$ordersWithoutCenter->count()} orders without supply centers.");
        
        $bar = $this->output->createProgressBar($ordersWithoutCenter->count());
        $bar->start();

        foreach ($ordersWithoutCenter as $order) {
            $randomCenter = $supplyCenters->random();
            $order->supply_center_id = $randomCenter->id;
            $order->save();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Successfully assigned supply centers to all orders!');

        return 0;
    }
}
