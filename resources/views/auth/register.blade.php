<x-guest-layout>
    <style>
        .register-container {
            position: relative;
            z-index: 1;
        }

        .register-bg-images {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .register-bg-image {
            position: absolute;
            opacity: 0.2;
            filter: saturate(1.2) contrast(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        .register-bg-image-1 {
            top: 10%;
            right: 10%;
            width: 250px;
            transform: rotate(10deg);
            animation: floatRegister1 15s infinite ease-in-out;
        }

        .register-bg-image-2 {
            bottom: 15%;
            left: 5%;
            width: 200px;
            transform: rotate(-8deg);
            animation: floatRegister2 18s infinite ease-in-out;
        }

        .register-bg-image-3 {
            top: 50%;
            right: 25%;
            width: 180px;
            transform: rotate(15deg);
            animation: floatRegister3 20s infinite ease-in-out;
        }

        @keyframes floatRegister1 {
            0%, 100% { transform: translate(0, 0) rotate(10deg); }
            50% { transform: translate(-10px, -15px) rotate(12deg); }
        }

        @keyframes floatRegister2 {
            0%, 100% { transform: translate(0, 0) rotate(-8deg); }
            50% { transform: translate(15px, 10px) rotate(-10deg); }
        }

        @keyframes floatRegister3 {
            0%, 100% { transform: translate(0, 0) rotate(15deg); }
            50% { transform: translate(-15px, 15px) rotate(18deg); }
        }

        .register-form {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .register-form label {
            color: #000000;
            font-weight: 500;
        }
        
        .register-form input[type="text"],
        .register-form input[type="email"],
        .register-form input[type="password"],
        .register-form input[type="date"] {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.2);
            color: #000000;
            font-size: 1rem;
            font-weight: 400;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .register-form input[type="text"]:focus,
        .register-form input[type="email"]:focus,
        .register-form input[type="password"]:focus,
        .register-form input[type="date"]:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        .register-form button[type="submit"] {
            background: linear-gradient(90deg, #6366f1 0%, #38bdf8 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(99,102,241,0.12);
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .register-form button[type="submit"]:hover {
            background: linear-gradient(90deg, #2563eb 0%, #0ea5e9 100%);
            transform: translateY(-2px) scale(1.03);
        }
    </style>

    <div class="register-container">
        <div class="register-bg-images">
            <img src="{{ asset('images/dataset/bag23.jpg') }}" alt="" class="register-bg-image register-bg-image-1" />
            <img src="{{ asset('images/dataset/bag72.jpg') }}" alt="" class="register-bg-image register-bg-image-2" />
            <img src="{{ asset('images/dataset/bag116.jpg') }}" alt="" class="register-bg-image register-bg-image-3" />
        </div>

        <form method="POST" action="{{ route('register') }}" class="register-form">
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

        <!-- Gender -->
        <div class="mt-4">
            <x-input-label class="mb-2" for="gender" :value="__('Gender')" />
            <label class="py-2 px-3 dark:text-gray-100 mb-1"><input type="radio" name="gender" value="male"{{ old('gender')=='male'?'checked':'' }}>Male</label>
            <label class="py-2 px-3 dark:text-gray-100 mb-1"><input type="radio" name="gender" value="female" {{ old('gender')=='female'?'checked':'' }}>Female</label>
            <label class="py-2 px-3 dark:text-gray-100 mb-1"><input type="radio" name="gender" value="null" {{ old('gender')=='null'?'checked':'' }}>Prefer not to say</label>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Date of birth -->
        <div id="date_of_birth" class="mt-4">
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
</x-guest-layout>
