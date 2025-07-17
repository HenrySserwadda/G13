<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>DURABAG</title>

    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    
   

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    boxShadow: {
                        'card': '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05)',
                        'card-hover': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans text-gray-800 antialiased bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <div class="min-h-screen flex flex-col items-center justify-center p-4 relative overflow-hidden">
        <!-- Background pattern -->
        <div class="absolute inset-0 z-0 opacity-5 dark:opacity-10">
            <div class="absolute inset-0 bg-[url('/images/bag-pattern.svg')] bg-repeat bg-[length:200px]"></div>
        </div>

        <!-- Main content container -->
        <div class="w-full max-w-md z-10">
            <!-- Logo and header -->
            <div class="flex flex-col items-center text-center mb-10">
                <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-xl shadow-md flex items-center justify-center mb-4 border border-gray-100 dark:border-gray-700">
                    <i class="fas fa-bag-shopping me-2 text-2xl text-blue-500"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    <span class="text-primary-600">DURA</span><span class="text-gray-800 dark:text-gray-200">BAG</span>
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 uppercase tracking-wider font-medium">
                    Supply Chain Management
                </p>
            </div>

            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-card border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-card-hover overflow-hidden">
                <div class="px-8 py-8 sm:px-10 sm:py-10">
                    {{ $slot }}
                </div>
              
            </div>
        </div>

       
    </div>
</body>
</html>