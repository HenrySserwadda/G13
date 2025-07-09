@extends('components.dashboard')

@section('page-title', 'Customer Dashboard')
@section('page-description', 'Overview of your customer dashboard')


@section('content')    
{{-- this is me trying to inform the customer of what happened when they login to the system for the very first time. they should see a message telling them to check their email for login details --}}
    @if (session('first_time'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <strong>Success:</strong> {{ session('first_time') }}
        </div>
    @endif
    <p>This is the customer dashboard</p>


@endsection