<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmGo Login</title>
    @vite('resources/css/app.css')
    <x-turnstile.scripts></x-turnstile.scripts>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

</head>

<body class="h-screen w-full bg-white">

    <div class="flex h-full w-full">

        <!-- Login Form Section -->
        <div
            class="w-full md:w-1/2 flex flex-col justify-center px-8 sm:px-12 lg:px-24 bg-white z-10 rounded-lg shadow-lg">

            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <img src="{{ asset('image/FarmGo.png') }}" alt="Logo FarmGo" class="max-w-24 max-h-24">
            </div>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg border {{ $errors->has('email') && str_contains($errors->first('email'), 'terkunci') ? 'bg-red-50 border-red-200' : 'bg-orange-50 border-orange-200' }}"
                    x-data="{ sent: false, loading: false }">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 {{ $errors->has('email') && str_contains($errors->first('email'), 'terkunci') ? 'text-red-600' : 'text-orange-600' }} mt-0.5 mr-3"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <div class="flex-1">
                            @foreach ($errors->all() as $error)
                                <p
                                    class="text-sm {{ $errors->has('email') && str_contains($errors->first('email'), 'terkunci') ? 'text-red-700' : 'text-orange-700' }} font-medium">
                                    {{ $error }}
                                </p>
                            @endforeach

                            {{-- Link to clear lockout if account is locked --}}
                            @if (($errors->has('email') && str_contains($errors->first('email'), 'terkunci')) || ($errors->has('password') && str_contains($errors->first('password'), 'terkunci')))
                                <p class="text-xs text-red-600 mt-2">
                                    Tidak ingin menunggu?
                                    <a href="{{ route('login.clear') }}" class="underline hover:text-red-800 font-medium">Klik
                                        di sini untuk reset</a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4" x-data="{ loading: false }"
                @submit="loading = true">

                @csrf <!-- CSRF Token untuk melindungi aplikasi dari CSRF attack -->

                <!-- Full Page Loading Overlay -->
                <div x-show="loading" x-cloak
                    class="fixed inset-0 bg-white bg-opacity-95 flex items-center justify-center z-50">
                    <div class="text-center">
                        <svg class="animate-spin h-16 w-16 text-blue-600 mx-auto mb-4"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <p class="text-lg font-semibold text-gray-700">Memproses login...</p>
                        <p class="text-sm text-gray-500 mt-2">Mohon tunggu sebentar</p>
                    </div>
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukan Email" value="{{ old('email') }}"
                        autocomplete="email"
                        class="w-full px-4 py-3 rounded-lg border {{ $errors->has('email') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} focus:outline-none focus:ring-2 placeholder-gray-400 text-sm transition duration-300"
                        required>
                </div>

                <!-- Password Input -->
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Masukan Password"
                            autocomplete="current-password"
                            class="w-full px-4 py-3 rounded-lg border {{ $errors->has('password') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} focus:outline-none focus:ring-2 placeholder-gray-400 text-sm transition duration-300"
                            required>

                        <!-- Tombol untuk Toggle Password -->
                        <button type="button" id="togglePassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition duration-200">

                            <!-- Icon Eye (Tampilkan Password) -->
                            <svg id="iconEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>

                            <!-- Icon Eye Slash (Sembunyikan Password) -->
                            <svg id="iconEyeSlash" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Forgot Password Link -->
                <div class="flex justify-end">
                    <a href="{{ route('password.request') }}"
                        class="text-xs font-medium text-blue-500 hover:text-blue-600">Forgot password?</a>
                </div>

                {{-- Turnstile CAPTCHA --}}
                <x-turnstile />

                <!-- Submit Button -->
                <button type="submit" :disabled="loading"
                    class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition duration-300 shadow-sm disabled:opacity-70">
                    Masuk
                </button>

            </form>



            <!-- Or Separator -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-2 bg-white text-gray-500">atau masuk dengan</span>
                </div>
            </div>

            <!-- Google Login Button -->
            <a href="{{ route('auth.google.login') }}"
                class="w-full bg-[#2d2d2d] text-white font-medium py-3 rounded-lg hover:bg-black transition duration-300 flex items-center justify-center gap-3">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M23.52 12.29C23.52 11.43 23.44 10.61 23.31 9.81H12V14.41H18.47C18.18 15.93 17.32 17.22 16.05 18.07V21.1H19.9C22.16 19.03 23.52 15.96 23.52 12.29Z"
                        fill="#4285F4" />
                    <path
                        d="M12 24C15.24 24 17.96 22.92 19.9 21.1L16.05 18.07C14.97 18.79 13.59 19.21 12 19.21C8.87 19.21 6.22 17.1 5.27 14.34H1.27V17.44C3.27 21.41 7.37 24 12 24Z"
                        fill="#34A853" />
                    <path
                        d="M5.27 14.34C5.03 13.57 4.9 12.79 4.9 12C4.9 11.21 5.03 10.43 5.27 9.66V6.56H1.27C0.46 8.16 0 9.99 0 12C0 14.01 0.46 15.84 1.27 17.44L5.27 14.34Z"
                        fill="#FBBC05" />
                    <path
                        d="M12 4.79C13.76 4.79 15.34 5.4 16.58 6.58L20.01 3.15C17.95 1.23 15.23 0 12 0C7.37 0 3.27 2.59 1.27 6.56L5.27 9.66C6.22 6.9 8.87 4.79 12 4.79Z"
                        fill="#EA4335" />
                </svg>
                Masuk dengan Google
            </a>

            <!-- Sign Up Link -->
            <p class="mt-8 text-center text-xs text-gray-600">
                Belum punya akun? <a href="{{ route('register') }}"
                    class="text-blue-500 font-medium hover:underline">Daftar</a>
            </p>
        </div>

        <!-- Right Side Image -->
        <div class="hidden md:block md:w-1/2 bg-cover bg-center relative"
            style="background-image: url('https://images.unsplash.com/photo-1596733430284-f7437764b1a9?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');">
            <div class="absolute inset-0 bg-black opacity-50"></div> <!-- Overlay hitam dengan transparansi -->
        </div>

    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const iconEye = document.querySelector('#iconEye');
        const iconEyeSlash = document.querySelector('#iconEyeSlash');

        togglePassword.addEventListener('click', function () {
            // Toggle tipe password
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle ikon
            iconEye.classList.toggle('hidden');
            iconEyeSlash.classList.toggle('hidden');
        });
    </script>
</body>

</html>