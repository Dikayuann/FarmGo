<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmGo Landing Page</title>
    @vite('resources/css/app.css')
    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Custom font adjustment to match the clean look */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Custom gradient animations */
        @keyframes gradient-shift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .gradient-animate {
            background-size: 200% 200%;
            animation: gradient-shift 8s ease infinite;
        }

        /* Floating animation */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        /* Glow effect */
        .glow {
            box-shadow: 0 0 20px rgba(132, 204, 22, 0.3);
        }
    </style>
</head>

<body class="bg-white">

    <nav
        class="bg-white/80 backdrop-blur-lg p-4 px-6 md:px-12 flex justify-between items-center w-full fixed top-0 z-50 border-b border-lime-100/50 shadow-sm">

        <div class="flex items-center gap-2">
            <img src="{{ asset('image/FarmGo.png') }}" alt="FarmGo Logo" class="max-w-12 max-h-12 drop-shadow-md">
        </div>

        <div class="hidden md:flex space-x-8 text-slate-600 font-medium text-sm">
            <a href="#" class="hover:text-lime-600 transition duration-300 relative group">
                Beranda
                <span
                    class="absolute bottom-0 left-0 w-0 h-0.5 bg-lime-600 group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="#tentang" class="hover:text-lime-600 transition duration-300 relative group">
                Tentang
                <span
                    class="absolute bottom-0 left-0 w-0 h-0.5 bg-lime-600 group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="#layanan" class="hover:text-lime-600 transition duration-300 relative group">
                Layanan
                <span
                    class="absolute bottom-0 left-0 w-0 h-0.5 bg-lime-600 group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="#kontak" class="hover:text-lime-600 transition duration-300 relative group">
                Kontak
                <span
                    class="absolute bottom-0 left-0 w-0 h-0.5 bg-lime-600 group-hover:w-full transition-all duration-300"></span>
            </a>
        </div>

        <button
            class="hidden md:block bg-gradient-to-r from-lime-400 to-green-500 hover:from-lime-500 hover:to-green-600 text-white font-semibold py-2.5 px-7 rounded-full shadow-lg shadow-lime-500/30 transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lime-500/40"
            onclick="window.location.href='{{ route('login') }}'">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Coba Sekarang
            </span>
        </button>

        <div class="md:hidden flex items-center">
            <button id="mobile-menu-btn"
                class="text-slate-700 hover:text-lime-600 focus:outline-none p-2 rounded-lg hover:bg-lime-50 transition">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

    </nav>

    <div id="mobile-menu"
        class="hidden md:hidden fixed top-16 left-0 w-full bg-white/95 backdrop-blur-lg shadow-xl z-40 p-6 border-b border-lime-100">
        <a href="#" class="block py-3 text-slate-700 hover:text-lime-600 transition font-medium">Beranda</a>
        <a href="#" class="block py-3 text-slate-700 hover:text-lime-600 transition font-medium">Tentang</a>
        <a href="#" class="block py-3 text-slate-700 hover:text-lime-600 transition font-medium">Layanan</a>
        <a href="#" class="block py-3 text-slate-700 hover:text-lime-600 transition font-medium">Kontak</a>
        <a href="{{ route('login') }}"
            class="block mt-4 bg-gradient-to-r from-lime-400 to-green-500 text-center text-white py-3 rounded-full font-semibold shadow-lg">
            Coba Gratis
        </a>
    </div>

    <section
        class="relative pt-28 pb-20 px-6 md:px-12 min-h-screen flex items-center bg-gradient-to-br from-[#dcfce7] via-[#ecfccb] to-[#f0fdf4] overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-20 right-10 w-72 h-72 bg-lime-200/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-10 w-96 h-96 bg-green-200/20 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center relative z-10">

            <!-- Text Content Column -->
            <div class="space-y-8" data-aos="fade-down" data-aos-duration="1000">
                <div class="inline-block">
                    <span
                        class="bg-gradient-to-r from-lime-500 to-green-600 text-white text-sm font-semibold px-4 py-2 rounded-full shadow-lg">
                        Platform Manajemen Ternak
                    </span>
                </div>
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 leading-tight">
                    Manajemen Peternakan <br>
                    <span
                        class="bg-gradient-to-r from-lime-500 via-green-600 to-emerald-600 bg-clip-text text-transparent">yang
                        Lebih Cerdas</span>
                </h1>
                <p class="text-slate-600 text-xl leading-relaxed max-w-lg">
                    Kelola operasional ternak harian dengan sistem all-in-one. Kontrol mudah, visibilitas total – Kapan
                    Saja, Di Mana Saja!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button onclick="window.location.href='{{ route('login') }}'"
                        class="group bg-gradient-to-r from-lime-500 to-green-500 hover:from-lime-600 hover:to-green-600 text-white font-bold py-4 px-8 rounded-full shadow-xl shadow-lime-500/30 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                        <span class="flex items-center justify-center gap-2">
                            Mulai Gratis Sekarang
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>

            <!-- Image Column -->
            <div class="relative flex justify-center" data-aos="fade-down" data-aos-duration="1000"
                data-aos-delay="200">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-lime-400/30 to-green-400/30 rounded-full blur-3xl animate-pulse">
                </div>
                <img src="{{ asset('image/Pic.png') }}" alt="3D Illustration of Cow and Goat"
                    class="relative w-full max-w-lg drop-shadow-2xl object-contain float-animation">
            </div>

        </div>
    </section>

    <section id="tentang" class="py-24 px-6 md:px-12 bg-white">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center">

            <div class="space-y-6">
                <div class="bg-gradient-to-br from-lime-500 to-green-600 p-0.5 rounded-3xl shadow-2xl shadow-lime-500/20"
                    data-aos="fade-down" data-aos-delay="100">
                    <div class="bg-white p-8 rounded-3xl">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-lime-500 to-green-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-lime-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">Misi Kami</h3>
                        <p class="text-slate-600 leading-relaxed">Memberikan solusi teknologi peternakan modern untuk
                            meningkatkan produktivitas dan kesejahteraan peternak lokal.</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-0.5 rounded-3xl shadow-2xl shadow-green-500/20"
                    data-aos="fade-down" data-aos-delay="200">
                    <div class="bg-white p-8 rounded-3xl">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-green-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">Visi Kami</h3>
                        <p class="text-slate-600 leading-relaxed">Menjadi platform manajemen ternak nomor satu yang
                            terintegrasi dan mudah digunakan di seluruh dunia.</p>
                    </div>
                </div>
            </div>

            <div class="relative h-full min-h-[500px] rounded-3xl overflow-hidden shadow-2xl" data-aos="fade-down"
                data-aos-delay="300">
                <div class="absolute inset-0 bg-gradient-to-t from-green-900/40 via-transparent to-transparent z-10">
                </div>
                <img src="https://images.unsplash.com/photo-1596733430284-f7437764b1a9?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                    alt="Cows in field"
                    class="object-cover w-full h-full transform hover:scale-105 transition duration-700">
            </div>
        </div>
    </section>

    <section class="py-24 px-6 md:px-12 bg-gradient-to-br from-slate-50 to-lime-50/30">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center">
            <div class="relative" data-aos="fade-down" data-aos-delay="100">
                <div
                    class="absolute -inset-4 bg-gradient-to-r from-lime-500 to-green-500 rounded-3xl opacity-20 blur-2xl">
                </div>
                <img src="https://images.unsplash.com/photo-1545468800-85cc9bc6ecf7?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                    alt="Cow closeup"
                    class="relative rounded-3xl shadow-2xl w-full h-auto object-cover transform hover:scale-[1.02] transition duration-500">
            </div>

            <div class="space-y-8" data-aos="fade-down" data-aos-delay="200">
                <div>
                    <span class="text-lime-600 font-semibold text-sm uppercase tracking-wider">Keunggulan Kami</span>
                    <h2 class="text-4xl font-bold text-slate-900 mt-3 mb-4">Mengapa Memilih FarmGo?</h2>
                    <p class="text-slate-600 text-lg leading-relaxed">Kelola peternakan lebih efisien dan akurat dengan
                        sistem data
                        terintegrasi yang dirancang khusus untuk peternak Indonesia.</p>
                </div>

                <ul class="space-y-5">
                    <li class="flex items-start gap-4 group">
                        <span
                            class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-lime-500 to-green-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-lime-500/30 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </span>
                        <div>
                            <h4 class="font-bold text-lg text-slate-900 mb-1">Akses Data Akurat</h4>
                            <p class="text-slate-600">Pantau semua informasi ternak secara real-time dengan akurasi
                                tinggi</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4 group">
                        <span
                            class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </span>
                        <div>
                            <h4 class="font-bold text-lg text-slate-900 mb-1">Pemantauan Kesehatan</h4>
                            <p class="text-slate-600">Sistem monitoring otomatis untuk kesehatan dan vaksinasi ternak
                            </p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4 group">
                        <span
                            class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </span>
                        <div>
                            <h4 class="font-bold text-lg text-slate-900 mb-1">Efisiensi Waktu & Biaya</h4>
                            <p class="text-slate-600">Hemat waktu dan biaya operasional hingga 40% dengan otomasi</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>



    <section id="layanan" class="py-16 px-6 md:px-12 bg-white">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-2" data-aos="fade-down">Semua Kebutuhan Manajemen Peternakan
            </h2>
            <p class="text-gray-500 mb-10" data-aos="fade-down" data-aos-delay="100">Solusi lengkap untuk
                mempermudah segala urusan peternakan Anda.</p>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="md:col-span-2 grid sm:grid-cols-2 gap-6">
                    <div class="bg-green-50 p-6 rounded-2xl hover:bg-green-100 transition duration-300"
                        data-aos="fade-down" data-aos-delay="150">
                        <div class="text-green-600 mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Manajemen Data Ternak</h3>
                        <p class="text-sm text-gray-600">Simpan data kelahiran, bobot, dan riwayat medis secara digital.
                        </p>
                    </div>
                    <div class="bg-white border border-gray-100 shadow-sm p-6 rounded-2xl hover:shadow-md transition duration-300"
                        data-aos="fade-down" data-aos-delay="200">
                        <div class="text-green-600 mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Monitoring Kesehatan</h3>
                        <p class="text-sm text-gray-600">Lacak jadwal vaksinasi dan kondisi kesehatan hewan.</p>
                    </div>
                    <div class="bg-white border border-gray-100 shadow-sm p-6 rounded-2xl hover:shadow-md transition duration-300"
                        data-aos="fade-down" data-aos-delay="250">
                        <div class="text-green-600 mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Notifikasi & Alert</h3>
                        <p class="text-sm text-gray-600">Dapatkan pengingat otomatis untuk jadwal penting.</p>
                    </div>
                    <div class="bg-white border border-gray-100 shadow-sm p-6 rounded-2xl hover:shadow-md transition duration-300"
                        data-aos="fade-down" data-aos-delay="300">
                        <div class="text-green-600 mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Catat Reproduksi</h3>
                        <p class="text-sm text-gray-600">Pantau perkawinan hewan ternak.</p>
                    </div>
                </div>

                <div class="h-full" data-aos="fade-down" data-aos-delay="350">
                    <img src="https://images.unsplash.com/photo-1500595046743-cd271d694d30?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                        alt="Farm landscape" class="rounded-2xl h-full object-cover shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 px-6 md:px-12 bg-gray-50">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-blue-900 mb-10" data-aos="fade-down">Pilih Paket Berlangganan</h2>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <!-- Trial Plan -->
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition"
                    data-aos="fade-down" data-aos-delay="100">
                    <span
                        class="bg-blue-100 text-blue-600 text-xs font-bold px-3 py-1 rounded-full uppercase">Trial</span>
                    <h3 class="text-4xl font-bold text-gray-900 mt-4 mb-2">Gratis<span
                            class="text-lg font-normal text-gray-500">/7 hari</span></h3>
                    <p class="text-gray-500 text-sm mb-6">Sempurna untuk peternakan kecil</p>
                    <ul class="text-left space-y-3 mb-8 text-sm text-gray-600">
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Hingga 10 Ternak</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Catat Kesehatan Dasar
                        </li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Pembaruan Manual</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Akses Web</li>
                    </ul>
                    <a href="{{ route('login') }}"
                        class="block w-full py-3 border-2 border-blue-500 text-blue-500 rounded-lg font-semibold hover:bg-blue-50 transition">Coba
                        Gratis →</a>
                </div>

                <!-- Premium Plan -->
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-8 rounded-2xl shadow-2xl relative transform md:scale-105 z-10"
                    data-aos="fade-down" data-aos-delay="200">
                    <span
                        class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full uppercase backdrop-blur-sm">Paling
                        Populer</span>
                    <h3 class="text-4xl font-bold text-white mt-4 mb-2">Rp 50.000<span
                            class="text-lg font-normal text-white/80">/bulan</span></h3>
                    <p class="text-white/90 text-sm mb-6">Terbaik untuk peternakan berkembang</p>
                    <ul class="text-left space-y-3 mb-8 text-sm text-white/90">
                        <li class="flex items-center gap-2"><span class="text-blue-200">✓</span> Ternak Tidak Terbatas
                        </li>
                        <li class="flex items-center gap-2"><span class="text-blue-200">✓</span> Riwayat Kesehatan
                            Lengkap</li>
                        <li class="flex items-center gap-2"><span class="text-blue-200">✓</span> Notifikasi Reproduksi
                        </li>
                        <li class="flex items-center gap-2"><span class="text-blue-200">✓</span> Analisis & Laporan</li>
                        <li class="flex items-center gap-2"><span class="text-blue-200">✓</span> Dukungan Prioritas</li>
                    </ul>
                    <a href="{{ route('login') }}"
                        class="block w-full py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition shadow-lg">Berlangganan
                        Sekarang →</a>
                </div>
            </div>
        </div>
    </section>


    {{-- Gallery Section (commented out) --}}
    {{-- <section class="py-16 px-6 md:px-12 bg-white">--}}
        {{-- ... --}}
        {{-- </section> --}}

    <!-- Testimonials Section -->
    <section class="py-20 px-6 md:px-12 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12" data-aos="fade-down" data-aos-duration="800">
                <h2 class="text-4xl md:text-5xl font-bold text-slate-800 mb-2">Cerita dari Para Peternak</h2>
                <h3
                    class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    FarmGo</h3>
                <p class="mt-4 text-slate-500 max-w-2xl mx-auto">Peternak dan pengelola peternakan telah merasakan
                    kemudahan dan efisiensi setelah
                    menggunakan sistem ini. Berikut pendapat mereka tentang pengalaman menggunakan FarmGo.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">

                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-8 rounded-3xl relative shadow-2xl shadow-emerald-500/20"
                    data-aos="fade-down" data-aos-delay="100">
                    <div class="absolute top-6 right-6 text-5xl opacity-20">"</div>
                    <p class="text-sm leading-relaxed mb-6 relative z-10">"Sejak menggunakan FarmGo, pencatatan data
                        ternak jadi jauh
                        lebih mudah dan rapi. Saya bisa memantau kesehatan dan perkembangan ternak kapan saja tanpa
                        harus mencatat manual."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-full overflow-hidden backdrop-blur-sm">
                            <img src="https://i.pravatar.cc/150?u=a042581f4e29026024d" alt="Eko"
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-lg">Eko Susiloanto</h4>
                            <p class="text-xs opacity-80">Peternak Sapi Perah</p>
                        </div>
                        <div class="ml-auto text-yellow-300 text-sm">★★★★★</div>
                    </div>
                </div>

                <div class="bg-white text-slate-700 p-8 rounded-3xl shadow-xl border border-slate-100"
                    data-aos="fade-down" data-aos-delay="200">
                    <div class="absolute top-6 right-6 text-5xl text-slate-100">"</div>
                    <p class="text-sm leading-relaxed mb-6">"Fitur pengingat vaksin dan reproduksi sangat membantu.
                        Tidak ada lagi jadwal yang terlewat, semuanya tercatat otomatis di sistem."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full overflow-hidden">
                            <img src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="Tri"
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-lg">Tri Cahyono</h4>
                            <p class="text-xs text-slate-500">Peternak Kambing</p>
                        </div>
                        <div class="ml-auto text-amber-400 text-sm">★★★★★</div>
                    </div>
                </div>

                <div class="bg-white text-slate-700 p-8 rounded-3xl shadow-xl border border-slate-100"
                    data-aos="fade-down" data-aos-delay="300">
                    <p class="text-sm leading-relaxed mb-6">"Sistemnya mudah digunakan bahkan oleh peternak yang belum
                        terbiasa dengan teknologi. Tampilan simpel tapi fungsinya lengkap."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-teal-100 rounded-full overflow-hidden">
                            <img src="https://i.pravatar.cc/150?u=a048581f4e29026704d" alt="Tjandra"
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-lg">Tjandra Mangkualam</h4>
                            <p class="text-xs text-slate-500">Peternak Kambing</p>
                        </div>
                        <div class="ml-auto text-amber-400 text-sm">★★★★★</div>
                    </div>
                </div>

                <div class="bg-white text-slate-700 p-8 rounded-3xl shadow-xl border border-slate-100"
                    data-aos="fade-down" data-aos-delay="400">
                    <p class="text-sm leading-relaxed mb-6">"FarmGo membantu saya mengelola peternakan dengan lebih
                        profesional. Semua data ternak tersimpan aman dan bisa diakses kapan saja."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-cyan-100 rounded-full overflow-hidden">
                            <img src="https://i.pravatar.cc/150?u=a04258114e29026704d" alt="Mukidi"
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-lg">Cak Mukidi</h4>
                            <p class="text-xs text-slate-500">Peternak Sapi Potong</p>
                        </div>
                        <div class="ml-auto text-amber-400 text-sm">★★★★★</div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-20 px-6 md:px-12 bg-slate-50">
        <div class="max-w-7xl mx-auto bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl overflow-hidden shadow-2xl shadow-emerald-500/20 grid md:grid-cols-2"
            data-aos="fade-down" data-aos-duration="1000">
            <div class="hidden md:block relative">
                <img src="https://img.freepik.com/free-photo/funny-curious-cow-looking-camera-while-other-cows-eating-hay-background-cattle-farm_342744-895.jpg?semt=ais_hybrid&w=740&q=80"
                    alt="Cows in barn" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-900/40 to-transparent"></div>
            </div>

            <div class="p-8 md:p-12 text-white">
                <h2 class="text-3xl font-bold mb-2">Hubungi <span class="text-emerald-200">Kami</span></h2>
                <p class="mb-8 text-emerald-100/80">Kami siap membantu kebutuhan manajemen peternakan Anda.</p>

                <form class="space-y-4">
                    <input type="text" placeholder="Full Name"
                        class="w-full px-5 py-3.5 rounded-xl bg-white/10 border border-white/20 placeholder-white/60 text-white focus:outline-none focus:bg-white/20 focus:border-white/40 transition backdrop-blur-sm">
                    <input type="email" placeholder="Email Address"
                        class="w-full px-5 py-3.5 rounded-xl bg-white/10 border border-white/20 placeholder-white/60 text-white focus:outline-none focus:bg-white/20 focus:border-white/40 transition backdrop-blur-sm">
                    <textarea rows="3" placeholder="Message"
                        class="w-full px-5 py-3.5 rounded-xl bg-white/10 border border-white/20 placeholder-white/60 text-white focus:outline-none focus:bg-white/20 focus:border-white/40 transition backdrop-blur-sm resize-none"></textarea>

                    <button type="submit"
                        class="w-full bg-white text-emerald-600 font-bold py-3.5 rounded-xl hover:bg-emerald-50 transition shadow-lg transform hover:scale-[1.02] active:scale-[0.98]">
                        Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-800 text-white py-16 px-6 border-t border-slate-700">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10">

            <!-- Brand -->
            <div>
                <span
                    class="text-2xl font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">FarmGo</span>
                <p class="mt-4 text-slate-400 text-sm leading-relaxed">
                    Platform digital untuk membantu peternak mengelola ternak, pakan, dan produksi secara modern.
                </p>
            </div>

            <!-- Produk -->
            <div>
                <h4 class="font-bold mb-5 text-white">Produk</h4>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li><a href="#" class="hover:text-emerald-400 transition">Manajemen Ternak</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition">Manajemen Reproduksi</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition">ini Kesehatan</a></li>
                </ul>
            </div>

            <!-- Perusahaan -->
            <div>
                <h4 class="font-bold mb-5 text-white">Perusahaan</h4>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li><a href="#" class="hover:text-emerald-400 transition">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition">Tim</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition">Kontak</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition">Kebijakan Privasi</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="font-bold mb-5 text-white">Info Peternakan</h4>
                <p class="text-slate-400 text-sm mb-4 leading-relaxed">
                    Dapatkan tips peternakan & update fitur terbaru FarmGo.
                </p>
                <div class="flex gap-2">
                    <input type="email" placeholder="Email Anda"
                        class="flex-1 px-4 py-3 rounded-xl text-white bg-slate-700/50 border border-slate-600 focus:outline-none focus:border-emerald-500 transition text-sm">
                    <button
                        class="bg-gradient-to-r from-emerald-500 to-teal-500 px-5 py-3 rounded-xl hover:from-emerald-600 hover:to-teal-600 transition font-semibold text-sm shadow-lg">
                        Kirim
                    </button>
                </div>
            </div>
        </div>

        <hr class="my-10 border-slate-700" />

        <!-- Bottom -->
        <div class="max-w-7xl mx-auto sm:flex sm:items-center sm:justify-between">
            <span class="text-sm text-slate-400">
                © 2025 FarmGo. Solusi Digital Peternakan Indonesia.
            </span>

            <!-- Sosial Media -->
            <div class="flex mt-4 sm:mt-0 gap-4">
                <a href="#"
                    class="text-slate-400 hover:text-emerald-400 transition p-2 rounded-lg hover:bg-slate-700/50">
                    <!-- Facebook -->
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" />
                    </svg>
                </a>

                <a href="#"
                    class="text-slate-400 hover:text-emerald-400 transition p-2 rounded-lg hover:bg-slate-700/50">
                    <!-- Instagram -->
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm10 2c1.654 0 3 1.346 3 3v10c0 1.654-1.346 3-3 3H7c-1.654 0-3-1.346-3-3V7c0-1.654 1.346-3 3-3h10zm-5 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm4.5-.75a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5z" />
                    </svg>
                </a>

                <a href="#"
                    class="text-slate-400 hover:text-emerald-400 transition p-2 rounded-lg hover:bg-slate-700/50">
                    <!-- WhatsApp -->
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 0 0-8.94 14.5L2 22l5.65-1.48A10 10 0 1 0 12 2z" />
                    </svg>
                </a>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', (event) => {
            event.preventDefault();
            menu.classList.toggle('hidden');
        });
    </script>

    <!-- AOS Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });
    </script>

</body>

</html>