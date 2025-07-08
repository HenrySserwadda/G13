@extends('components.dashboard')

@section('page-title', 'Sales Analytics Dashboard')
@section('page-description', 'Machine Learning powered sales insights and analytics')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Sales Analytics Dashboard</h1>
        <p class="text-gray-600">Machine Learning powered sales insights and analytics</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <!-- Chart option fields -->
            <div>
                <label for="chartType" class="block text-sm font-medium text-gray-700">Chart Type</label>
                <select id="chartType" class="mt-1 block w-full border-gray-300 rounded" name="chartType">
                    <option value="bar">Bar</option>
                    <option value="line">Line</option>
                    <option value="pie">Pie</option>
                </select>
            </div>
            <div>
                <label for="xAxis" class="block text-sm font-medium text-gray-700">X-Axis</label>
                <select id="xAxis" class="mt-1 block w-full border-gray-300 rounded" name="xAxis">
                    <option value="Month of the year">Month of the year</option>
                    <option value="Material">Material</option>
                    <option value="Size">Size</option>
                    <option value="Compartments">Compartments</option>
                    <option value="Laptop Compartment">Laptop Compartment</option>
                    <option value="Waterproof">Waterproof</option>
                    <option value="Style">Style</option>
                    <option value="Color">Color</option>
                    <option value="Gender">Gender</option>
                </select>
            </div>
            <div>
                <label for="xAxis2" class="block text-sm font-medium text-gray-700">Second X-Axis (optional)</label>
                <select id="xAxis2" class="mt-1 block w-full border-gray-300 rounded" name="xAxis2">
                    <option value="">None</option>
                    <option value="Month of the year">Month of the year</option>
                    <option value="Material">Material</option>
                    <option value="Size">Size</option>
                    <option value="Compartments">Compartments</option>
                    <option value="Laptop Compartment">Laptop Compartment</option>
                    <option value="Waterproof">Waterproof</option>
                    <option value="Style">Style</option>
                    <option value="Color">Color</option>
                    <option value="Gender">Gender</option>
                </select>
            </div>
            <div>
                <label for="yAxis" class="block text-sm font-medium text-gray-700">Y-Axis</label>
                <select id="yAxis" class="mt-1 block w-full border-gray-300 rounded" name="yAxis">
                    <option value="Weight Capacity (kg)">Weight Capacity (kg)</option>
                    <option value="sales">Sales (frequency)</option>
                </select>
            </div>
        </div>
        <button id="generateChartBtn" class="bg-blue-600 text-white px-4 py-2 rounded">Generate Chart</button>
        <canvas id="analyticsChart" class="my-4"></canvas>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Monthly Sales Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Sales by Month</h3>
            <div class="flex justify-center">
                <img src="{{ $images['monthly_sales'] }}" alt="Sales by Month" class="max-w-full h-auto rounded-lg">
            </div>
            <p class="text-sm text-gray-500 mt-3">Monthly sales distribution analysis</p>
        </div>
        <!-- Material Sales Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Sales by Material</h3>
            <div class="flex justify-center">
                <img src="{{ $images['material_sales'] }}" alt="Sales by Material" class="max-w-full h-auto rounded-lg">
            </div>
            <p class="text-sm text-gray-500 mt-3">Material preference analysis</p>
        </div>
        <!-- Gender Sales Chart -->
        <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Sales by Gender</h3>
            <div class="flex justify-center">
                <img src="{{ $images['gender_sales'] }}" alt="Sales by Gender" class="max-w-full h-auto rounded-lg">
            </div>
            <p class="text-sm text-gray-500 mt-3">Gender-based sales distribution</p>
        </div>
    </div>
    <!-- Action Buttons -->
    <div class="mt-8 flex flex-wrap gap-4">
        <a href="{{ route('ml.recommendations', 1) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-150">
            <i class="fas fa-lightbulb mr-2"></i>
            View Product Recommendations
        </a>
        <button onclick="trainModels()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-150">
            <i class="fas fa-cog mr-2"></i>
            Retrain Models
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = @json($chartData);

    let chartInstance = null;

    // Only render initial chart if chartData and chartData.type exist
    if (chartData && chartData.type) {
        let config;
        if (chartData.type === 'pie') {
            config = {
                type: 'pie',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        data: chartData.values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                        ]
                    }]
                },
                options: {
                    animation: { animateScale: true }
                }
            };
        } else if (chartData.datasets) {
            config = {
                type: chartData.type,
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets.map((ds, i) => ({
                        label: ds.label,
                        data: ds.data,
                        backgroundColor: `rgba(${54 + i*50}, 162, 235, 0.5)`
                    }))
                },
                options: {
                    animation: { duration: 1500, easing: 'easeOutBounce' }
                }
            };
        } else {
            config = {
                type: chartData.type,
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Value',
                        data: chartData.values,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)'
                    }]
                },
                options: {
                    animation: { duration: 1500, easing: 'easeOutBounce' }
                }
            };
        }
        const ctx = document.getElementById('analyticsChart').getContext('2d');
        chartInstance = new Chart(ctx, config);
    }

    document.getElementById('generateChartBtn').onclick = function() {
        const chartType = document.getElementById('chartType').value;
        const xAxis = document.getElementById('xAxis').value;
        const xAxis2 = document.getElementById('xAxis2').value;
        const yAxis = document.getElementById('yAxis').value;

        fetch('/ml/custom-chart', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ chartType, xAxis, xAxis2, yAxis })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.chartData || !data.chartData.type) {
                alert('No chart data returned from server.');
                return;
            }
            const chartData = data.chartData;
            let config;
            if (chartData.type === 'pie') {
                config = {
                    type: 'pie',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            data: chartData.values,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(255, 206, 86, 0.5)',
                            ]
                        }]
                    },
                    options: {
                        animation: { animateScale: true }
                    }
                };
            } else if (chartData.datasets) {
                config = {
                    type: chartData.type,
                    data: {
                        labels: chartData.labels,
                        datasets: chartData.datasets.map((ds, i) => ({
                            label: ds.label,
                            data: ds.data,
                            backgroundColor: `rgba(${54 + i*50}, 162, 235, 0.5)`
                        }))
                    },
                    options: {
                        animation: { duration: 1500, easing: 'easeOutBounce' }
                    }
                };
            } else {
                config = {
                    type: chartData.type,
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Value',
                            data: chartData.values,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)'
                        }]
                    },
                    options: {
                        animation: { duration: 1500, easing: 'easeOutBounce' }
                    }
                };
            }

            // Destroy previous chart if exists
            if (chartInstance) {
                chartInstance.destroy();
            }
            const ctx = document.getElementById('analyticsChart').getContext('2d');
            chartInstance = new Chart(ctx, config);
        });
    };

    function trainModels() {
        if (confirm('This will retrain the ML models. This may take a few minutes. Continue?')) {
            fetch('{{ route("ml.train") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Models trained successfully!');
                    location.reload(); // Refresh to show new charts
                } else {
                    alert('Error training models. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error training models. Please try again.');
            });
        }
    }
</script>
@endpush