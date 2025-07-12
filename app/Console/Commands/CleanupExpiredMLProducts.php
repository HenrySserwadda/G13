<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MLProductService;

class CleanupExpiredMLProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ml:cleanup-expired-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired ML-generated products';

    protected $mlProductService;

    public function __construct(MLProductService $mlProductService)
    {
        parent::__construct();
        $this->mlProductService = $mlProductService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of expired ML products...');
        
        $deletedCount = $this->mlProductService->cleanupExpiredProducts();
        
        $this->info("Successfully deleted {$deletedCount} expired ML products.");
        
        return 0;
    }
}
