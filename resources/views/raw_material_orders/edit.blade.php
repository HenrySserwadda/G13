@extends('components.dashboard')
@section('page-title', 'Update Order Status')
@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-md mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Update Order Status</h2>
            <a href="{{ route('raw-material-orders.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Orders
            </a>
        </div>

        <div class="space-y-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Order #</label>
                <div class="bg-gray-50 p-3 rounded-md border border-gray-200">{{ $order->id }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Placed By</label>
                <div class="bg-gray-50 p-3 rounded-md border border-gray-200">{{ $order->user->name ?? 'N/A' }} ({{ $order->user->user_id ?? '' }})</div>
            </div>
        </div>

        <form method="POST" action="{{ route('raw-material-orders.update', $order->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="pending" @if($order->status=='pending') selected @endif>Pending</option>
                    <option value="processing" @if($order->status=='processing') selected @endif>Processing</option>
                    <option value="completed" @if($order->status=='completed') selected @endif>Completed</option>
                    <option value="cancelled" @if($order->status=='cancelled') selected @endif>Cancelled</option>
                </select>
            </div>

            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('raw-material-orders.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection