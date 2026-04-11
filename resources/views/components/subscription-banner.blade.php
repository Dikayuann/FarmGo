@php
    $user = Auth::user();
    $daysUntil = $user->getDaysUntilExpiry();
@endphp

{{-- Trial Ending Soon (within 7 days before expiry) --}}
@if($user->isTrialEndingSoon())
    <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.300ms class="bg-yellow-50 border-b border-yellow-200">
        <div class="px-4 py-3 mx-auto flex items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-yellow-100 p-1.5 rounded-full shrink-0">
                    <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs sm:text-sm text-yellow-800">
                        <span class="font-semibold">{{ $user->isOnTrial() ? 'Trial' : 'Langganan' }} Anda akan berakhir dalam {{ $daysUntil }} hari.</span>
                        <span class="hidden sm:inline"> Perpanjang untuk fitur tanpa batas.</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('langganan.index') }}" class="text-xs font-semibold px-3 py-1.5 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition">
                    Perpanjang
                </a>
                <button @click="show = false" type="button" class="text-yellow-500 hover:text-yellow-700 hover:bg-yellow-100 p-1 rounded-md transition" aria-label="Dismiss">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>
@endif

{{-- Grace Period (0-3 days after expiry) --}}
@if($user->isInGracePeriod())
    @php
        $graceDaysLeft = 3 - $user->getDaysSinceExpiry();
    @endphp
    <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.300ms class="bg-orange-50 border-b border-orange-200">
        <div class="px-4 py-3 mx-auto flex items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-orange-100 p-1.5 rounded-full shrink-0">
                    <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs sm:text-sm text-orange-800">
                        <span class="font-semibold">Masa tunggu (Grace Period)! Akses tersisa {{ $graceDaysLeft }} hari.</span>
                        <span class="hidden sm:inline"> Akun akan segera dibatasi.</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('langganan.index') }}" class="text-xs font-semibold px-3 py-1.5 bg-orange-600 text-white rounded hover:bg-orange-700 transition">
                    Perbarui
                </a>
                <button @click="show = false" type="button" class="text-orange-500 hover:text-orange-700 hover:bg-orange-100 p-1 rounded-md transition" aria-label="Dismiss">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>
@endif

{{-- Read-Only Mode (4-30 days after expiry) --}}
@if($user->isReadOnlyMode())
    @php
        $daysSinceExpiry = $user->getDaysSinceExpiry();
        $daysUntilLock = 30 - $daysSinceExpiry;
    @endphp
    <!-- Removed sticky and top-20 so it does not overlay and block clicks -->
    <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.300ms class="bg-red-50 border-b border-red-200">
        <div class="px-4 py-3 mx-auto flex items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-red-100 p-1.5 rounded-full shrink-0">
                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs sm:text-sm text-red-800">
                        <span class="font-bold">Mode Baca Saja.</span> Langganan berakhir. Akses penuh akan terkunci dalam {{ $daysUntilLock }} hari.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="text-[10px] font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded uppercase tracking-wider hidden sm:inline-block">
                    H+{{ $daysSinceExpiry }}
                </span>
                <a href="{{ route('langganan.index') }}" class="text-xs font-bold px-3 py-1.5 bg-red-600 text-white rounded hover:bg-red-700 transition shadow-sm">
                    Aktifkan Akun
                </a>
                <button @click="show = false" type="button" class="text-red-500 hover:text-red-700 hover:bg-red-100 p-1 rounded-md transition" aria-label="Dismiss">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>
@endif