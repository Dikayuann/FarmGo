<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Tags -->
    <title>FarmGo - Smart Farm Management System | Kelola Ternak dengan Mudah</title>
    <meta name="description"
        content="Kelola operasional ternak harian dengan sistem all-in-one. Kontrol mudah, visibilitas total – Kapan Saja, Di Mana Saja! Mulai Gratis Sekarang.">
    <meta name="keywords"
        content="farm management, manajemen ternak, sistem peternakan, farm software, livestock management, FarmGo">
    <meta name="author" content="FarmGo">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="FarmGo - Smart Farm Management System">
    <meta property="og:description"
        content="Kelola operasional ternak harian dengan sistem all-in-one. Kontrol mudah, visibilitas total – Kapan Saja, Di Mana Saja!">
    <meta property="og:image" content="{{ asset('image/FarmGo.png') }}">
    <meta property="og:site_name" content="FarmGo">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="FarmGo - Smart Farm Management System">
    <meta name="twitter:description"
        content="Kelola operasional ternak harian dengan sistem all-in-one. Kontrol mudah, visibilitas total – Kapan Saja, Di Mana Saja!">
    <meta name="twitter:image" content="{{ asset('image/FarmGo.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('image/FarmGo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('image/FarmGo.png') }}">

    <!-- Preconnect for third-party resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com">
    <link rel="preconnect" href="https://unpkg.com">

    @vite('resources/css/app.css')

    <!-- Google Fonts - non-blocking -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
    </noscript>

    <!-- AOS Library - deferred -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    </noscript>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        html {
            overflow-x: hidden;
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

        /* Card hover lift */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
        }

        /* Gradient border reveal */
        .gradient-border {
            position: relative;
            background: white;
            border-radius: 1rem;
            overflow: hidden;
        }
        .gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 1rem;
            padding: 2px;
            background: linear-gradient(135deg, #84cc16, #10b981, #14b8a6);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .gradient-border:hover::before {
            opacity: 1;
        }

        /* Navbar scroll effect */
        .nav-scrolled {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08) !important;
            border-bottom-color: transparent !important;
        }

        /* Back to top button */
        .back-to-top {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        /* Animated counter */
        .stat-number {
            font-variant-numeric: tabular-nums;
        }

        /* Subtle parallax for decorative blobs */
        .blob {
            transition: transform 0.1s ease-out;
        }

        /* Pricing card shimmer */
        .pricing-premium {
            position: relative;
            overflow: hidden;
        }
        .pricing-premium::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.05) 50%, transparent 60%);
            animation: shimmer 4s ease-in-out infinite;
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        /* Smooth section divider */
        .section-wave {
            position: relative;
        }
        .section-wave::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 60px;
            background: white;
            clip-path: ellipse(55% 100% at 50% 100%);
        }
    </style>
</head>

<body class="bg-white">

    <nav id="main-nav"
        class="bg-white/80 backdrop-blur-lg p-4 px-6 md:px-12 flex justify-between items-center w-full fixed top-0 z-50 border-b border-lime-100/50 shadow-sm transition-all duration-300">

        <div class="flex items-center gap-3">
            <img src="{{ asset('image/FarmGo.png') }}" alt="FarmGo Logo" class="h-10 w-auto drop-shadow-md" width="64"
                height="64" fetchpriority="high">
        </div>

        <div class="hidden md:flex space-x-8 text-slate-700 font-medium text-sm">
            <a href="#" class="hover:text-lime-600 transition duration-300 relative group">
                <span data-i18n="nav_home">Beranda</span>
                <span
                    class="absolute bottom-0 left-0 w-0 h-0.5 bg-lime-600 group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="#tentang" class="hover:text-lime-600 transition duration-300 relative group">
                <span data-i18n="nav_about">Tentang</span>
                <span
                    class="absolute bottom-0 left-0 w-0 h-0.5 bg-lime-600 group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="#layanan" class="hover:text-lime-600 transition duration-300 relative group">
                <span data-i18n="nav_services">Layanan</span>
                <span
                    class="absolute bottom-0 left-0 w-0 h-0.5 bg-lime-600 group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="#kontak" class="hover:text-lime-600 transition duration-300 relative group">
                <span data-i18n="nav_contact">Kontak</span>
                <span
                    class="absolute bottom-0 left-0 w-0 h-0.5 bg-lime-600 group-hover:w-full transition-all duration-300"></span>
            </a>
        </div>

        <div class="hidden md:flex items-center gap-3">
            <!-- Language Toggle -->
            <div id="lang-toggle" class="flex items-center bg-slate-100 rounded-full p-0.5 cursor-pointer select-none" title="Switch Language">
                <button id="lang-id-btn" class="px-3 py-1.5 rounded-full text-xs font-bold transition-all duration-300 bg-lime-500 text-white shadow-sm">ID</button>
                <button id="lang-en-btn" class="px-3 py-1.5 rounded-full text-xs font-bold transition-all duration-300 text-slate-500 hover:text-slate-700">EN</button>
            </div>

            <button
                class="bg-gradient-to-r from-lime-400 to-green-500 hover:from-lime-500 hover:to-green-600 text-white font-semibold py-2.5 px-7 rounded-full shadow-lg shadow-lime-500/30 transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lime-500/40"
                onclick="window.location.href='{{ route('login') }}'">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span data-i18n="nav_cta">Coba Sekarang</span>
                </span>
            </button>
        </div>

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
        <a href="#" class="block py-3 text-slate-700 hover:text-lime-600 transition font-medium" data-i18n="nav_home">Beranda</a>
        <a href="#tentang" class="block py-3 text-slate-700 hover:text-lime-600 transition font-medium" data-i18n="nav_about">Tentang</a>
        <a href="#layanan" class="block py-3 text-slate-700 hover:text-lime-600 transition font-medium" data-i18n="nav_services">Layanan</a>
        <a href="#kontak" class="block py-3 text-slate-700 hover:text-lime-600 transition font-medium" data-i18n="nav_contact">Kontak</a>
        <!-- Mobile Language Toggle -->
        <div class="flex items-center gap-2 py-3 border-t border-slate-200 mt-3">
            <span class="text-slate-500 text-sm font-medium">Bahasa:</span>
            <div id="lang-toggle-mobile" class="flex items-center bg-slate-100 rounded-full p-0.5 cursor-pointer select-none">
                <button id="lang-id-btn-mobile" class="px-3 py-1.5 rounded-full text-xs font-bold transition-all duration-300 bg-lime-500 text-white shadow-sm">ID</button>
                <button id="lang-en-btn-mobile" class="px-3 py-1.5 rounded-full text-xs font-bold transition-all duration-300 text-slate-500">EN</button>
            </div>
        </div>
        <a href="{{ route('login') }}"
            class="block mt-4 bg-gradient-to-r from-lime-400 to-green-500 text-center text-white py-3 rounded-full font-semibold shadow-lg" data-i18n="nav_cta_mobile">
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
                        class="bg-gradient-to-r from-lime-500 to-green-600 text-white text-sm font-semibold px-4 py-2 rounded-full shadow-lg" data-i18n="hero_badge">
                        Platform Manajemen Ternak
                    </span>
                </div>
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 leading-tight">
                    <span data-i18n="hero_title_1">Manajemen Peternakan</span> <br>
                    <span
                        class="bg-gradient-to-r from-lime-500 via-green-600 to-emerald-600 bg-clip-text text-transparent" data-i18n="hero_title_2">yang Lebih Cerdas</span>
                </h1>
                <p class="text-slate-700 text-xl leading-relaxed max-w-lg" data-i18n="hero_desc">
                    Kelola operasional ternak harian dengan sistem all-in-one. Kontrol mudah, visibilitas total – Kapan
                    Saja, Di Mana Saja!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button onclick="window.location.href='{{ route('login') }}'"
                        class="group bg-gradient-to-r from-lime-500 to-green-500 hover:from-lime-600 hover:to-green-600 text-white font-bold py-4 px-8 rounded-full shadow-xl shadow-lime-500/30 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                        <span class="flex items-center justify-center gap-2">
                            <span data-i18n="hero_cta">Mulai Gratis Sekarang</span>
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
                    class="relative w-full max-w-lg drop-shadow-2xl object-contain float-animation" width="512"
                    height="512" loading="eager">
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
                        <h3 class="text-2xl font-bold text-slate-900 mb-3" data-i18n="about_mission_title">Misi Kami</h3>
                        <p class="text-slate-700 leading-relaxed" data-i18n="about_mission_desc">Memberikan solusi teknologi peternakan modern untuk
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
                        <h3 class="text-2xl font-bold text-slate-900 mb-3" data-i18n="about_vision_title">Visi Kami</h3>
                        <p class="text-slate-700 leading-relaxed" data-i18n="about_vision_desc">Menjadi platform manajemen ternak nomor satu yang
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
                    class="object-cover w-full h-full transform hover:scale-105 transition duration-700" width="800"
                    height="1200" loading="lazy">
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
                    class="relative rounded-3xl shadow-2xl w-full h-auto object-cover transform hover:scale-[1.02] transition duration-500"
                    width="800" height="1120" loading="lazy">
            </div>

            <div class="space-y-8" data-aos="fade-down" data-aos-delay="200">
                <div>
                    <span class="text-lime-700 font-semibold text-sm uppercase tracking-wider" data-i18n="why_badge">Keunggulan Kami</span>
                    <h2 class="text-4xl font-bold text-slate-900 mt-3 mb-4" data-i18n="why_title">Mengapa Memilih FarmGo?</h2>
                    <p class="text-slate-700 text-lg leading-relaxed" data-i18n="why_desc">Kelola peternakan lebih efisien dan akurat dengan
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
                            <h4 class="font-bold text-lg text-slate-900 mb-1" data-i18n="why_1_title">Akses Data Akurat</h4>
                            <p class="text-slate-700" data-i18n="why_1_desc">Pantau semua informasi ternak secara real-time dengan akurasi
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
                            <h4 class="font-bold text-lg text-slate-900 mb-1" data-i18n="why_2_title">Pemantauan Kesehatan</h4>
                            <p class="text-slate-700" data-i18n="why_2_desc">Sistem monitoring otomatis untuk kesehatan dan vaksinasi ternak
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
                            <h4 class="font-bold text-lg text-slate-900 mb-1" data-i18n="why_3_title">Efisiensi Waktu & Biaya</h4>
                            <p class="text-slate-700" data-i18n="why_3_desc">Hemat waktu dan biaya operasional hingga 40% dengan otomasi</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>



    <section id="layanan" class="py-16 px-6 md:px-12 bg-white">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-2" data-aos="fade-down" data-i18n="services_title">Semua Kebutuhan Manajemen Peternakan
            </h2>
            <p class="text-gray-600 mb-10" data-aos="fade-down" data-aos-delay="100" data-i18n="services_desc">Solusi lengkap untuk
                mempermudah segala urusan peternakan Anda.</p>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="md:col-span-2 grid sm:grid-cols-2 gap-6">
                    <div class="bg-green-50 p-6 rounded-2xl hover:bg-green-100 transition duration-300 card-hover"
                        data-aos="fade-down" data-aos-delay="150">
                        <div class="text-green-600 mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2" data-i18n="services_1_title">Manajemen Data Ternak</h3>
                        <p class="text-sm text-gray-600" data-i18n="services_1_desc">Simpan data kelahiran, bobot, dan riwayat medis secara digital.
                        </p>
                    </div>
                    <div class="bg-white border border-gray-100 shadow-sm p-6 rounded-2xl hover:shadow-md transition duration-300 card-hover gradient-border"
                        data-aos="fade-down" data-aos-delay="200">
                        <div class="text-green-600 mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2" data-i18n="services_2_title">Monitoring Kesehatan</h3>
                        <p class="text-sm text-gray-600" data-i18n="services_2_desc">Lacak jadwal vaksinasi dan kondisi kesehatan hewan.</p>
                    </div>
                    <div class="bg-white border border-gray-100 shadow-sm p-6 rounded-2xl hover:shadow-md transition duration-300 card-hover gradient-border"
                        data-aos="fade-down" data-aos-delay="250">
                        <div class="text-green-600 mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2" data-i18n="services_3_title">Notifikasi & Alert</h3>
                        <p class="text-sm text-gray-600" data-i18n="services_3_desc">Dapatkan pengingat otomatis untuk jadwal penting.</p>
                    </div>
                    <div class="bg-white border border-gray-100 shadow-sm p-6 rounded-2xl hover:shadow-md transition duration-300 card-hover gradient-border"
                        data-aos="fade-down" data-aos-delay="300">
                        <div class="text-green-600 mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2" data-i18n="services_4_title">Catat Reproduksi</h3>
                        <p class="text-sm text-gray-600" data-i18n="services_4_desc">Pantau perkawinan hewan ternak.</p>
                    </div>
                </div>

                <div class="h-full" data-aos="fade-down" data-aos-delay="350">
                    <img src="https://images.unsplash.com/photo-1500595046743-cd271d694d30?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                        alt="Farm landscape" class="rounded-2xl h-full object-cover shadow-lg" width="800" height="531"
                        loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 px-6 md:px-12 bg-gray-50">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-blue-900 mb-10" data-aos="fade-down" data-i18n="pricing_title">Pilih Paket Berlangganan</h2>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <!-- Trial Plan -->
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition card-hover"
                    data-aos="fade-down" data-aos-delay="100">
                    <span
                        class="bg-blue-100 text-blue-600 text-xs font-bold px-3 py-1 rounded-full uppercase" data-i18n="pricing_trial_badge">Trial</span>
                    <h3 class="text-4xl font-bold text-gray-900 mt-4 mb-2"><span data-i18n="pricing_trial_price">Gratis</span><span
                            class="text-lg font-normal text-gray-600" data-i18n="pricing_trial_period">/7 hari</span></h3>
                    <p class="text-gray-600 text-sm mb-6" data-i18n="pricing_trial_desc">Sempurna untuk peternakan kecil</p>
                    <ul class="text-left space-y-3 mb-8 text-sm text-gray-600">
                        <li class="flex items-center gap-2"><span class="text-blue-600">✓</span> <span data-i18n="pricing_trial_1">Hingga 10 Ternak</span></li>
                        <li class="flex items-center gap-2"><span class="text-blue-600">✓</span> <span data-i18n="pricing_trial_2">Catat Kesehatan Dasar</span>
                        </li>
                        <li class="flex items-center gap-2"><span class="text-blue-600">✓</span> <span data-i18n="pricing_trial_3">Pembaruan Manual</span></li>
                        <li class="flex items-center gap-2"><span class="text-blue-600">✓</span> <span data-i18n="pricing_trial_4">Akses Web</span></li>
                    </ul>
                    <a href="{{ route('login') }}"
                        class="block w-full py-3 border-2 border-blue-600 text-blue-700 rounded-lg font-semibold hover:bg-blue-50 transition"><span data-i18n="pricing_trial_cta">Coba
                        Gratis</span> →</a>
                </div>

                <!-- Premium Plan -->
                <div class="pricing-premium bg-gradient-to-br from-blue-600 to-blue-700 p-8 rounded-2xl shadow-2xl relative transform md:scale-105 z-10"
                    data-aos="fade-down" data-aos-delay="200">
                    <span
                        class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full uppercase backdrop-blur-sm" data-i18n="pricing_premium_badge">Paling
                        Populer</span>
                    <h3 class="text-4xl font-bold text-white mt-4 mb-2"><span data-i18n="pricing_premium_price">Rp 50.000</span><span
                            class="text-lg font-normal text-white/80" data-i18n="pricing_premium_period">/bulan</span></h3>
                    <p class="text-white/90 text-sm mb-6" data-i18n="pricing_premium_desc">Terbaik untuk peternakan berkembang</p>
                    <ul class="text-left space-y-3 mb-8 text-sm text-white/90">
                        <li class="flex items-center gap-2"><span class="text-blue-200">✓</span> <span data-i18n="pricing_premium_1">Ternak Tidak Terbatas</span>
                        </li>
                        <li class="flex items-center gap-2"><span class="text-blue-200">✓</span> <span data-i18n="pricing_premium_2">Riwayat Kesehatan
                            Lengkap</span></li>
                        <li class="flex items-center gap-2"><span class="text-blue-200">✓</span> <span data-i18n="pricing_premium_3">Notifikasi Reproduksi</span>
                        </li>
                        <li class="flex items-center gap-2"><span class="text-blue-200">✓</span> <span data-i18n="pricing_premium_4">Analisis & Laporan</span></li>
                        <li class="flex items-center gap-2"><span class="text-blue-200">✓</span> <span data-i18n="pricing_premium_5">Dukungan Prioritas</span></li>
                    </ul>
                    <a href="{{ route('login') }}"
                        class="block w-full py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition shadow-lg"><span data-i18n="pricing_premium_cta">Berlangganan
                        Sekarang</span> →</a>
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
                <h2 class="text-4xl md:text-5xl font-bold text-slate-800 mb-2" data-i18n="testimonials_title">Cerita dari Para Peternak</h2>
                <h3
                    class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    FarmGo</h3>
                <p class="mt-4 text-slate-600 max-w-2xl mx-auto" data-i18n="testimonials_desc">Peternak dan pengelola peternakan telah merasakan
                    kemudahan dan efisiensi setelah
                    menggunakan sistem ini. Berikut pendapat mereka tentang pengalaman menggunakan FarmGo.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">

                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-8 rounded-3xl relative shadow-2xl shadow-emerald-500/20"
                    data-aos="fade-down" data-aos-delay="100">
                    <div class="absolute top-6 right-6 text-5xl opacity-20">"</div>
                    <p class="text-sm leading-relaxed mb-6 relative z-10" data-i18n="testimonials_1_text">"Sejak menggunakan FarmGo, pencatatan data
                        ternak jadi jauh
                        lebih mudah dan rapi. Saya bisa memantau kesehatan dan perkembangan ternak kapan saja tanpa
                        harus mencatat manual."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-full overflow-hidden backdrop-blur-sm">
                            <img src="https://i.pravatar.cc/150?u=a042581f4e29026024d" alt="Eko"
                                class="w-full h-full object-cover" width="84" height="84" loading="lazy">
                        </div>
                        <div>
                            <h4 class="font-bold text-lg">Eko Susiloanto</h4>
                            <p class="text-xs text-emerald-100">Peternak Sapi Perah</p>
                        </div>
                        <div class="ml-auto text-yellow-300 text-sm">★★★★★</div>
                    </div>
                </div>

                <div class="bg-white text-slate-700 p-8 rounded-3xl shadow-xl border border-slate-100"
                    data-aos="fade-down" data-aos-delay="200">
                    <div class="absolute top-6 right-6 text-5xl text-slate-100">"</div>
                    <p class="text-sm leading-relaxed mb-6" data-i18n="testimonials_2_text">"Fitur pengingat vaksin dan reproduksi sangat membantu.
                        Tidak ada lagi jadwal yang terlewat, semuanya tercatat otomatis di sistem."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full overflow-hidden">
                            <img src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="Tri"
                                class="w-full h-full object-cover" width="84" height="84" loading="lazy">
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-lg">Tri Cahyono</h4>
                            <p class="text-xs text-slate-600">Peternak Kambing</p>
                        </div>
                        <div class="ml-auto text-amber-400 text-sm">★★★★★</div>
                    </div>
                </div>

                <div class="bg-white text-slate-700 p-8 rounded-3xl shadow-xl border border-slate-100"
                    data-aos="fade-down" data-aos-delay="300">
                    <p class="text-sm leading-relaxed mb-6" data-i18n="testimonials_3_text">"Sistemnya mudah digunakan bahkan oleh peternak yang belum
                        terbiasa dengan teknologi. Tampilan simpel tapi fungsinya lengkap."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-teal-100 rounded-full overflow-hidden">
                            <img src="https://i.pravatar.cc/150?u=a048581f4e29026704d" alt="Tjandra"
                                class="w-full h-full object-cover" width="84" height="84" loading="lazy">
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-lg">Tjandra Mangkualam</h4>
                            <p class="text-xs text-slate-600">Peternak Kambing</p>
                        </div>
                        <div class="ml-auto text-amber-400 text-sm">★★★★★</div>
                    </div>
                </div>

                <div class="bg-white text-slate-700 p-8 rounded-3xl shadow-xl border border-slate-100"
                    data-aos="fade-down" data-aos-delay="400">
                    <p class="text-sm leading-relaxed mb-6" data-i18n="testimonials_4_text">"FarmGo membantu saya mengelola peternakan dengan lebih
                        profesional. Semua data ternak tersimpan aman dan bisa diakses kapan saja."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-cyan-100 rounded-full overflow-hidden">
                            <img src="https://i.pravatar.cc/150?u=a04258114e29026704d" alt="Mukidi"
                                class="w-full h-full object-cover" width="84" height="84" loading="lazy">
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-lg">Cak Mukidi</h4>
                            <p class="text-xs text-slate-600">Peternak Sapi Potong</p>
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
                    alt="Cows in barn" class="w-full h-full object-cover" width="740" height="493" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-900/40 to-transparent"></div>
            </div>

            <div class="p-8 md:p-12 text-white">
                <h2 class="text-3xl font-bold mb-2"><span data-i18n="contact_title_1">Hubungi</span> <span class="text-emerald-100" data-i18n="contact_title_2">Kami</span></h2>
                <p class="mb-8 text-emerald-50" data-i18n="contact_desc">Kami siap membantu kebutuhan manajemen peternakan Anda.</p>

                <form id="contact-form" class="space-y-4">
                    @csrf
                    <input type="text" name="name" data-i18n-placeholder="contact_name_placeholder" placeholder="Nama Lengkap" required
                        class="w-full px-5 py-3.5 rounded-xl bg-white/10 border border-white/20 placeholder-white/70 text-white focus:outline-none focus:bg-white/20 focus:border-white/40 transition backdrop-blur-sm">
                    <input type="email" name="email" data-i18n-placeholder="contact_email_placeholder" placeholder="Alamat Email" required
                        class="w-full px-5 py-3.5 rounded-xl bg-white/10 border border-white/20 placeholder-white/70 text-white focus:outline-none focus:bg-white/20 focus:border-white/40 transition backdrop-blur-sm">
                    <textarea name="message" rows="3" data-i18n-placeholder="contact_message_placeholder" placeholder="Pesan Anda" required minlength="10" maxlength="2000"
                        class="w-full px-5 py-3.5 rounded-xl bg-white/10 border border-white/20 placeholder-white/70 text-white focus:outline-none focus:bg-white/20 focus:border-white/40 transition backdrop-blur-sm resize-none"></textarea>

                    <div id="contact-message" class="hidden p-4 rounded-xl text-sm font-medium"></div>

                    <button type="submit" id="contact-submit"
                        class="w-full bg-white text-emerald-600 font-bold py-3.5 rounded-xl hover:bg-emerald-50 transition shadow-lg transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="contact-submit-text" data-i18n="contact_submit">Kirim Pesan</span>
                        <span id="contact-submit-loading" class="hidden" data-i18n="contact_loading">Mengirim...</span>
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
                <p class="mt-4 text-slate-300 text-sm leading-relaxed" data-i18n="footer_brand_desc">
                    Platform digital untuk membantu peternak mengelola ternak, pakan, dan produksi secara modern.
                </p>
            </div>

            <!-- Produk -->
            <div>
                <h4 class="font-bold mb-5 text-white" data-i18n="footer_products">Produk</h4>
                <ul class="space-y-3 text-sm text-slate-300">
                    <li><a href="#" class="hover:text-emerald-400 transition" data-i18n="footer_product_1">Manajemen Ternak</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition" data-i18n="footer_product_2">Manajemen Reproduksi</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition" data-i18n="footer_product_3">Monitoring Kesehatan</a></li>
                </ul>
            </div>

            <!-- Perusahaan -->
            <div>
                <h4 class="font-bold mb-5 text-white" data-i18n="footer_company">Perusahaan</h4>
                <ul class="space-y-3 text-sm text-slate-300">
                    <li><a href="#" class="hover:text-emerald-400 transition" data-i18n="footer_company_1">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition" data-i18n="footer_company_2">Tim</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition" data-i18n="footer_company_3">Kontak</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition" data-i18n="footer_company_4">Kebijakan Privasi</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="font-bold mb-5 text-white" data-i18n="footer_newsletter_title">Info Peternakan</h4>
                <p class="text-slate-300 text-sm mb-4 leading-relaxed" data-i18n="footer_newsletter_desc">
                    Dapatkan tips peternakan & update fitur terbaru FarmGo.
                </p>
                <form id="newsletter-form" class="space-y-3">
                    @csrf
                    <div class="flex gap-2">
                        <input type="email" name="email" data-i18n-placeholder="footer_newsletter_placeholder" placeholder="Email Anda" required
                            class="flex-1 px-4 py-3 rounded-xl text-white bg-slate-700/50 border border-slate-600 focus:outline-none focus:border-emerald-500 transition text-sm">
                        <button type="submit" id="newsletter-submit"
                            class="bg-gradient-to-r from-emerald-500 to-teal-500 px-5 py-3 rounded-xl hover:from-emerald-600 hover:to-teal-600 transition font-semibold text-sm shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="newsletter-submit-text" data-i18n="footer_newsletter_submit">Kirim</span>
                            <span id="newsletter-submit-loading" class="hidden">...</span>
                        </button>
                    </div>
                    <div id="newsletter-message" class="hidden p-3 rounded-xl text-xs font-medium"></div>
                </form>
            </div>
        </div>

        <hr class="my-10 border-slate-700" />

        <!-- Bottom -->
        <div class="max-w-7xl mx-auto sm:flex sm:items-center sm:justify-between">
            <span class="text-sm text-slate-300" data-i18n="footer_copyright">
                © 2025 FarmGo. Solusi Digital Peternakan Indonesia.
            </span>

            <!-- Sosial Media -->
            <div class="flex mt-4 sm:mt-0 gap-4">
                <a href="#"
                    class="text-slate-300 hover:text-emerald-400 transition p-2 rounded-lg hover:bg-slate-700/50">
                    <!-- Facebook -->
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" />
                    </svg>
                </a>

                <a href="#"
                    class="text-slate-300 hover:text-emerald-400 transition p-2 rounded-lg hover:bg-slate-700/50">
                    <!-- Instagram -->
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm10 2c1.654 0 3 1.346 3 3v10c0 1.654-1.346 3-3 3H7c-1.654 0-3-1.346-3-3V7c0-1.654 1.346-3 3-3h10zm-5 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm4.5-.75a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5z" />
                    </svg>
                </a>

                <a href="#"
                    class="text-slate-300 hover:text-emerald-400 transition p-2 rounded-lg hover:bg-slate-700/50">
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

    <!-- Contact Form AJAX Handler -->
    <script>
        document.getElementById('contact-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = this;
            const submitBtn = document.getElementById('contact-submit');
            const submitText = document.getElementById('contact-submit-text');
            const submitLoading = document.getElementById('contact-submit-loading');
            const messageDiv = document.getElementById('contact-message');

            // Disable submit button and show loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            messageDiv.classList.add('hidden');

            try {
                const formData = new FormData(form);
                const response = await fetch('{{ route('contact.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message
                    messageDiv.className = 'p-4 rounded-xl text-sm font-medium bg-green-500/20 text-green-100 border border-green-500/30';
                    messageDiv.textContent = data.message;
                    messageDiv.classList.remove('hidden');

                    // Reset form
                    form.reset();

                    // Hide message after 5 seconds
                    setTimeout(() => {
                        messageDiv.classList.add('hidden');
                    }, 5000);
                } else {
                    // Show error message
                    let errorMessage = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).flat().join(' ');
                    }
                    messageDiv.className = 'p-4 rounded-xl text-sm font-medium bg-red-500/20 text-red-100 border border-red-500/30';
                    messageDiv.textContent = errorMessage;
                    messageDiv.classList.remove('hidden');
                }
            } catch (error) {
                messageDiv.className = 'p-4 rounded-xl text-sm font-medium bg-red-500/20 text-red-100 border border-red-500/30';
                messageDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi nanti.';
                messageDiv.classList.remove('hidden');
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        });
    </script>

    <!-- Newsletter Form AJAX Handler -->
    <script>
        document.getElementById('newsletter-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = this;
            const submitBtn = document.getElementById('newsletter-submit');
            const submitText = document.getElementById('newsletter-submit-text');
            const submitLoading = document.getElementById('newsletter-submit-loading');
            const messageDiv = document.getElementById('newsletter-message');

            // Disable submit button and show loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            messageDiv.classList.add('hidden');

            try {
                const formData = new FormData(form);
                const response = await fetch('{{ route('newsletter.subscribe') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message
                    messageDiv.className = 'p-3 rounded-xl text-xs font-medium bg-emerald-500/20 text-emerald-100 border border-emerald-500/30';
                    messageDiv.textContent = data.message;
                    messageDiv.classList.remove('hidden');

                    // Reset form
                    form.reset();

                    // Hide message after 5 seconds
                    setTimeout(() => {
                        messageDiv.classList.add('hidden');
                    }, 5000);
                } else {
                    // Show error message
                    let errorMessage = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).flat().join(' ');
                    }
                    messageDiv.className = 'p-3 rounded-xl text-xs font-medium bg-red-500/20 text-red-100 border border-red-500/30';
                    messageDiv.textContent = errorMessage;
                    messageDiv.classList.remove('hidden');
                }
            } catch (error) {
                messageDiv.className = 'p-3 rounded-xl text-xs font-medium bg-red-500/20 text-red-100 border border-red-500/30';
                messageDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi nanti.';
                messageDiv.classList.remove('hidden');
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        });
    </script>

    <!-- i18n Language Switcher -->
    <script>
        (function() {
            const translations = {};
            let currentLang = localStorage.getItem('farmgo_lang') || 'id';

            // Load a language JSON file
            async function loadLang(lang) {
                if (translations[lang]) return translations[lang];
                try {
                    const res = await fetch('/lang/' + lang + '.json');
                    translations[lang] = await res.json();
                    return translations[lang];
                } catch (e) {
                    console.error('Failed to load language:', lang, e);
                    return {};
                }
            }

            // Apply translations to the DOM
            function applyTranslations(dict) {
                document.querySelectorAll('[data-i18n]').forEach(el => {
                    const key = el.getAttribute('data-i18n');
                    if (dict[key]) el.textContent = dict[key];
                });
                document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
                    const key = el.getAttribute('data-i18n-placeholder');
                    if (dict[key]) el.setAttribute('placeholder', dict[key]);
                });
                document.documentElement.setAttribute('lang', currentLang);
            }

            // Update toggle button styles
            function updateToggleUI(lang) {
                const activeClass = 'bg-lime-500 text-white shadow-sm';
                const inactiveClass = 'text-slate-500 hover:text-slate-700';

                ['', '-mobile'].forEach(suffix => {
                    const idBtn = document.getElementById('lang-id-btn' + suffix);
                    const enBtn = document.getElementById('lang-en-btn' + suffix);
                    if (!idBtn || !enBtn) return;

                    if (lang === 'id') {
                        idBtn.className = 'px-3 py-1.5 rounded-full text-xs font-bold transition-all duration-300 ' + activeClass;
                        enBtn.className = 'px-3 py-1.5 rounded-full text-xs font-bold transition-all duration-300 ' + inactiveClass;
                    } else {
                        enBtn.className = 'px-3 py-1.5 rounded-full text-xs font-bold transition-all duration-300 ' + activeClass;
                        idBtn.className = 'px-3 py-1.5 rounded-full text-xs font-bold transition-all duration-300 ' + inactiveClass;
                    }
                });
            }

            // Switch language
            async function switchLang(lang) {
                currentLang = lang;
                localStorage.setItem('farmgo_lang', lang);
                const dict = await loadLang(lang);
                applyTranslations(dict);
                updateToggleUI(lang);
            }

            // Bind click events
            document.addEventListener('DOMContentLoaded', async function() {
                // Desktop buttons
                document.getElementById('lang-id-btn')?.addEventListener('click', () => switchLang('id'));
                document.getElementById('lang-en-btn')?.addEventListener('click', () => switchLang('en'));
                // Mobile buttons
                document.getElementById('lang-id-btn-mobile')?.addEventListener('click', () => switchLang('id'));
                document.getElementById('lang-en-btn-mobile')?.addEventListener('click', () => switchLang('en'));

                // Load saved language on page load
                await switchLang(currentLang);
            });
        })();
    </script>

    <!-- Back to Top Button -->
    <button id="back-to-top"
        class="back-to-top fixed bottom-8 right-8 z-50 w-12 h-12 bg-gradient-to-r from-lime-500 to-green-600 text-white rounded-full shadow-lg shadow-lime-500/30 flex items-center justify-center hover:shadow-xl hover:scale-110 transition-all duration-300"
        aria-label="Back to top">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path>
        </svg>
    </button>

    <!-- Enhanced Interactions Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nav = document.getElementById('main-nav');
            const backToTop = document.getElementById('back-to-top');

            // Navbar scroll effect & back-to-top visibility
            window.addEventListener('scroll', function() {
                if (window.scrollY > 80) {
                    nav.classList.add('nav-scrolled');
                } else {
                    nav.classList.remove('nav-scrolled');
                }

                if (window.scrollY > 500) {
                    backToTop.classList.add('visible');
                } else {
                    backToTop.classList.remove('visible');
                }
            }, { passive: true });

            // Back to top click
            backToTop.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // Animated number counters
            const counters = document.querySelectorAll('.stat-number[data-count]');
            let countersAnimated = false;

            function animateCounters() {
                if (countersAnimated) return;
                const statsSection = document.querySelector('.stat-number');
                if (!statsSection) return;

                const rect = statsSection.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    countersAnimated = true;
                    counters.forEach(counter => {
                        const target = parseInt(counter.getAttribute('data-count'));
                        const suffix = counter.getAttribute('data-suffix') || '+';
                        const duration = 2000;
                        const steps = 60;
                        const increment = target / steps;
                        let current = 0;
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                current = target;
                                clearInterval(timer);
                            }
                            counter.textContent = Math.floor(current).toLocaleString('id-ID') + suffix;
                        }, duration / steps);
                    });
                }
            }

            window.addEventListener('scroll', animateCounters, { passive: true });
            animateCounters(); // Check on load
        });
    </script>

</body>

</html>