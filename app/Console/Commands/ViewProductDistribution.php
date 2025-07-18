<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SupplyCenter;
use App\Models\Product;

class ViewProductDistribution extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:view-distribution';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View product distribution across supply centers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Product Distribution Across Supply Centers:');
        $this->info('==========================================');

        $centers = SupplyCenter::with('products')->get();

        foreach ($centers as $center) {
            $productCount = $center->products->count();
            $totalStock = $center->products->sum('quantity');
            
            $this->info("\nSupply Center: {$center->name}");
            $this->info('----------------------------------------');
            $this->info("Total Products: {$productCount}");
            $this->info("Total Stock: {$totalStock}");
            
            if ($productCount > 0) {
                $this->info("\nProducts:");
                foreach ($center->products as $product) {
                    $this->info("  - {$product->name}: {$product->quantity} units (UGX " . number_format($product->price) . ")");
                }
            }
        }

        $this->info("\n" . str_repeat('=', 50));
        $this->info('Summary:');
        $this->info('Total Products: ' . Product::count());
        $this->info('Total Stock: ' . Product::sum('quantity'));
        $this->info('Products without Supply Center: ' . Product::whereNull('supply_center_id')->count());

        return 0;
    }
}
