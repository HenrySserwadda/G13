@extends('components.dashboard')

@section('content')
<div class="container mx-auto p-4 space-y-6 dark:bg-gray-900 transition-colors duration-300">
    <!-- Dark Mode Toggle -->
    <div class="flex justify-end mb-4">
        <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg p-2.5">
            <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
            <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <!-- Workforce Allocation Header -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 dark:from-blue-600 dark:to-indigo-600 text-white p-6 rounded-lg shadow-lg flex flex-col md:flex-row justify-between items-center transition hover:shadow-xl">
        <div class="mb-4 md:mb-0 text-center md:text-left">
            <h3 class="text-2xl font-semibold">Optimize your workforce distribution across supply centers</h3>
            <p class="text-blue-100 dark:text-blue-200 text-lg">Track stock, sales, and workers to enhance performance efficiency</p>
        </div>
        <a href="{{ route('workforce.manage') }}" class="flex items-center bg-white dark:bg-gray-800 text-blue-700 dark:text-blue-300 font-semibold px-6 py-3 rounded-full shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Manage
        </a>
    </div>

    <!-- Table 1: Supply Center Summary -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
        <h3 class="text-xl font-semibold p-6 pb-2 text-gray-800 dark:text-gray-200 text-center">Supply Center Summary</h3>
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
                    @foreach ($analysis as $item)
                    <tr class="hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <td class="p-3 dark:text-gray-300">{{ $item['center']->name }}</td>
                        <td class="p-3 text-center dark:text-gray-300">{{ number_format($item['center']->sales->last()->monthly_sales ?? 0) }}</td>
                        <td class="p-3 text-center dark:text-gray-300">{{ number_format($item['center']->stocks->sum('quantity')) }}</td>
                        <td class="p-3 text-center dark:text-gray-300">{{ $item['center']->workers->count() }}</td>
                        <td class="p-3 text-center font-bold 
                            @if($item['analysis']['status'] == 'Surplus') text-red-500 dark:text-red-400
                            @elseif($item['analysis']['status'] == 'Deficit') text-yellow-500 dark:text-yellow-400
                            @else text-green-500 dark:text-green-400
                            @endif">
                            {{ $item['analysis']['status'] }}
                        </td>
                        <td class="p-3 text-center dark:text-gray-300">{{ $item['analysis']['reason'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>

    <!-- Table 2: Workforce Allocation History -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
        <h3 class="text-xl font-semibold p-6 pb-2 text-gray-800 dark:text-gray-200 text-center">Workforce Allocation History</h3>
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
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
        <h3 class="text-xl font-semibold p-6 pb-2 text-gray-800 dark:text-gray-200 text-center">Stock Summary</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gradient-to-r from-green-400 to-green-600 dark:from-green-500 dark:to-green-700 text-white">
                    <tr>
                        <th class="p-3 text-left">Supply Center</th>
                        <th class="p-3 text-center">Stock Quantity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($stocks as $stock)
                    <tr class="hover:bg-green-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <td class="p-3 dark:text-gray-300">{{ $stock->supplyCenter->name }}</td>
                        <td class="p-3 text-center dark:text-gray-300">{{ number_format($stock->quantity) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>

    <!-- Table 4: Sales Summary -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
        <h3 class="text-xl font-semibold p-6 pb-2 text-gray-800 dark:text-gray-200 text-center">Sales Summary (Monthly)</h3>
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
                    @foreach ($sales as $sale)
                    <tr class="hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <td class="p-3 dark:text-gray-300">{{ $sale->supplyCenter->name }}</td>
                        <td class="p-3 text-center dark:text-gray-300">{{ date('F Y', strtotime($sale->sales_month)) }}</td>
                        <td class="p-3 text-center dark:text-gray-300">{{ number_format($sale->monthly_sales) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>

    <!-- Graph 1: Stock & Sales vs Workforce -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 transition-all duration-300 hover:shadow-xl">
        <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Stock & Sales vs Workforce</h3>
        <div class="h-80">
            <canvas id="stockSalesWorkforceChart"></canvas>
        </div>
    </div>
    <br>

    <!-- Graph 2: Center Performance Post Allocation -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 transition-all duration-300 hover:shadow-xl">
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

    // Initialize charts
    let stockSalesChart, centerPerfChart;

    function createCharts() {
        const isDarkMode = document.documentElement.classList.contains('dark');
        const colors = isDarkMode ? darkColors : lightColors;
        const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
        const textColor = isDarkMode ? '#e5e7eb' : '#374151';

        // Destroy existing charts if they exist
        if (stockSalesChart) stockSalesChart.destroy();
        if (centerPerfChart) centerPerfChart.destroy();

        // Graph 1: Stock & Sales vs Workforce
        const stockSalesCtx = document.getElementById('stockSalesWorkforceChart').getContext('2d');
        stockSalesChart = new Chart(stockSalesCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($analysis->pluck('center.name')) !!},
                datasets: [
                    {
                        label: 'Stock Available',
                        data: {!! json_encode($analysis->map(fn($a) => $a['center']->stocks->sum('quantity'))) !!},
                        backgroundColor: colors,
                        borderColor: colors.map(c => c.replace('0.7', '1')),
                        borderWidth: 1
                    },
                    {
                        label: 'Sales (Monthly)',
                        data: {!! json_encode($analysis->map(fn($a) => $a['center']->sales->last()->monthly_sales ?? 0)) !!},
                        backgroundColor: colors.map(c => c.replace('0.7', '0.4')),
                        borderColor: colors.map(c => c.replace('0.7', '1')),
                        borderWidth: 1,
                        type: 'line',
                        tension: 0.3,
                        fill: false,
                        pointBackgroundColor: colors,
                        pointBorderColor: isDarkMode ? '#1f2937' : '#fff',
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
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
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
                labels: {!! json_encode($analysis->pluck('center.name')) !!},
                datasets: [
                    {
                        label: 'Sales After Allocation',
                        data: {!! json_encode($analysis->map(fn($a) => $a['center']->sales->last()->monthly_sales ?? 0)) !!},
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: isDarkMode ? 'rgba(255, 99, 132, 0.2)' : 'rgba(255, 99, 132, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                        pointBorderColor: isDarkMode ? '#1f2937' : '#fff',
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 2,
                        borderWidth: 2
                    },
                    {
                        label: 'Workers After Allocation',
                        data: {!! json_encode($analysis->map(fn($a) => $a['center']->workers->count())) !!},
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: isDarkMode ? 'rgba(54, 162, 235, 0.2)' : 'rgba(54, 162, 235, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        pointBorderColor: isDarkMode ? '#1f2937' : '#fff',
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
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
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

    // Dark mode toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            themeToggleDarkIcon.classList.remove('hidden');
        }

        // Create charts with initial theme
        createCharts();

        themeToggleBtn.addEventListener('click', function() {
            // Toggle icons
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // If set via local storage previously
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                // If NOT set via local storage previously
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }

            // Recreate charts with new theme
            createCharts();
        });
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
</style>
@endsection