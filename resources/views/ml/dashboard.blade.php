@extends('components.dashboard')

@section('page-title', 'Sales Analytics Dashboard')
@section('page-description', 'Machine Learning powered sales insights and analytics')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Sales Analytics Dashboard</h1>
        <p class="text-gray-600">Machine Learning powered sales insights and analytics</p>
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

<script>
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
@endsection