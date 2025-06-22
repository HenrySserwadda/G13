<x-dashboardappearance>
    <x-slot name="content">
    @if($user->status=='approved'){
        @forEach($users as $user)
            <div>
                <p>{{ $user->name }}</p>
                <p>{{ $user->category }}</p>
                <p>{{ $user->email }}</p>
            </div>
        
    }
    </x-slot>
</x-dashboardappearance>