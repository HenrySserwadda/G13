<?php

namespace App\Console\Commands;
// In app/Console/Commands/RetrainModels.php

use Illuminate\Console\Command;
use App\Http\Controllers\MLController;

class RetrainModels extends Command
{
    protected $signature = 'ml:retrain';

    public function handle()
    {
        $controller = new MLController();
        $controller->trainModels();
        $this->info('ML models retrained successfully');
    }
}