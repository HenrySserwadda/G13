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
        <div id="chartLoading" class="mt-4 flex items-center justify-center hidden">
            <svg class="animate-spin h-6 w-6 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span class="text-blue-600 font-semibold">Generating chart...</span>
        </div>
        <canvas id="analyticsChart" class="my-4"></canvas>
        <button id="downloadChartBtn" class="bg-green-600 text-white px-4 py-2 rounded">Download Chart</button>

        <!-- Sales Prediction UI -->
        <div class="mt-8 p-4 bg-gray-50 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">Predict Future Sales</h3>
            <form id="predictSalesForm" class="flex flex-wrap items-center gap-2">
                <label for="monthsAhead" class="mr-2">Months ahead:</label>
                <input type="number" id="monthsAhead" name="monthsAhead" min="1" max="12" value="1" class="border rounded px-2 py-1 w-20">
                <label for="material" class="mr-2">Material:</label>
                <select id="material" name="material" class="border rounded px-2 py-1">
                    <option value="">All</option>
                    <option value="Leather">Leather</option>
                    <option value="Canvas">Canvas</option>
                    <option value="Nylon">Nylon</option>
                    <option value="Polyester">Polyester</option>
                </select>
                <label for="size" class="mr-2">Size:</label>
                <select id="size" name="size" class="border rounded px-2 py-1">
                    <option value="">All</option>
                    <option value="Small">Small</option>
                    <option value="Medium">Medium</option>
                    <option value="Large">Large</option>
                </select>
                <label for="compartments" class="mr-2">Compartments:</label>
                <select id="compartments" name="compartments" class="border rounded px-2 py-1">
                    <option value="">All</option>
                    <option value="1.0">1.0</option>
                    <option value="2.0">2.0</option>
                    <option value="3.0">3.0</option>
                </select>
                <label for="laptopCompartment" class="mr-2">Laptop Compartment:</label>
                <select id="laptopCompartment" name="laptopCompartment" class="border rounded px-2 py-1">
                    <option value="">All</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <label for="waterproof" class="mr-2">Waterproof:</label>
                <select id="waterproof" name="waterproof" class="border rounded px-2 py-1">
                    <option value="">All</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <label for="style" class="mr-2">Style:</label>
                <select id="style" name="style" class="border rounded px-2 py-1">
                    <option value="">All</option>
                    <option value="Tote">Tote</option>
                    <option value="Backpack">Backpack</option>
                    <option value="Messenger">Messenger</option>
                </select>
                <label for="color" class="mr-2">Color:</label>
                <select id="color" name="color" class="border rounded px-2 py-1">
                    <option value="">All</option>
                    <option value="Green">Green</option>
                    <option value="Blue">Blue</option>
                    <option value="Black">Black</option>
                    <option value="Red">Red</option>
                </select>
                <label for="month" class="mr-2">Month:</label>
                <select id="month" name="month" class="border rounded px-2 py-1">
                    <option value="">All</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
                <label for="gender" class="mr-2">Gender:</label>
                <select id="gender" name="gender" class="border rounded px-2 py-1">
                    <option value="">All</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Unisex">Unisex</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Predict</button>
            </form>
            <div id="predictionLoading" class="mt-4 flex items-center justify-center hidden">
                <svg class="animate-spin h-6 w-6 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span class="text-blue-600 font-semibold">Loading prediction...</span>
            </div>
            <div id="predictionResult" class="mt-4 text-blue-800 font-bold"></div>
            <canvas id="predictionChart" class="my-4"></canvas>
            <button id="downloadPredictionChartBtn" class="bg-green-600 text-white px-4 py-2 rounded">Download Prediction Chart</button>
        </div>
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
    let predictionChartInstance = null;

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
        document.getElementById('chartLoading').classList.remove('hidden');
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
            document.getElementById('chartLoading').classList.add('hidden');
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
        })
        .catch(err => {
            document.getElementById('chartLoading').classList.add('hidden');
            alert('Error generating chart. Please try again.');
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

    document.getElementById('downloadChartBtn').onclick = function() {
        const canvas = document.getElementById('analyticsChart');
        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');
        link.download = 'chart.png';
        link.click();
    };

    document.getElementById('predictSalesForm').onsubmit = function(e) {
        e.preventDefault();
        document.getElementById('predictionLoading').classList.remove('hidden');
        const monthsAhead = document.getElementById('monthsAhead').value;
        const material = document.getElementById('material').value;
        const size = document.getElementById('size').value;
        const compartments = document.getElementById('compartments').value;
        const laptopCompartment = document.getElementById('laptopCompartment').value;
        const waterproof = document.getElementById('waterproof').value;
        const style = document.getElementById('style').value;
        const color = document.getElementById('color').value;
        const month = document.getElementById('month').value;
        const gender = document.getElementById('gender').value;
        fetch('/ml/predict-sales', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                months_ahead: monthsAhead,
                material,
                size,
                compartments,
                laptop_compartment: laptopCompartment,
                waterproof,
                style,
                color,
                month,
                gender
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('predictionLoading').classList.add('hidden');
            if (data && data.predictions && data.future_months) {
                let html = '<ul>';
                for (let i = 0; i < data.predictions.length; i++) {
                    html += `<li>Month: <b>${data.future_months[i]}</b> — Predicted Sales: <b>${data.predictions[i].toFixed(2)}</b></li>`;
                }
                html += '</ul>';
                document.getElementById('predictionResult').innerHTML = html;

                // Draw prediction chart
                const ctx = document.getElementById('predictionChart').getContext('2d');
                if (predictionChartInstance) {
                    predictionChartInstance.destroy();
                }
                predictionChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.future_months,
                        datasets: [{
                            label: 'Predicted Sales',
                            data: data.predictions,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            } else if (data && data.error) {
                document.getElementById('predictionResult').innerHTML = `<span class='text-red-600'>${data.error}</span>`;
                if (predictionChartInstance) {
                    predictionChartInstance.destroy();
                }
            } else {
                document.getElementById('predictionResult').innerHTML = '<span class="text-red-600">No prediction data returned.</span>';
                if (predictionChartInstance) {
                    predictionChartInstance.destroy();
                }
            }
        })
        .catch(err => {
            document.getElementById('predictionLoading').classList.add('hidden');
            document.getElementById('predictionResult').innerHTML = '<span class="text-red-600">Error fetching prediction.</span>';
            if (predictionChartInstance) {
                predictionChartInstance.destroy();
            }
        });
    };

    document.getElementById('downloadPredictionChartBtn').onclick = function() {
        const canvas = document.getElementById('predictionChart');
        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');
        link.download = 'prediction_chart.png';
        link.click();
    };
</script>
@endpush