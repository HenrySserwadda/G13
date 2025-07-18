<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\SupplyCenter;

class AssignSupplyCentersToProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:assign-supply-centers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Randomly assign supply centers to products that don\'t have one';

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

        $productsWithoutCenter = Product::whereNull('supply_center_id')->get();
        
        if ($productsWithoutCenter->isEmpty()) {
            $this->info('All products already have supply centers assigned.');
            return 0;
        }

        $this->info("Found {$productsWithoutCenter->count()} products without supply centers.");
        
        $bar = $this->output->createProgressBar($productsWithoutCenter->count());
        $bar->start();

        foreach ($productsWithoutCenter as $product) {
            $randomCenter = $supplyCenters->random();
            $product->supply_center_id = $randomCenter->id;
            $product->save();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Successfully assigned supply centers to all products!');

        return 0;
    }
}
