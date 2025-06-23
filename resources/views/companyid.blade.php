<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('companyid') }}">
        @csrf

        <!-- Company identification number -->
        <div>
            <x-input-label for="companyid" :value="__('Company identification number')" />
            <x-text-input id="companyid" class="block mt-1 w-full" type="text" name="companyid" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
</x-guest-layout>