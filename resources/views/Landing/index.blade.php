<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmGo Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom font adjustment to match the clean look */
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white">

<nav class="bg-white p-4 px-6 md:px-12 flex justify-between items-center w-full fixed top-0 z-50 shadow-sm">

    <div class="flex items-center gap-2">
        <img src="{{ asset('image/FarmGo.png') }}" alt="Gambar" class="max-w-12 max-h-12">
    </div>




    <div class="hidden md:flex space-x-8 text-gray-700 font-medium text-sm">
        <a href="#" class="hover:text-green-500 transition">Home</a>
        <a href="#" class="hover:text-green-500 transition">About</a>
        <a href="#" class="hover:text-green-500 transition">Services</a>
        <a href="#" class="hover:text-green-500 transition">Contact</a>
    </div>

    <div class="hidden md:block">
        <a href="" class="bg-lime-400 hover:bg-lime-500 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-300 ease-in-out">
            Start Free Trial
        </a>
    </div>

    <div class="md:hidden flex items-center">
        <button id="mobile-menu-btn" class="text-gray-700 hover:text-green-500 focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

</nav>

<div id="mobile-menu" class="hidden md:hidden fixed top-16 left-0 w-full bg-white shadow-md z-40 p-4">
    <a href="#" class="block py-2 text-gray-700 hover:text-green-500 transition">Home</a>
    <a href="#" class="block py-2 text-gray-700 hover:text-green-500 transition">About</a>
    <a href="#" class="block py-2 text-gray-700 hover:text-green-500 transition">Services</a>
    <a href="#" class="block py-2 text-gray-700 hover:text-green-500 transition">Contact</a>
    <a href="{{ route('login') }}" class="block mt-4 bg-lime-400 text-center text-white py-2 rounded-lg font-semibold">
        Start Free Trial
    </a>
</div>

<section class="relative pt-24 pb-12 px-6 md:px-12 min-h-screen flex items-center bg-gradient-to-br from-[#dcfce7] via-[#ecfccb] to-[#f0fdf4]">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">

        <div class="space-y-6">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                Smarter Livestock Management  <br>
                <span class="text-gray-900">for Your Growing Herd</span>
            </h1>
            <p class="text-gray-600 text-lg leading-relaxed max-w-lg">
                Kelola operasional ternak harian Anda dengan aplikasi web & seluler all-in-one untuk kontrol mudah, visibilitas total – Kapan Saja, Di Mana Saja!
            </p>
            <div class="pt-2">
                <button class="bg-gray-900 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
                    Pelajari Lebih Lanjut
                </button>
            </div>
        </div>

        <div class="relative flex justify-center">
            <img src="{{ asset('image/Pic.png') }}"
                 alt="3D Illustration of Cow and Goat"
                 class="w-full max-w-lg drop-shadow-xl rounded-xl object-contain">
        </div>

    </div>
</section>

<section  class="py-16 px-6 md:px-12 bg-gray-50">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">


        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Our Mission</h3>
                <p class="text-gray-500 mt-2 text-sm">Memberikan solusi teknologi peternakan modern untuk
                    meningkatkan produktivitas dan kesejahteraan peternak lokal.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Our Vision</h3>
                <p class="text-gray-500 mt-2 text-sm">Menjadi platform manajemen ternak nomor satu yang
                    terintegrasi dan mudah digunakan di seluruh dunia.</p>
            </div>
        </div>
        <div class="relative h-full min-h-[400px] shadow-xl rounded-3xl overflow-hidden">
            <img src="https://images.unsplash.com/photo-1596733430284-f7437764b1a9?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Cows in field" class="object-cover w-full h-full shadow-2xl rounded-3xl">
        </div>
    </div>
</section>

<section class="py-16 px-6 md:px-12 bg-gray-50">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
        <div>
            <img src="https://images.unsplash.com/photo-1545468800-85cc9bc6ecf7?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                 alt="Cow closeup" class="rounded-3xl shadow-2xl w-full h-auto object-cover">
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-lg">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Why Choose Us</h2>
            <p class="text-gray-600 mb-6">Kelola peternakan lebih efisien dan akurat dengan sistem data terintegrasi.</p>

            <ul class="space-y-4">
                <li class="flex items-center gap-3">
                    <span class="text-green-500 bg-green-100 p-1 rounded-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                    <span class="font-semibold text-gray-700">Akses Data Akurat</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="text-green-500 bg-green-100 p-1 rounded-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                    <span class="font-semibold text-gray-700">Pemantauan Kesehatan  </span>
                </li>

                <li class="flex items-center gap-3">
                    <span class="text-green-500 bg-green-100 p-1 rounded-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                    <span class="font-semibold text-gray-700">Efisiensi Waktu & Biaya</span>
                </li>
            </ul>
        </div>
    </div>
</section>



<section class="py-16 px-6 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">All Farm Management Needs You</h2>
        <p class="text-gray-500 mb-10">Solusi selengkapnya untuk mempermudah segala urusan peternakan.</p>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="md:col-span-2 grid sm:grid-cols-2 gap-6">
                <div class="bg-green-50 p-6 rounded-2xl hover:bg-green-100 transition duration-300">
                    <div class="text-green-600 mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Manajemen Data Ternak</h3>
                    <p class="text-sm text-gray-600">Simpan data kelahiran, bobot, dan riwayat medis secara digital.</p>
                </div>
                <div class="bg-white border border-gray-100 shadow-sm p-6 rounded-2xl hover:shadow-md transition duration-300">
                    <div class="text-green-600 mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Monitoring Kesehatan</h3>
                    <p class="text-sm text-gray-600">Lacak jadwal vaksinasi dan kondisi kesehatan hewan.</p>
                </div>
                <div class="bg-white border border-gray-100 shadow-sm p-6 rounded-2xl hover:shadow-md transition duration-300">
                    <div class="text-green-600 mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Notifikasi & Alert</h3>
                    <p class="text-sm text-gray-600">Dapatkan pengingat otomatis untuk jadwal penting.</p>
                </div>
                <div class="bg-white border border-gray-100 shadow-sm p-6 rounded-2xl hover:shadow-md transition duration-300">
                    <div class="text-green-600 mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Analisis Keuangan</h3>
                    <p class="text-sm text-gray-600">Pantau profitabilitas dan pengeluaran pakan.</p>
                </div>
            </div>

            <div class="h-full">
                <img src="https://images.unsplash.com/photo-1500595046743-cd271d694d30?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Farm landscape" class="rounded-2xl h-full object-cover shadow-lg">
            </div>
        </div>
    </div>
</section>

<section class="py-16 px-6 md:px-12 bg-gray-50">
    <div class="max-w-7xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-blue-900 mb-10">Rencana Sempurnamu</h2>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                <span class="bg-blue-100 text-blue-600 text-xs font-bold px-3 py-1 rounded-full uppercase">Basic</span>
                <h3 class="text-4xl font-bold text-gray-900 mt-4 mb-2">$0<span class="text-lg font-normal text-gray-500">/month</span></h3>
                <p class="text-gray-500 text-sm mb-6">Perfect for small farms</p>
                <ul class="text-left space-y-3 mb-8 text-sm text-gray-600">
                    <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Up to 50 Animals</li>
                    <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Basic Health Records</li>
                    <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Manual Updates</li>
                </ul>
                <a href="#" class="block w-full py-2 border border-blue-500 text-blue-500 rounded-lg font-semibold hover:bg-blue-50 transition">Get Started ↗</a>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-xl border-2 border-blue-200 relative transform scale-105 z-10">
                <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase">Most Popular</span>
                <h3 class="text-4xl font-bold text-gray-900 mt-4 mb-2">$5<span class="text-lg font-normal text-gray-500">/month</span></h3>
                <p class="text-gray-500 text-sm mb-6">Best for growing farms</p>
                <ul class="text-left space-y-3 mb-8 text-sm text-gray-600">
                    <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Up to 200 Animals</li>
                    <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Full Health History</li>
                    <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Reproduction Alerts</li>
                    <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Mobile Access</li>
                </ul>
                <a href="#" class="block w-full py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">Subscribe ↗</a>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                <span class="bg-blue-100 text-blue-600 text-xs font-bold px-3 py-1 rounded-full uppercase">Enterprise</span>
                <h3 class="text-4xl font-bold text-gray-900 mt-4 mb-2">$10<span class="text-lg font-normal text-gray-500">/month</span></h3>
                <p class="text-gray-500 text-sm mb-6">For large commercial farms</p>
                <ul class="text-left space-y-3 mb-8 text-sm text-gray-600">
                    <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Unlimited Animals</li>
                    <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Advanced Analytics</li>
                    <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Multi-User Access</li>
                    <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Priority Support</li>
                </ul>
                <a href="#" class="block w-full py-2 border border-blue-500 text-blue-500 rounded-lg font-semibold hover:bg-blue-50 transition">Get Started ↗</a>
            </div>
        </div>
    </div>
</section>


{{--<section class="py-16 px-6 md:px-12 bg-white">--}}
{{--    <div class="max-w-7xl mx-auto">--}}
{{--        <div class="text-center mb-12">--}}
{{--            <h2 class="text-3xl font-bold text-gray-900">Galeri <span class="text-green-500">Kami</span></h2>--}}
{{--            <p class="text-gray-500">Intip aktivitas peternakan modern yang dikelola dengan FarmGo.</p>--}}
{{--        </div>--}}

{{--        <div class="grid md:grid-cols-4 gap-4 mb-20 md:h-96">--}}
{{--            <div class="md:col-span-2 row-span-2 relative overflow-hidden rounded-2xl h-64 md:h-auto">--}}
{{--                <img src="https://images.stockcake.com/public/1/7/d/17d3ac65-91b4-48c0-83c0-b97528deaf6d_large/cow-eating-grass-stockcake.jpg" class="w-full h-full object-cover hover:scale-110 transition duration-500" alt="Cows eating">--}}
{{--            </div>--}}
{{--            <div class="relative overflow-hidden rounded-2xl h-48 md:h-auto">--}}
{{--                <img src="https://images.unsplash.com/photo-1505499238323-9529b6736279?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover hover:scale-110 transition duration-500" alt="Farmer">--}}
{{--            </div>--}}
{{--            <div class="relative overflow-hidden rounded-2xl h-48 md:h-auto">--}}
{{--                <img src="https://images.unsplash.com/photo-1516916759473-600c07bc99d7?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover hover:scale-110 transition duration-500" alt="Barn">--}}
{{--            </div>--}}
{{--            <div class="md:col-span-2 relative overflow-hidden rounded-2xl h-64 md:h-auto">--}}
{{--                <img src="https://images.unsplash.com/photo-1595822987179-883a9a2a7a92?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover hover:scale-110 transition duration-500" alt="Calves">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}

<section class="py-16 px-6 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">Cerita dari Para Peternak</h2>
            <h3 class="text-2xl font-bold text-green-500">yang Telah Menggunakan FarmGo</h3>
        </div>

        <div class="grid md:grid-cols-2 gap-8">

            <div class="bg-green-500 text-white p-8 rounded-3xl relative shadow-lg">
                <p class="text-sm leading-relaxed mb-6">"Sejak menggunakan FarmGo, pencatatan data ternak jadi jauh lebih mudah dan rapi. Saya bisa memantau kesehatan dan perkembangan ternak kapan saja tanpa harus mencatat manual."</p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden"><img src="https://i.pravatar.cc/150?u=a042581f4e29026024d" alt="Eko"></div>
                    <div>
                        <h4 class="font-bold text-lg">Eko Susiloanto</h4>
                        <p class="text-xs opacity-80">Regional Mobility Manager</p>
                    </div>
                    <div class="ml-auto text-yellow-300 text-sm">★★★★★</div>
                </div>
            </div>

            <div class="bg-white text-gray-700 p-8 rounded-3xl shadow-lg border border-gray-100">
                <p class="text-sm leading-relaxed mb-6">"Fitur pengingat vaksin dan reproduksi sangat membantu. Tidak ada lagi jadwal yang terlewat, semuanya tercatat otomatis di sistem."</p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden"><img src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="Tri"></div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Tri Cahyono</h4>
                        <p class="text-xs text-gray-500">Human Accounts Supervisor</p>
                    </div>
                    <div class="ml-auto text-yellow-400 text-sm">★★★★★</div>
                </div>
            </div>

            <div class="bg-white text-gray-700 p-8 rounded-3xl shadow-lg border border-gray-100">
                <p class="text-sm leading-relaxed mb-6">"Sistemnya mudah digunakan bahkan oleh peternak yang belum terbiasa dengan teknologi. Tampilan simpel tapi fungsinya lengkap."</p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden"><img src="https://i.pravatar.cc/150?u=a048581f4e29026704d" alt="Tjandra"></div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Tjandra Mangkualam</h4>
                        <p class="text-xs text-gray-500">District Directives Producer</p>
                    </div>
                    <div class="ml-auto text-yellow-400 text-sm">★★★★★</div>
                </div>
            </div>

            <div class="bg-white text-gray-700 p-8 rounded-3xl shadow-lg border border-gray-100">
                <p class="text-sm leading-relaxed mb-6">"FarmGo membantu saya mengelola peternakan dengan lebih profesional. Semua data ternak tersimpan aman dan bisa diakses kapan saja."</p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden"><img src="https://i.pravatar.cc/150?u=a04258114e29026704d" alt="Mukidi"></div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Cak Mukidi</h4>
                        <p class="text-xs text-gray-500">Forward Paradigm Manager</p>
                    </div>
                    <div class="ml-auto text-yellow-400 text-sm">★★★★★</div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="py-16 px-6 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto bg-emerald-500 rounded-3xl overflow-hidden shadow-2xl grid md:grid-cols-2">
        <div class="hidden md:block relative">
            <img src="https://img.freepik.com/free-photo/funny-curious-cow-looking-camera-while-other-cows-eating-hay-background-cattle-farm_342744-895.jpg?semt=ais_hybrid&w=740&q=80" alt="Cows in barn" class="w-full h-full object-cover opacity-90">
            <div class="absolute inset-0 bg-green-900/20"></div>
        </div>

        <div class="p-8 md:p-12 text-white">
            <h2 class="text-3xl font-bold mb-2">Hubungi <span class="text-emerald-900">Kami</span></h2>
            <p class="mb-8 text-emerald-50">Kami siap membantu kebutuhan manajemen peternakan Anda.</p>

            <form class="space-y-4">
                <input type="text" placeholder="Full Name" class="w-full px-4 py-3 rounded-lg bg-emerald-600/50 border border-emerald-400 placeholder-emerald-100 text-white focus:outline-none focus:bg-emerald-600 transition">
                <input type="email" placeholder="Email Address" class="w-full px-4 py-3 rounded-lg bg-emerald-600/50 border border-emerald-400 placeholder-emerald-100 text-white focus:outline-none focus:bg-emerald-600 transition">
                <textarea rows="3" placeholder="Message" class="w-full px-4 py-3 rounded-lg bg-emerald-600/50 border border-emerald-400 placeholder-emerald-100 text-white focus:outline-none focus:bg-emerald-600 transition"></textarea>

                <button class="w-full bg-white text-emerald-600 font-bold py-3 rounded-lg hover:bg-emerald-50 transition">Send Message</button>
            </form>
        </div>
    </div>
</section>





<footer class="bg-slate-900 text-white py-12 px-6 border-t border-slate-800">
    <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-8">
        <div>
            <span class="text-xl font-bold text-green-400">FarmGo</span>
            <p class="mt-4 text-gray-400 text-sm">Solusi digital terbaik untuk peternakan modern masa kini.</p>
        </div>
        <div>
            <h4 class="font-bold mb-4">Product</h4>
            <ul class="space-y-2 text-sm text-gray-400">
                <li><a href="#" class="hover:text-green-400">Features</a></li>
                <li><a href="#" class="hover:text-green-400">Pricing</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold mb-4">Company</h4>
            <ul class="space-y-2 text-sm text-gray-400">
                <li><a href="#" class="hover:text-green-400">About Us</a></li>
                <li><a href="#" class="hover:text-green-400">Contact</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold mb-4">Newsletter</h4>
            <div class="flex">
                <input type="email" placeholder="Your email" class="px-4 py-2 rounded-l-lg text-gray-900 w-full focus:outline-none">
                <button class="bg-blue-600 px-4 py-2 rounded-r-lg hover:bg-blue-700">Go</button>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto mt-8 pt-8 border-t border-slate-800 text-center text-xs text-gray-500">
        &copy; 2025 FarmGo. All rights reserved.
    </div>
</footer>








<script>
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>



</body>
</html>
