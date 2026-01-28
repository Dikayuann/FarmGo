<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmGo Register</title>
    @vite('resources/css/app.css')
    <x-turnstile.scripts></x-turnstile.scripts>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-screen w-full bg-white">

<div class="flex h-full w-full">

    <!-- Login Form Section -->
    <div class="w-full md:w-1/2 flex flex-col justify-center px-8 sm:px-12 lg:px-24 bg-white z-10 rounded-lg shadow-lg">

        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('image/FarmGo.png') }}" alt="Logo FarmGo" class="max-w-24 max-h-24">
        </div>

        <!-- Register Form -->
        <form action="{{ route('register.submit') }}" method="POST" class="space-y-3" x-data="{ loading: false }" @submit="loading = true">
            @csrf

            <!-- Full Page Loading Overlay -->
            <div x-show="loading" x-cloak
                class="fixed inset-0 bg-white bg-opacity-95 flex items-center justify-center z-50">
                <div class="text-center">
                    <svg class="animate-spin h-16 w-16 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-lg font-semibold text-gray-700">Mendaftarkan akun...</p>
                    <p class="text-sm text-gray-500 mt-2">Mohon tunggu sebentar</p>
                </div>
            </div>

            <!-- Full Name -->
            <div>
                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" id="full_name" name="full_name" placeholder="Masukan Nama Lengkap"
                       autocomplete="name"
                       class="w-full px-4 py-2.5 rounded-lg border @error('full_name') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 text-sm transition duration-300"
                       value="{{ old('full_name') }}" required>
                @error('full_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Farm Name -->
            <div>
                <label for="farm_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Peternakan</label>
                <input type="text" id="farm_name" name="farm_name" placeholder="Masukan Nama Peternakan"
                       autocomplete="organization"
                       class="w-full px-4 py-2.5 rounded-lg border @error('farm_name') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 text-sm transition duration-300"
                       value="{{ old('farm_name') }}">
                @error('farm_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" placeholder="Masukan Email"
                       autocomplete="email"
                       class="w-full px-4 py-2.5 rounded-lg border @error('email') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 text-sm transition duration-300"
                       value="{{ old('email') }}" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Number -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Handphone</label>
                <input type="tel" id="phone" name="phone" placeholder="Masukan Nomor Handphone"
                       autocomplete="tel" maxlength="15"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                       class="w-full px-4 py-2.5 rounded-lg border @error('phone') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 text-sm transition duration-300"
                       value="{{ old('phone') }}">
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>



            <div class="relative">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="Masukan Password (min. 8 karakter)"
                           autocomplete="new-password"
                           class="w-full px-4 py-2.5 rounded-lg border @error('password') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 text-sm transition duration-300" required>

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
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                
                <!-- Password Strength Indicator (Simplified) -->
                <div id="passwordStrength" class="mt-1.5 hidden">
                    <!-- Strength Bar -->
                    <div class="flex gap-1 mb-1">
                        <div id="strength-bar-1" class="h-1 flex-1 rounded bg-gray-200 transition-all duration-300"></div>
                        <div id="strength-bar-2" class="h-1 flex-1 rounded bg-gray-200 transition-all duration-300"></div>
                        <div id="strength-bar-3" class="h-1 flex-1 rounded bg-gray-200 transition-all duration-300"></div>
                        <div id="strength-bar-4" class="h-1 flex-1 rounded bg-gray-200 transition-all duration-300"></div>
                    </div>
                    
                    <!-- Strength Text & Hint -->
                    <div class="flex items-center justify-between">
                        <p id="strengthText" class="text-xs font-medium"></p>
                        <p class="text-xs text-gray-500">Min. 8 karakter, huruf besar, angka & simbol</p>
                    </div>
                </div>
            </div>

            {{-- Turnstile CAPTCHA --}}
            <x-turnstile />

            <!-- Submit Button -->
            <button type="submit" :disabled="loading"
                class="w-full bg-blue-600 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-700 transition duration-300 shadow-sm disabled:opacity-70">
                Daftar
            </button>

        </form>

        <!-- Or Separator -->
        <div class="relative my-3">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-xs">
                <span class="px-2 bg-white text-gray-500">atau daftar dengan</span>
            </div>
        </div>



        <!-- Google Login Button -->
        <a href="{{ route('auth.google') }}" class="w-full bg-[#2d2d2d] text-white font-medium py-2.5 rounded-lg hover:bg-black transition duration-300 flex items-center justify-center gap-3">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M23.52 12.29C23.52 11.43 23.44 10.61 23.31 9.81H12V14.41H18.47C18.18 15.93 17.32 17.22 16.05 18.07V21.1H19.9C22.16 19.03 23.52 15.96 23.52 12.29Z" fill="#4285F4"/>
                <path d="M12 24C15.24 24 17.96 22.92 19.9 21.1L16.05 18.07C14.97 18.79 13.59 19.21 12 19.21C8.87 19.21 6.22 17.1 5.27 14.34H1.27V17.44C3.27 21.41 7.37 24 12 24Z" fill="#34A853"/>
                <path d="M5.27 14.34C5.03 13.57 4.9 12.79 4.9 12C4.9 11.21 5.03 10.43 5.27 9.66V6.56H1.27C0.46 8.16 0 9.99 0 12C0 14.01 0.46 15.84 1.27 17.44L5.27 14.34Z" fill="#FBBC05"/>
                <path d="M12 4.79C13.76 4.79 15.34 5.4 16.58 6.58L20.01 3.15C17.95 1.23 15.23 0 12 0C7.37 0 3.27 2.59 1.27 6.56L5.27 9.66C6.22 6.9 8.87 4.79 12 4.79Z" fill="#EA4335"/>
            </svg>
            Google
        </a>

        <!-- Login Link -->
        <p class="mt-2 text-center text-xs text-gray-600">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-500 font-medium hover:underline">Masuk</a>
        </p>
    </div>


    <!-- Right Side Image -->
    <div class="hidden md:block md:w-1/2 bg-cover bg-center relative"
         style="background-image: url('https://images.unsplash.com/photo-1596733430284-f7437764b1a9?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');">
        <div class="absolute inset-0 bg-black opacity-50"></div>
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

    // Password Strength Checker (Simplified)
    const passwordStrengthDiv = document.getElementById('passwordStrength');
    const strengthBars = [
        document.getElementById('strength-bar-1'),
        document.getElementById('strength-bar-2'),
        document.getElementById('strength-bar-3'),
        document.getElementById('strength-bar-4')
    ];
    const strengthText = document.getElementById('strengthText');

    password.addEventListener('input', function() {
        const value = this.value;
        
        if (value.length === 0) {
            passwordStrengthDiv.classList.add('hidden');
            return;
        }
        
        passwordStrengthDiv.classList.remove('hidden');
        
        // Check requirements
        const checks = {
            length: value.length >= 8,
            uppercase: /[A-Z]/.test(value),
            lowercase: /[a-z]/.test(value),
            number: /[0-9]/.test(value),
            special: /[!@#$%^&*(),.?":{}|<>]/.test(value)
        };
        
        // Calculate strength
        const metRequirements = Object.values(checks).filter(Boolean).length;
        let strength = 0;
        let strengthLabel = '';
        let strengthColor = '';
        
        if (metRequirements <= 1) {
            strength = 1;
            strengthLabel = 'Lemah';
            strengthColor = '#ef4444'; // red
        } else if (metRequirements === 2) {
            strength = 2;
            strengthLabel = 'Cukup';
            strengthColor = '#f59e0b'; // orange
        } else if (metRequirements === 3 || metRequirements === 4) {
            strength = 3;
            strengthLabel = 'Baik';
            strengthColor = '#3b82f6'; // blue
        } else if (metRequirements === 5) {
            strength = 4;
            strengthLabel = 'Sangat Kuat';
            strengthColor = '#10b981'; // green
        }
        
        // Update strength bars
        strengthBars.forEach((bar, index) => {
            if (index < strength) {
                bar.style.backgroundColor = strengthColor;
            } else {
                bar.style.backgroundColor = '#e5e7eb'; // gray-200
            }
        });
        
        // Update strength text
        strengthText.textContent = `Kekuatan: ${strengthLabel}`;
        strengthText.style.color = strengthColor;
    });

</script>
</body>
</html>
