<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - FarmGo</title>
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

        <!-- Reset Password Form Section -->
        <div
            class="w-full md:w-1/2 flex flex-col justify-center px-8 sm:px-12 lg:px-24 bg-white z-10 rounded-lg shadow-lg">

            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <img src="{{ asset('image/FarmGo.png') }}" alt="Logo FarmGo" class="max-w-24 max-h-24">
            </div>

            <!-- Title -->
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Reset Password</h1>
                <p class="text-sm text-gray-600">Masukkan password baru Anda.</p>
            </div>

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

            <!-- Reset Password Form -->
            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <!-- Email Display (Read-only) -->
                <div>
                    <label for="email_display" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email_display" value="{{ $email ?? old('email') }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 text-sm"
                        readonly>
                </div>

                <!-- Password Input -->
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                            placeholder="Min 8 karakter, huruf besar, kecil, angka"
                            class="w-full px-4 py-3 rounded-lg border {{ $errors->has('password') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} focus:outline-none focus:ring-2 placeholder-gray-400 text-sm transition duration-300"
                            required>

                        <!-- Toggle Password Button -->
                        <button type="button" id="togglePassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition duration-200">
                            <svg id="iconEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="iconEyeSlash" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>

                    <!-- Password Strength Indicator -->
                    <div id="strengthIndicator" class="mt-2 hidden">
                        <div class="flex gap-1 mb-1">
                            <div id="strength1" class="h-1 flex-1 rounded bg-gray-200 transition-colors duration-300">
                            </div>
                            <div id="strength2" class="h-1 flex-1 rounded bg-gray-200 transition-colors duration-300">
                            </div>
                            <div id="strength3" class="h-1 flex-1 rounded bg-gray-200 transition-colors duration-300">
                            </div>
                            <div id="strength4" class="h-1 flex-1 rounded bg-gray-200 transition-colors duration-300">
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <p id="strengthText" class="text-xs font-medium"></p>
                            <div class="flex items-center gap-2 text-xs">
                                <span id="req-length" class="text-gray-400">8+</span>
                                <span id="req-lowercase" class="text-gray-400">a-z</span>
                                <span id="req-uppercase" class="text-gray-400">A-Z</span>
                                <span id="req-number" class="text-gray-400">0-9</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Confirmation Input -->
                <div class="relative">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi
                        Password</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Ulangi password baru"
                            class="w-full px-4 py-3 rounded-lg border {{ $errors->has('password') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} focus:outline-none focus:ring-2 placeholder-gray-400 text-sm transition duration-300"
                            required>

                        <!-- Toggle Password Confirmation Button -->
                        <button type="button" id="togglePasswordConfirmation"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition duration-200">
                            <svg id="iconEyeConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="iconEyeSlashConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition duration-300 shadow-sm">
                    Reset Password
                </button>

            </form>

        </div>

        <!-- Right Side Image -->
        <div class="hidden md:block md:w-1/2 bg-cover bg-center relative"
            style="background-image: url('https://images.unsplash.com/photo-1596733430284-f7437764b1a9?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');">
            <div class="absolute inset-0 bg-black opacity-50"></div>
        </div>

    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const iconEye = document.querySelector('#iconEye');
        const iconEyeSlash = document.querySelector('#iconEyeSlash');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            iconEye.classList.toggle('hidden');
            iconEyeSlash.classList.toggle('hidden');
        });

        // Toggle password confirmation visibility
        const togglePasswordConfirmation = document.querySelector('#togglePasswordConfirmation');
        const passwordConfirmation = document.querySelector('#password_confirmation');
        const iconEyeConfirm = document.querySelector('#iconEyeConfirm');
        const iconEyeSlashConfirm = document.querySelector('#iconEyeSlashConfirm');

        togglePasswordConfirmation.addEventListener('click', function () {
            const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmation.setAttribute('type', type);
            iconEyeConfirm.classList.toggle('hidden');
            iconEyeSlashConfirm.classList.toggle('hidden');
        });

        // Password strength checker
        const strengthIndicator = document.getElementById('strengthIndicator');
        const strengthBars = [
            document.getElementById('strength1'),
            document.getElementById('strength2'),
            document.getElementById('strength3'),
            document.getElementById('strength4')
        ];
        const strengthText = document.getElementById('strengthText');

        const reqLength = document.getElementById('req-length');
        const reqLowercase = document.getElementById('req-lowercase');
        const reqUppercase = document.getElementById('req-uppercase');
        const reqNumber = document.getElementById('req-number');

        function updateRequirement(element, met) {
            if (met) {
                element.classList.remove('text-gray-400');
                element.classList.add('text-green-600', 'font-semibold');
            } else {
                element.classList.remove('text-green-600', 'font-semibold');
                element.classList.add('text-gray-400');
            }
        }

        password.addEventListener('input', function() {
            const value = this.value;
            
            if (value.length === 0) {
                strengthIndicator.classList.add('hidden');
                return;
            }
            
            strengthIndicator.classList.remove('hidden');
            
            // Check requirements
            const hasLength = value.length >= 8;
            const hasLowercase = /[a-z]/.test(value);
            const hasUppercase = /[A-Z]/.test(value);
            const hasNumber = /[0-9]/.test(value);
            
            updateRequirement(reqLength, hasLength);
            updateRequirement(reqLowercase, hasLowercase);
            updateRequirement(reqUppercase, hasUppercase);
            updateRequirement(reqNumber, hasNumber);
            
            // Common weak passwords
            const commonPasswords = [
                'password', 'admin', 'user', 'test', 'qwerty', '123456', 
                'password1', 'admin123', 'user123', 'welcome', 'letmein',
                'monkey', 'dragon', 'master', 'sunshine', 'princess'
            ];
            
            const isCommon = commonPasswords.some(common => 
                value.toLowerCase().includes(common)
            );
            
            // Calculate strength
            let strength = 0;
            if (hasLength) strength++;
            if (hasLowercase) strength++;
            if (hasUppercase) strength++;
            if (hasNumber) strength++;
            
            // Penalize common passwords
            if (isCommon && strength === 4) {
                strength = 2; // Downgrade to medium
            }
            
            // Bonus for length
            if (value.length >= 12) strength = Math.min(4, strength + 0.5);
            
            // Reset all bars
            strengthBars.forEach(bar => {
                bar.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-green-500');
                bar.classList.add('bg-gray-200');
            });
            
            // Update strength indicator
            if (strength <= 1) {
                strengthBars[0].classList.remove('bg-gray-200');
                strengthBars[0].classList.add('bg-red-500');
                strengthText.textContent = 'Lemah';
                strengthText.className = 'text-xs font-medium text-red-600';
            } else if (strength === 2) {
                strengthBars[0].classList.remove('bg-gray-200');
                strengthBars[0].classList.add('bg-yellow-500');
                strengthBars[1].classList.remove('bg-gray-200');
                strengthBars[1].classList.add('bg-yellow-500');
                strengthText.textContent = isCommon ? 'Sedang (Terlalu Umum)' : 'Sedang';
                strengthText.className = 'text-xs font-medium text-yellow-600';
            } else if (strength === 3) {
                strengthBars[0].classList.remove('bg-gray-200');
                strengthBars[0].classList.add('bg-yellow-500');
                strengthBars[1].classList.remove('bg-gray-200');
                strengthBars[1].classList.add('bg-yellow-500');
                strengthBars[2].classList.remove('bg-gray-200');
                strengthBars[2].classList.add('bg-yellow-500');
                strengthText.textContent = 'Baik';
                strengthText.className = 'text-xs font-medium text-yellow-600';
            } else if (strength >= 4) {
                strengthBars.forEach(bar => {
                    bar.classList.remove('bg-gray-200');
                    bar.classList.add('bg-green-500');
                });
                strengthText.textContent = 'Kuat';
            }
        });
    </script>
</body>

</html>