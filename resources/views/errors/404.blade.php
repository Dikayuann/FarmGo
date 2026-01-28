<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | FarmGo</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 to-green-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Logo -->
        <div class="mb-8 flex justify-center">
            <img src="{{ asset('image/FarmGo.png') }}" alt="FarmGo Logo" class="h-20 w-20">
        </div>

        <!-- Error Code -->
        <div class="mb-6">
            <h1 class="text-9xl font-bold text-blue-600 mb-2">404</h1>
            <div class="h-1 w-32 bg-gradient-to-r from-blue-600 to-green-600 mx-auto rounded-full"></div>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Halaman Tidak Ditemukan</h2>
            <p class="text-gray-600 text-lg mb-2">Maaf, halaman yang Anda cari tidak dapat ditemukan.</p>
            <p class="text-gray-500">Halaman mungkin telah dipindahkan atau tidak pernah ada.</p>
        </div>

        <!-- Illustration -->
        <div class="mb-8">
            <svg class="w-64 h-64 mx-auto text-blue-600 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.history.back()"
                class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-300 border-2 border-blue-600">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </span>
            </button>

            <a href="{{ route('dashboard') }}"
                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Ke Dashboard
                </span>
            </a>
        </div>

        <!-- Help Text -->
        <div class="mt-8 text-sm text-gray-500">
            <p>Butuh bantuan? <a href="mailto:support@farmgo.com" class="text-blue-600 hover:underline">Hubungi
                    Support</a></p>
        </div>
    </div>
</body>

</html>