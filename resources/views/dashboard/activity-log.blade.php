@extends('components.dashboard')

@section('page-title', 'Activity Log')
@section('page-description', 'All recent activities in the system')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">System Activity Log</h1>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="space-y-4">
                @forelse($activities as $activity)
                    <div class="flex items-start">
                        <div class="bg-gray-100 p-2 rounded-full mr-3">
                            <i class="fas fa-{{ $activity->icon }} text-gray-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                <span>{{ $activity->created_at->diffForHumans() }}</span>
                                @if(isset($activity->causer) && $activity->causer && isset($activity->causer->name))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-800 text-xs font-semibold">
                                        <i class="fas fa-user-circle mr-1"></i>
                                        {{ $activity->causer->name }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-info-circle text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-500">No activities to display</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-6">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
@endsection 