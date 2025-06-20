<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DURABAG App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <nav class="bg-white shadow p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-xl font-bold text-blue-600">Durabag</a>
            <div>
                <a href="{{ route('products.index') }}" class="text-sm text-gray-700 hover:text-blue-600 mr-4">Products</a>
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-blue-600">Dashboard</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto py-8">
        @yield('content')
    </main>

    {{-- Flowbite JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.0/flowbite.min.js"></script>
</body>
</html>
