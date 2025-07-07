<!--i still need to add server side code to restrict the type and size of file-->
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" enctype="multipart/form-data" action="{{ route('login') }}">
        @csrf

        <!-- Pdf insertion -->
        <div>
            <x-input-label for="wholesalerpdf" :value="__('Insert pdf')" />
            <x-text-input id="wholesalerpdf" class="block mt-1 w-full" type="file" name="wholesalerpdf" :value="old('wholesalerpdf')" required autofocus />
            <x-input-error :messages="$errors->get('pdf')" class="mt-2" />
        </div>
        <div>
            <x-primary-button class="ms-3">
                {{ __('Submit') }}
            </x-primary-button>
        </div>
</x-guest-layout>