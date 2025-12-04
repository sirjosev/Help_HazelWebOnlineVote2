<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - PT INTI (Persero)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="asset/css/style.css">
    <style>
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="bg-white shadow-md fixed w-full z-50 top-0">
        <div class="container mx-auto px-4 md:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <!-- Logo Placeholder -->
                <a href="index.php" class="text-2xl font-extrabold text-indigo-700 flex items-center gap-2">
                    <span class="bg-indigo-700 text-white p-1 rounded">INTI</span>
                    <span class="text-gray-800">E-Voting</span>
                </a>
            </div>
            <div class="hidden md:flex space-x-8">
                <a href="index.php" class="text-indigo-700 font-bold transition">Beranda</a>
                <a href="voting.php" class="text-gray-600 hover:text-indigo-700 font-medium transition">E-Voting</a>
                <a href="#" class="text-gray-600 hover:text-indigo-700 font-medium transition">Layanan</a>
                <a href="#" class="text-gray-600 hover:text-indigo-700 font-medium transition">Berita</a>
                <a href="#" class="text-gray-600 hover:text-indigo-700 font-medium transition">Karir</a>
            </div>
            <div class="flex space-x-3">
                <a href="auth/login.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-5 rounded-lg text-sm transition shadow-md">
                    Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-bg h-[400px] md:h-[500px] flex items-center justify-center text-center text-white mt-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg">Tentang Kami</h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto opacity-90">
                Inovasi Tiada Henti untuk Kemandirian Teknologi Bangsa
            </p>
        </div>
    </header>

    <!-- Company Profile Section -->
    <section class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2069&q=80" alt="Gedung Kantor" class="rounded-2xl shadow-2xl w-full object-cover h-[400px]">
                </div>
                <div class="md:w-1/2 space-y-6">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Profil Perusahaan</h2>
                    <div class="w-20 h-1.5 bg-indigo-600 rounded-full"></div>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        PT INTI (Persero) adalah salah satu Badan Usaha Milik Negara (BUMN) di industri strategis yang secara resmi didirikan pada tanggal 30 Desember 1974. Berkantor pusat di Jalan Moch Toha No. 77 Bandung, perusahaan ini memiliki portofolio di bidang Manufaktur, Sistem Integrator, dan Digital.
                    </p>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Untuk mendukung bisnisnya, PT INTI (Persero) juga mengoperasikan fasilitas produksi seluas delapan hektar di Jalan Moch Toha No 225 yang memproduksi perangkat telekomunikasi dan elektronik.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4">
                        <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100 hover:shadow-md transition">
                            <div class="text-indigo-600 font-bold text-xl mb-1">Manufaktur</div>
                            <p class="text-xs text-gray-500">Produksi perangkat elektronik & telekomunikasi</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100 hover:shadow-md transition">
                            <div class="text-indigo-600 font-bold text-xl mb-1">System Integrator</div>
                            <p class="text-xs text-gray-500">Solusi perangkat keras & lunak terintegrasi</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100 hover:shadow-md transition">
                            <div class="text-indigo-600 font-bold text-xl mb-1">Digital</div>
                            <p class="text-xs text-gray-500">Layanan & produk inovasi digital</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 md:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Visi & Misi</h2>
                <div class="w-20 h-1.5 bg-indigo-600 rounded-full mx-auto mt-4"></div>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg border-t-4 border-indigo-600">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Visi</h3>
                    <p class="text-gray-600 text-lg italic">
                        "Menjadi perusahaan teknologi terpercaya yang mendukung kemandirian bangsa."
                    </p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg border-t-4 border-indigo-600">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Misi</h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-indigo-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Menyediakan produk dan layanan teknologi berkualitas tinggi.
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-indigo-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Membangun ekosistem digital yang berkelanjutan.
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-indigo-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Memberikan nilai tambah bagi seluruh pemangku kepentingan.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Section -->
    <section class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 md:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Pimpinan Perusahaan</h2>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Dedikasi dan profesionalisme untuk memajukan industri teknologi Indonesia.</p>
            </div>

            <!-- Dewan Komisaris -->
            <div class="mb-20">
                <h3 class="text-2xl font-bold text-gray-800 mb-8 border-l-4 border-indigo-600 pl-4">Dewan Komisaris</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Commissioner 1 -->
                    <div class="group">
                        <div class="relative overflow-hidden rounded-xl mb-4 shadow-lg">
                            <img src="https://ui-avatars.com/api/?name=Unggul+Priyanto&background=random&size=400" alt="Unggul Priyanto" class="w-full h-64 object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <h4 class="text-xl font-bold text-gray-900">Dr. Ir. Unggul Priyanto, M.Sc.</h4>
                        <p class="text-indigo-600 font-medium">Komisaris Utama</p>
                    </div>
                    <!-- Commissioner 2 -->
                    <div class="group">
                        <div class="relative overflow-hidden rounded-xl mb-4 shadow-lg">
                            <img src="https://ui-avatars.com/api/?name=Trisno+Hendradi&background=random&size=400" alt="Trisno Hendradi" class="w-full h-64 object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <h4 class="text-xl font-bold text-gray-900">Marsdya TNI (Purn) Trisno Hendradi</h4>
                        <p class="text-indigo-600 font-medium">Komisaris</p>
                    </div>
                    <!-- Commissioner 3 -->
                    <div class="group">
                        <div class="relative overflow-hidden rounded-xl mb-4 shadow-lg">
                            <img src="https://ui-avatars.com/api/?name=Rahmadi+Murwanto&background=random&size=400" alt="Rahmadi Murwanto" class="w-full h-64 object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <h4 class="text-xl font-bold text-gray-900">Rahmadi Murwanto, Ph.D.</h4>
                        <p class="text-indigo-600 font-medium">Komisaris</p>
                    </div>
                    <!-- Commissioner 4 -->
                    <div class="group">
                        <div class="relative overflow-hidden rounded-xl mb-4 shadow-lg">
                            <img src="https://ui-avatars.com/api/?name=Yanuar+Rokhmad&background=random&size=400" alt="Yanuar Rokhmad" class="w-full h-64 object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <h4 class="text-xl font-bold text-gray-900">Yanuar Rokhmad Madyantoro</h4>
                        <p class="text-indigo-600 font-medium">Komisaris</p>
                    </div>
                </div>
            </div>

            <!-- Direksi -->
            <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-8 border-l-4 border-indigo-600 pl-4">Direksi</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Director 1 -->
                    <div class="group">
                        <div class="relative overflow-hidden rounded-xl mb-4 shadow-lg">
                            <img src="https://ui-avatars.com/api/?name=Edi+Witjara&background=random&size=400" alt="Edi Witjara" class="w-full h-80 object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <h4 class="text-xl font-bold text-gray-900">Dr. Edi Witjara, CMA.</h4>
                        <p class="text-indigo-600 font-medium">Direktur Utama</p>
                    </div>
                    <!-- Director 2 -->
                    <div class="group">
                        <div class="relative overflow-hidden rounded-xl mb-4 shadow-lg">
                            <img src="https://ui-avatars.com/api/?name=Ahmad+Taufik&background=random&size=400" alt="Ahmad Taufik" class="w-full h-80 object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <h4 class="text-xl font-bold text-gray-900">Ahmad Taufik, S.T.</h4>
                        <p class="text-indigo-600 font-medium">Direktur</p>
                    </div>
                    <!-- Director 3 -->
                    <div class="group">
                        <div class="relative overflow-hidden rounded-xl mb-4 shadow-lg">
                            <img src="https://ui-avatars.com/api/?name=Tantang+Yudha&background=random&size=400" alt="Tantang Yudha" class="w-full h-80 object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <h4 class="text-xl font-bold text-gray-900">Tantang Yudha Santoso, ST.</h4>
                        <p class="text-indigo-600 font-medium">Direktur</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4">PT INTI (Persero)</h3>
                    <p class="text-gray-400">
                        Jalan Moch Toha No. 77<br>
                        Bandung 40253, Indonesia
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="index.php" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="voting.php" class="hover:text-white transition">E-Voting</a></li>
                        <li><a href="#" class="hover:text-white transition">Hubungi Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Ikuti Kami</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-indigo-600 transition">
                            <!-- Icon placeholder -->
                            <span class="font-bold">FB</span>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-indigo-600 transition">
                            <span class="font-bold">IG</span>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-indigo-600 transition">
                            <span class="font-bold">LN</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-500">
                <p>&copy; 2024 PT INTI (Persero). All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
