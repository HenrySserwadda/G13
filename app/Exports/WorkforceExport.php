<?php

namespace App\Exports;

use App\Models\SupplyCenter;
use Maatwebsite\Excel\Concerns\FromCollection;

class WorkforceReportExport implements FromCollection
{
    public function collection()
    {
        return SupplyCenter::withCount('workers')->get(['id', 'name', 'location']);
    }
}
