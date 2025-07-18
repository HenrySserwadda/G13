@extends('components.dashboard')

@section('content')
<div class="container mx-auto p-4 space-y-6 dark:bg-gray-900 transition-colors duration-300">
    <!-- Workforce Allocation Header -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 dark:from-blue-600 dark:to-indigo-600 text-white p-6 rounded-lg shadow-lg flex flex-col md:flex-row justify-between items-center transition hover:shadow-xl">
        <div class="mb-4 md:mb-0 text-center md:text-left">
            <h3 class="text-2xl font-semibold">Optimize your workforce distribution across supply centers</h3>
            <p class="text-blue-100 dark:text-blue-200 text-lg">Track stock, sales, and workers to enhance performance efficiency</p>
        </div>
        <a href="{{ url('/workers') }}" class="flex items-center bg-white dark:bg-gray-800 text-blue-700 dark:text-blue-300 font-semibold px-6 py-3 rounded-full shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
    Manage
</a>

    </div>

    <!-- Table 1: Supply Center Summary -->
    <div class="bg-white dark:bg-gray-800 dark:shadow-2xl dark:bg-opacity-80 shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
        <h3 class="text-xl font-semibold p-6 pb-2 text-gray-800 dark:text-gray-200 text-center flex items-center justify-center gap-2">
            <span class="inline-block align-middle">
                <!-- Warehouse SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-500 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-7 9 7v7a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-7z" />
                </svg>
            </span>
            Supply Center Summary
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gradient-to-r from-purple-400 to-purple-600 dark:from-purple-500 dark:to-purple-700 text-white">
                    <tr>
                        <th class="p-3 text-left">Supply Center</th>
                        <th class="p-3 text-center">Sales</th>
                        <th class="p-3 text-center">Stock Available</th>
                        <th class="p-3 text-center">Workers</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-center">Reason</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach (
    $centers as $center)
@php
    $sales = $center->sales->last()->monthly_sales ?? 0;
    $stock = \App\Models\Product::where('supply_center_id', $center->id)->sum('quantity');
    $workers = $center->workers->count() ?? 0;
    if ($stock > 0) {
        $ratio = ($workers / $stock) * 1000;
    } else {
        $ratio = 0;
    }
    if ($stock == 0 || $ratio <= 40) {
        $status = 'Deficit';
        $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
        $reason = $stock == 0 ? 'No stock available' : 'Too few workers for available stock';
    } elseif ($ratio > 40 && $ratio <= 60) {
        $status = 'Adequate';
        $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        $reason = 'Workforce matches stock level';
    } else {
        $status = 'Surplus';
        $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
        $reason = 'More workers than needed for stock';
    }
@endphp
<tr class="hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200">
    <td class="p-3 dark:text-gray-300">{{ $center->name }}</td>
    <td class="p-3 text-center dark:text-gray-300">{{ number_format($sales) }}</td>
    <td class="p-3 text-center dark:text-gray-300">{{ number_format($stock) }}</td>
    <td class="p-3 text-center dark:text-gray-300">{{ $workers }}</td>
    <td class="p-3 text-center">
        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
            {{ $status }}
        </span>
    </td>
    <td class="p-3 text-center dark:text-gray-300 text-xs">{{ $reason }}</td>
</tr>
@endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>

    <!-- Table 2: Workforce Allocation History -->
    <div class="bg-white dark:bg-gray-800 dark:shadow-2xl dark:bg-opacity-80 shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
        <h3 class="text-xl font-semibold p-6 pb-2 text-gray-800 dark:text-gray-200 text-center flex items-center justify-center gap-2">
            <span class="inline-block align-middle">
                <!-- History/Transfer SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-500 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
            Workforce Allocation History
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gradient-to-r from-blue-400 to-blue-600 dark:from-blue-500 dark:to-blue-700 text-white">
                    <tr>
                        <th class="p-3 text-left">Worker</th>
                        <th class="p-3 text-center">From Center</th>
                        <th class="p-3 text-center">To Center</th>
                        <th class="p-3 text-center">Date</th>
                        <th class="p-3 text-center">Reason</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($transfers as $transfer)
                    <tr class="hover:bg-purple-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <td class="p-3 dark:text-gray-300">{{ $transfer->worker->name }}</td>
                        <td class="p-3 text-center dark:text-gray-300">{{ $transfer->fromCenter->name ?? 'N/A' }}</td>
                        <td class="p-3 text-center dark:text-gray-300">{{ $transfer->toCenter->name ?? 'N/A' }}</td>
                        <td class="p-3 text-center dark:text-gray-300">{{ date('M d, Y', strtotime($transfer->transfer_date)) }}</td>
                        <td class="p-3 text-center dark:text-gray-300">{{ $transfer->reason }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>

    <!-- Table 3: Stock Summary -->
    <div class="bg-white dark:bg-gray-800 dark:shadow-2xl dark:bg-opacity-80 shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
        <h3 class="text-xl font-semibold p-6 pb-2 text-gray-800 dark:text-gray-200 text-center flex items-center justify-center gap-2">
            <span class="inline-block align-middle">
                <!-- Box/Inventory SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V7m0 6v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6" />
                </svg>
            </span>
            Stock Summary
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gradient-to-r from-green-400 to-green-600 dark:from-green-500 dark:to-green-700 text-white">
                    <tr>
                        <th class="p-3 text-left">Supply Center</th>
                        <th class="p-3 text-center">Stock Quantity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($centers as $center)
                    <tr class="hover:bg-green-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <td class="p-3 dark:text-gray-300">{{ $center->name }}</td>
                        <td class="p-3 text-center dark:text-gray-300">
                            @php
                                $totalProductStock = \App\Models\Product::where('supply_center_id', $center->id)->sum('quantity');
                            @endphp
                            {{ $totalProductStock }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>

    <!-- Table 4: Sales Summary -->
    <div class="bg-white dark:bg-gray-800 dark:shadow-2xl dark:bg-opacity-80 shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
        <h3 class="text-xl font-semibold p-6 pb-2 text-gray-800 dark:text-gray-200 text-center flex items-center justify-center gap-2">
            <span class="inline-block align-middle">
                <!-- Sales/Trending Up SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500 dark:text-orange-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8" />
                </svg>
            </span>
            Sales Summary (Monthly)
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gradient-to-r from-orange-400 to-orange-600 dark:from-orange-500 dark:to-orange-700 text-white">
                    <tr>
                        <th class="p-3 text-left">Supply Center</th>
                        <th class="p-3 text-center">Sales Month</th>
                        <th class="p-3 text-center">Sales Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($centers as $center)
                    <tr class="hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <td class="p-3 dark:text-gray-300">{{ $center->name }}</td>
                        <td class="p-3 text-center dark:text-gray-300">
                            @if($center->sales->last())
                                {{ date('F Y', strtotime($center->sales->last()->sales_month)) }}
                            @else
                                N/A
                            @endif
                            </td>
                        <td class="p-3 text-center dark:text-gray-300">{{ number_format($center->sales->last()->monthly_sales ?? 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>

    <!-- Graph 1: Stock & Sales vs Workforce -->
    <div class="bg-white dark:bg-gray-800 dark:shadow-2xl dark:bg-opacity-80 shadow-lg rounded-lg p-6 transition-all duration-300 hover:shadow-xl">
        <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Stock & Sales vs Workforce</h3>
        <div class="h-80">
            <canvas id="stockSalesWorkforceChart"></canvas>
        </div>
    </div>
    <br>

    <!-- Graph 2: Center Performance Post Allocation -->
    <div class="bg-white dark:bg-gray-800 dark:shadow-2xl dark:bg-opacity-80 shadow-lg rounded-lg p-6 transition-all duration-300 hover:shadow-xl">
        <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Center Performance After Workforce Allocation</h3>
        <div class="h-80">
            <canvas id="centerPerformanceChart"></canvas>
        </div>
    </div>

    <div class="flex space-x-2 justify-end mt-4">
        <!-- PDF Export Button -->
        <a href="{{ route('workforce.exportPdf') }}" class="flex items-center bg-red-500 dark:bg-red-600 text-white px-4 py-2 rounded-full shadow-md hover:bg-red-600 dark:hover:bg-red-700 transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-400">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M8 16H7a2 2 0 01-2-2V6a2 2 0 012-2h6l4 4v8a2 2 0 01-2 2h-1"/>
            </svg>
            Export PDF
        </a>

        <!-- Excel Export Button -->
        <a href="{{ route('workforce.exportExcel') }}" class="flex items-center bg-green-500 dark:bg-green-600 text-white px-4 py-2 rounded-full shadow-md hover:bg-green-600 dark:hover:bg-green-700 transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-400">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M10 14h10M4 14h4m-4 4h16"/>
            </svg>
            Export Excel
        </a>
    </div>
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    // Color palette for charts (light mode)
    const lightColors = [
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 99, 132, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(255, 159, 64, 0.7)',
        'rgba(153, 102, 255, 0.7)',
        'rgba(255, 205, 86, 0.7)',
        'rgba(201, 203, 207, 0.7)',
        'rgba(54, 162, 235, 0.7)'
    ];

    // Color palette for charts (dark mode)
    const darkColors = [
        'rgba(100, 180, 255, 0.7)',
        'rgba(255, 120, 150, 0.7)',
        'rgba(100, 220, 220, 0.7)',
        'rgba(255, 180, 100, 0.7)',
        'rgba(180, 150, 255, 0.7)',
        'rgba(255, 220, 100, 0.7)',
        'rgba(220, 220, 220, 0.7)',
        'rgba(100, 180, 255, 0.7)'
    ];

    let stockSalesChart, centerPerfChart;

    function isDarkMode() {
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    function createCharts() {
        const dark = isDarkMode();
        const colors = dark ? darkColors : lightColors;
        const gridColor = dark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
        const textColor = dark ? '#fff' : '#374151';

        // Destroy existing charts if they exist
        if (stockSalesChart) stockSalesChart.destroy();
        if (centerPerfChart) centerPerfChart.destroy();

        // Graph 1: Stock & Sales vs Workforce
        const stockSalesCtx = document.getElementById('stockSalesWorkforceChart').getContext('2d');
        stockSalesChart = new Chart(stockSalesCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($centers->pluck('name')) !!},
                datasets: [
                    {
                        label: 'Stock Available',
                        data: {!! json_encode($centers->map(fn($c) => \App\Models\Product::where('supply_center_id', $c->id)->sum('quantity'))) !!},
                        backgroundColor: colors,
                        borderColor: colors.map(c => c.replace('0.7', '1')),
                        borderWidth: 1
                    },
                    {
                        label: 'Sales (Monthly)',
                        data: {!! json_encode($centers->map(fn($c) => $c->sales->last()->monthly_sales ?? 0)) !!},
                        backgroundColor: colors.map(c => c.replace('0.7', '0.4')),
                        borderColor: colors.map(c => c.replace('0.7', '1')),
                        borderWidth: 1,
                        type: 'line',
                        tension: 0.3,
                        fill: false,
                        pointBackgroundColor: colors,
                        pointBorderColor: dark ? '#1f2937' : '#fff',
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            color: textColor
                        }
                    },
                    datalabels: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor
                        },
                        title: {
                            display: false,
                            color: textColor
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: textColor
                        },
                        title: {
                            display: false,
                            color: textColor
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            },
            plugins: [ChartDataLabels]
        });

        // Graph 2: Center Performance Post Allocation
        const centerPerfCtx = document.getElementById('centerPerformanceChart').getContext('2d');
        centerPerfChart = new Chart(centerPerfCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($centers->pluck('name')) !!},
                datasets: [
                    {
                        label: 'Sales After Allocation',
                        data: {!! json_encode($centers->map(fn($c) => $c->sales->last()->monthly_sales ?? 0)) !!},
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: dark ? 'rgba(255, 99, 132, 0.2)' : 'rgba(255, 99, 132, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                        pointBorderColor: dark ? '#1f2937' : '#fff',
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 2,
                        borderWidth: 2
                    },
                    {
                        label: 'Workers After Allocation',
                        data: {!! json_encode($centers->map(fn($c) => $c->workers->count() ?? 0)) !!},
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: dark ? 'rgba(54, 162, 235, 0.2)' : 'rgba(54, 162, 235, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        pointBorderColor: dark ? '#1f2937' : '#fff',
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 2,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            color: textColor
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor
                        },
                        title: {
                            display: false,
                            color: textColor
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: textColor
                        },
                        title: {
                            display: false,
                            color: textColor
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

    // Listen for system color scheme changes and recreate charts
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function() {
            createCharts();
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        createCharts();
    });
</script>

<style>
    /* Smooth transitions for all interactive elements */
    a, button, .hover\:shadow-lg:hover, .hover\:bg-blue-50:hover, .hover\:bg-purple-50:hover, 
    .hover\:bg-green-50:hover, .hover\:bg-orange-50:hover {
        transition: all 0.3s ease;
    }

    /* Table row hover effects */
    tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Custom scrollbar for tables */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Dark mode scrollbar */
    .dark .overflow-x-auto::-webkit-scrollbar-track {
        background: #374151;
    }
    .dark .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #6b7280;
    }
    .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    /* Extra pronounced shadow for dark mode */
    .dark .dark\:shadow-2xl {
        box-shadow: 0 8px 32px 0 rgba(31, 41, 55, 0.85), 0 1.5px 4px 0 rgba(0,0,0,0.10);
    }
</style>
@endsection