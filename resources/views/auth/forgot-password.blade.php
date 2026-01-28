<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - FarmGo</title>
    @vite('resources/css/app.css')
    <x-turnstile.scripts></x-turnstile.scripts>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-screen w-full bg-white">

    <div class="flex h-full w-full">

        <!-- Forgot Password Form Section -->
        <div
            class="w-full md:w-1/2 flex flex-col justify-center px-8 sm:px-12 lg:px-24 bg-white z-10 rounded-lg shadow-lg">

            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <img src="{{ asset('image/FarmGo.png') }}" alt="Logo FarmGo" class="max-w-24 max-h-24">
            </div>

            <!-- Title -->
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Lupa Password?</h1>
                <p class="text-sm text-gray-600">Masukkan email Anda dan kami akan mengirimkan link untuk reset
                    password.</p>
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
                            @if(session('masked_email'))
                                <p class="text-sm text-green-600 mt-1">
                                    Email dikirim ke: <span class="font-semibold">{{ session('masked_email') }}</span>
                                </p>
                                <p class="text-xs text-green-600 mt-1">
                                    Silakan cek inbox atau folder spam Anda.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Error Messages --}}
            @if ($errors->any())
                <div
                    class="mb-6 p-4 rounded-lg border {{ $errors->has('email') && str_contains($errors->first('email'), 'Google') ? 'bg-blue-50 border-blue-200' : 'bg-red-50 border-red-200' }}">
                    <div class="flex items-start">
                        @if ($errors->has('email') && str_contains($errors->first('email'), 'Google'))
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        @endif
                        <div class="flex-1">
                            @foreach ($errors->all() as $error)
                                <p
                                    class="text-sm {{ $errors->has('email') && str_contains($errors->first('email'), 'Google') ? 'text-blue-700' : 'text-red-700' }} font-medium">
                                    {{ $error }}
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Turnstile CAPTCHA Error --}}
            @if ($errors->has('cf-turnstile-response'))
                <div class="mb-6 p-4 rounded-lg border bg-red-50 border-red-200">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-red-700 font-medium">{{ $errors->first('cf-turnstile-response') }}</p>
                        </div>
                    </div>
                </div>
            @endif


            <!-- Forgot Password Form -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan Email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 rounded-lg border {{ $errors->has('email') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} focus:outline-none focus:ring-2 placeholder-gray-400 text-sm transition duration-300"
                        required autofocus>
                </div>

                {{-- Turnstile CAPTCHA --}}
                <x-turnstile />

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition duration-300 shadow-sm">
                    Kirim Link Reset Password
                </button>

            </form>

            <!-- Back to Login Link -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}"
                    class="text-sm text-blue-500 hover:text-blue-600 font-medium flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Login
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