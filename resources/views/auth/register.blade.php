<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full px-3 py-2 text-m" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full px-3 py-2 text-m" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        
        <!-- Category -->
        <div class="mt-4">
            <x-input-label class="mb-2" for="category" :value="__('Category')" />
            <label class="py-2 px-3 dark:text-gray-100 mb-1"><input type="radio" name="category" value="staff"{{ old('category')=='staff'?'checked':'' }}>Staff</label><br>
            <label class="py-2 px-3 dark:text-gray-100 mb-1"><input type="radio" name="category" value="wholesaler" {{ old('category')=='wholesaler'?'checked':'' }}>Wholesaler</label><br>
            <label class="py-2 px-3 dark:text-gray-100 mb-1"><input type="radio" name="category" value="supplier" {{ old('category')=='supplier'?'checked':'' }}>Supplier</label><br>
            <label class="py-2 px-3 dark:text-gray-100 mb-1"><input type="radio" name="category" value="retailer" {{ old('category')=='retailer'?'checked':'' }}>Retailer</label><br>
            <label class="py-2 px-3 dark:text-gray-100 mb-1"><input type="radio" name="category" value="customer" {{ old('category')=='customer'?'checked':'' }}>Customer</label>
            <br><x-input-error :messages="$errors->get('category')" class="mt-2" />
        </div>

        <!-- Date of birth -->
        <div id="date_of_birth" class="mt-4 hidden">
            <x-input-label for="date_of_birth" :value="__('Date of birth')" />
            <x-text-input id="dob" class="block mt-1 w-full px-3 py-2 text-m" type="date" name="date_of_birth" :value="old('date_of_birth')" required />
            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-3">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full px-3 py-2 text-m"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full px-3 py-2 text-m"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const radioButtons=document.querySelectorAll('input[name="category"]');
        const dobDiv=document.getElementById('date_of_birth');
        const inputDate =document.getElementById('dob');

        function showDobDiv(){
            const selected = document.querySelector('input[name="category"]:checked')?.value;
            if(selected ==='customer'){
                dobDiv.classList.remove('hidden');
                inputDate.required=true;
            }else{
                dobDiv.classList.add('hidden');
                inputDate.required=false;
                inputDate.value='';
            }

        }
        showDobDiv();
        radioButtons.forEach(radio => {
            radio.addEventListener('change', showDobDiv);
        });

    });
</script>    
</x-guest-layout>
