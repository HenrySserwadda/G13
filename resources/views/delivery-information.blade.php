<x-guest-layout>
    <form method="POST" action=""><!--i need to send this information using a post method but i dont know to which table-->
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone number -->
        <div class="mt-4">
            <x-input-label for="tel" :value="__('Phone number')" />
            <x-text-input id="telno" class="block mt-1 w-full" type="tel" name="telno" :value="old('tel')" required />
            <x-input-error :messages="$errors->get('tel')" class="mt-2" />
        </div>

        <!-- City -->
        <div class="mt-4">
            <x-input-label for="city" :value="__('City')" />
            <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>

        <!-- Street -->
        <div class="mt-4">
            <x-input-label for="street" :value="__('Street')" />
            <x-text-input id="street" class="block mt-1 w-full" type="text" name="street" :value="old('street')" required />
            <x-input-error :messages="$errors->get('street')" class="mt-2" />
        </div>
        
        

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Checkout') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
