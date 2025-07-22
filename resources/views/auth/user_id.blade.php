
   <!-- Footer -->
<x-guest-layout>
    <style>
        body {
            background: linear-gradient(135deg, #6366f1 0%, #38bdf8 100%);
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            position: relative;
            overflow: hidden;
        }
        .bg-img-1, .bg-img-2 {
            position: fixed;
            z-index: 0;
            opacity: 0.22;
            pointer-events: none;
            filter: blur(0.5px) brightness(0.98) saturate(1.1);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18), 0 2px 8px rgba(99,102,241,0.10);
            border-radius: 32px;
            transition: transform 1.2s cubic-bezier(.39,.575,.56,1.000), opacity 0.8s;
        }
        .bg-img-1 {
            top: 4%;
            left: 1.5%;
            width: 320px;
            transform: rotate(-8deg) scale(0.92);
            animation: bgPop1 1.2s 0.2s cubic-bezier(.39,.575,.56,1.000) both;
        }
        .bg-img-2 {
            bottom: 0;
            right: 0;
            width: 400px;
            transform: rotate(7deg) scale(0.92);
            animation: bgPop2 1.2s 0.4s cubic-bezier(.39,.575,.56,1.000) both;
        }
        @keyframes bgPop1 {
            0% { opacity: 0; transform: rotate(-8deg) scale(0.7); }
            100% { opacity: 0.13; transform: rotate(-8deg) scale(0.92); }
        }
        @keyframes bgPop2 {
            0% { opacity: 0; transform: rotate(7deg) scale(0.7); }
            100% { opacity: 0.13; transform: rotate(7deg) scale(0.92); }
        }
        .login-card {
            background: rgba(255,255,255,0.95);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
            border-radius: 18px;
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 400px;
            margin: 60px auto 0 auto;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s cubic-bezier(.39,.575,.56,1.000) both;
        }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: none; }
        }
        .login-card h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .login-card label {
            color: #374151;
            font-weight: 500;
        }
        .login-card input[type="text"] {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
            width: 100%;
            font-size: 1rem;
            background: #f8fafc;
            transition: border 0.2s;
        }
        .login-card input:focus {
            border-color: #6366f1;
            outline: none;
        }
        .login-card .primary-btn {
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
        .login-card .primary-btn:hover {
            background: linear-gradient(90deg, #2563eb 0%, #0ea5e9 100%);
            transform: translateY(-2px) scale(1.03);
        }
        .login-card .forgot-link {
            color: #6366f1;
            text-decoration: underline;
            font-size: 0.95rem;
        }
    </style>
    <img src="/images/dataset/bag305.jpg" class="bg-img-1" alt="Decor 1">
    <img src="/images/dataset/bag168.jpg" class="bg-img-2" alt="Decor 2">
    <div class="login-card">
        <h1>Sign In by ID</h1>
        <form method="POST" action="{{ route('user_id') }}">
            @csrf
            <div class="mt-3">
                <x-input-label for="user_id" :value="__('User Identification Number')" />
                <x-text-input id="user_id" class="block mt-2 w-full px-3 py-2 text-m" type="text" name="user_id" :value="old('user_id')" required/>
                @if ($errors->has('user_id'))
                    <span class="text-red-500 text-sm">{{ $errors->first('user_id') }}</span>
                @endif

            </div>
            <div class="flex items-center justify-between mt-4">
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
                <button type="submit" class="primary-btn">{{ __('Log in') }}</button>
            </div>
        </form>
    </div>
    <script>
        // Animate card on load
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.login-card');
            if(card) {
                card.style.opacity = 0;
                setTimeout(() => { card.style.opacity = 1; }, 200);
            }
        });
    </script>
</x-guest-layout>

