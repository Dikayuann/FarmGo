<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - FarmGo</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-screen w-full bg-white">

    <div class="flex h-full w-full">

        <!-- Verification Notice Section -->
        <div
            class="w-full md:w-1/2 flex flex-col justify-center px-8 sm:px-12 lg:px-24 bg-white z-10 rounded-lg shadow-lg">

            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <img src="{{ asset('image/FarmGo.png') }}" alt="Logo FarmGo" class="max-w-24 max-h-24">
            </div>

            <!-- Title -->
            <div class="mb-6 text-center">
                <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Verifikasi Email Anda</h1>
                <p class="text-sm text-gray-600">Kami telah mengirimkan link verifikasi ke email Anda.</p>
            </div>

            {{-- Success Messages --}}
            @if (session('success'))
                <div class="mb-6 p-4 rounded-lg border bg-green-50 border-green-200">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg border bg-red-50 border-red-200">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <div class="flex-1">
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-700 font-medium">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Info Box -->
            <div class="mb-6 p-4 rounded-lg border bg-blue-50 border-blue-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm text-blue-700 font-medium mb-1">Cek Email Anda</p>
                        <p class="text-xs text-blue-600">
                            Silakan cek inbox email Anda di <strong>{{ auth()->user()->email }}</strong> dan klik link
                            verifikasi.
                            Jika tidak ada di inbox, cek folder spam.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Resend Form -->
            <form action="{{ route('verification.resend') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Resend Button -->
                <button type="submit"
                    class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition duration-300 shadow-sm">
                    Kirim Ulang Email Verifikasi
                </button>

            </form>

            <!-- Back to Login Link -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium">
                    Kembali ke Halaman Login
                </a>
            </div>

        </div>

        <!-- Right Side Image -->
        <div class="hidden md:block md:w-1/2 bg-cover bg-center relative"
            style="background-image: url('https://images.unsplash.com/photo-1596733430284-f7437764b1a9?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');">
            <div class="absolute inset-0 bg-black opacity-50"></div>
        </div>

    </div>

</body>

</html>