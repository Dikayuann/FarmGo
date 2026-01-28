<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Sedang Maintenance | FarmGo</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-yellow-50 to-amber-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Logo -->
        <div class="mb-8 flex justify-center">
            <img src="{{ asset('image/FarmGo.png') }}" alt="FarmGo Logo" class="h-20 w-20">
        </div>

        <!-- Error Code -->
        <div class="mb-6">
            <h1 class="text-9xl font-bold text-amber-600 mb-2">503</h1>
            <div class="h-1 w-32 bg-gradient-to-r from-yellow-600 to-amber-600 mx-auto rounded-full"></div>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Sedang Maintenance</h2>
            <p class="text-gray-600 text-lg mb-2">Kami sedang melakukan pemeliharaan sistem.</p>
            <p class="text-gray-500">FarmGo akan kembali online sebentar lagi. Terima kasih atas kesabaran Anda.</p>
        </div>

        <!-- Illustration -->
        <div class="mb-8">
            <svg class="w-64 h-64 mx-auto text-amber-600 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                    clip-rule="evenodd" />
            </svg>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="location.reload()"
                class="px-6 py-3 bg-white text-amber-600 font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-300 border-2 border-amber-600">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Coba Lagi
                </span>
            </button>

            <a href="/"
                class="px-6 py-3 bg-gradient-to-r from-yellow-600 to-amber-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Ke Beranda
                </span>
            </a>
        </div>

        <!-- Help Text -->
        <div class="mt-8 text-sm text-gray-500">
            <p>Pertanyaan? <a href="mailto:support@farmgo.com" class="text-amber-600 hover:underline">Hubungi
                    Support</a></p>
        </div>
    </div>
</body>

</html>