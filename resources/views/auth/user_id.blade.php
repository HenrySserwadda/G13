
   <!-- Footer -->
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('user_id') }}">
        @csrf

        <!-- User id -->
        <div class="mt-3">
            <x-input-label for="user_id" :value="__('User Identification Number')" />
            <x-text-input id="user_id" class="block mt-2 w-full px-3 py-2 text-m" type="text" name="user_id" :value="old('user_id')" required/>
            <x-input-error :messages="$errors->get('user_id')" class="mt-4" />
        </div>


        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

