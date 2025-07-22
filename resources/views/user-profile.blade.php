@extends('components.dashboard')
@section('title', 'User Profile - DURABAG')
@section('content')
    <div class="max-w-xl mx-auto mt-10 bg-white rounded-2xl shadow-md p-6 border border-gray-200">
        <h2 class="text-2xl font-semibold text-gray-900 center mb-6 border-b pb-2">User Profile</h2>
        {{--<div class="flex justify-center mb-6">
            <img src="{{ $user->avatar_url }}" alt="User Avatar" class="w-24 h-24 rounded-full shadow-md object-cover">
        </div>
        <div class="flex justify-center mb-6">
            @livewire('avatar-upload', ['user' => $user], key($user->id))
        </div>--}}
        <table class="w-full text-m text-left text-gray-800">
            <tbody>
                <tr class="border-b">
                    <th class="py-3 pr-4 font-medium text-gray-500">Name:</th>
                    <td class="py-3">{{ $user->name }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-3 pr-4 font-medium text-gray-500">Email:</th>
                    <td class="py-3">{{ $user->email }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-3 pr-4 font-medium text-gray-500">User ID:</th>
                    <td class="py-3">{{ $user->user_id ?? '--' }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-3 pr-4 font-medium text-gray-500">Category:</th>
                    <td class="py-3 capitalize">{{ $user->category ?? '—' }}</td>
                </tr>
                <tr>
                    <th class="py-3 pr-4 font-medium text-gray-500">Status:</th>
                    <td class="py-3 capitalize">
                        <span class="inline-block px-3 py-1 rounded-full 
                            {{ $user->status === 'application approved' ? 'bg-green-100 text-green-800' : 
                               ($user->status === 'application received' ? 'bg-yellow-100 text-yellow-800' : 
                               ($user->status==='application rejected'?'bg-red-100 text-red-800':'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
