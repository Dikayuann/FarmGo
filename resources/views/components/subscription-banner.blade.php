@php
    $user = Auth::user();
    $daysUntil = $user->getDaysUntilExpiry();
@endphp

{{-- Trial Ending Soon (within 7 days before expiry) --}}
@if($user->isTrialEndingSoon())
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-yellow-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-yellow-800">
                        {{ $user->isOnTrial() ? 'Trial' : 'Langganan' }} Anda akan berakhir dalam <strong>{{ $daysUntil }}
                            hari</strong>
                    </p>
                    <p class="text-xs text-yellow-700 mt-0.5">
                        Perpanjang sekarang untuk terus menikmati seluruh fitur FarmGo tanpa gangguan.
                    </p>
                </div>
            </div>
            <a href="{{ route('langganan.index') }}"
                class="px-4 py-2 bg-yellow-600 text-white text-sm font-semibold rounded-lg hover:bg-yellow-700 transition whitespace-nowrap">
                Perpanjang
            </a>
        </div>
    </div>
@endif

{{-- Grace Period (0-3 days after expiry) --}}
@if($user->isInGracePeriod())
    @php
        $graceDaysLeft = 3 - $user->getDaysSinceExpiry();
    @endphp
    <div class="bg-orange-50 border-l-4 border-orange-500 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-orange-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-orange-800">
                        Langganan Berakhir! Akses penuh tersisa <strong>{{ $graceDaysLeft }} hari</strong>
                    </p>
                    <p class="text-xs text-orange-700 mt-0.5">
                        Setelah itu, akun akan masuk mode baca saja. Perpanjang sekarang untuk menghindari gangguan.
                    </p>
                </div>
            </div>
            <a href="{{ route('langganan.index') }}"
                class="px-4 py-2 bg-orange-600 text-white text-sm font-semibold rounded-lg hover:bg-orange-700 transition whitespace-nowrap">
                Perpanjang Sekarang
            </a>
        </div>
    </div>
@endif

{{-- Read-Only Mode (4-30 days after expiry) --}}
@if($user->isReadOnlyMode())
    @php
        $daysSinceExpiry = $user->getDaysSinceExpiry();
        $daysUntilLock = 30 - $daysSinceExpiry;
    @endphp
    <div class="bg-red-50 border-l-4 border-red-500 p-4 shadow-md sticky top-20 z-40">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                        clip-rule="evenodd" />
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-bold text-red-800">
                        <i class="fas fa-lock mr-1"></i> Mode Baca Saja - Langganan Berakhir
                    </p>
                    <p class="text-xs text-red-700 mt-0.5">
                        Anda hanya bisa melihat data. Dalam {{ $daysUntilLock }} hari, akses akan diblokir sepenuhnya.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-red-600 font-bold bg-red-100 px-3 py-1 rounded-full">
                    {{ $daysSinceExpiry }}/30 hari
                </span>
                <a href="{{ route('langganan.index') }}"
                    class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 transition whitespace-nowrap shadow-lg">
                    <i class="fas fa-unlock mr-1"></i> Aktifkan Akun
                </a>
            </div>
        </div>
    </div>
@endif