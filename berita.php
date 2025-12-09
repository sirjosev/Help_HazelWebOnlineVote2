<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita - PT INTI (Persero)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="asset/css/style.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-bg {
            background-image: linear-gradient(rgba(79, 70, 229, 0.9), rgba(67, 56, 202, 0.9)), url('https://images.unsplash.com/photo-1504711434969-e33886168f5c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
        }
        .news-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="bg-white shadow-md fixed w-full z-50 top-0">
        <div class="container mx-auto px-4 md:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <a href="index.php" class="text-2xl font-extrabold text-indigo-700 flex items-center gap-2">
                    <span class="bg-indigo-700 text-white p-1 rounded">INTI</span>
                    <span class="text-gray-800">E-Voting</span>
                </a>
            </div>
            <div class="hidden md:flex space-x-8">
                <a href="index.php" class="text-gray-600 hover:text-indigo-700 font-medium transition">Beranda</a>
                <a href="voting.php" class="text-gray-600 hover:text-indigo-700 font-medium transition">E-Voting</a>
                <a href="berita.php" class="text-indigo-700 font-bold transition">Berita</a>
            </div>
            <div class="flex space-x-3">
                <a href="auth/login.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-5 rounded-lg text-sm transition shadow-md">
                    Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-bg h-[300px] md:h-[350px] flex items-center justify-center text-center text-white mt-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 drop-shadow-lg">Berita & Informasi</h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto opacity-90">
                Update terbaru seputar kegiatan dan perkembangan PT INTI
            </p>
        </div>
    </header>

    <!-- News Section -->
    <section class="py-16 md:py-20 bg-gray-50">
        <div class="container mx-auto px-4 md:px-8">
            <!-- Featured News -->
            <div class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 border-l-4 border-indigo-600 pl-4">Berita Utama</h2>
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden md:flex">
                    <div class="md:w-1/2">
                        <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" 
                             alt="Featured News" 
                             class="w-full h-64 md:h-full object-cover">
                    </div>
                    <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                        <span class="inline-block bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-sm font-semibold mb-4 w-fit">Berita Terkini</span>
                        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                            PT INTI Luncurkan Sistem E-Voting Terbaru untuk Mendukung Transparansi Pemilu
                        </h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Dalam upaya mendukung transparansi dan efisiensi proses pemilihan umum, PT INTI (Persero) resmi meluncurkan sistem E-Voting terbaru yang dilengkapi dengan teknologi keamanan mutakhir dan antarmuka yang user-friendly.
                        </p>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            9 Desember 2024
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest News Grid -->
            <h2 class="text-2xl font-bold text-gray-900 mb-8 border-l-4 border-indigo-600 pl-4">Berita Terbaru</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- News Card 1 -->
                <article class="news-card bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1469&q=80" 
                         alt="News 1" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold mb-3">Teknologi</span>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                            Kolaborasi dengan Kementerian Kominfo untuk Digitalisasi Pelayanan Publik
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            PT INTI menjalin kerjasama strategis dengan Kementerian Komunikasi dan Informatika untuk mengembangkan infrastruktur digital...
                        </p>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>8 Desember 2024</span>
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Baca Selengkapnya →</a>
                        </div>
                    </div>
                </article>

                <!-- News Card 2 -->
                <article class="news-card bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1560472355-536de3962603?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" 
                         alt="News 2" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-semibold mb-3">Event</span>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                            Workshop Keamanan Siber untuk Meningkatkan Kapabilitas SDM
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            Mengadakan workshop intensif tentang keamanan siber yang diikuti oleh lebih dari 200 karyawan dari berbagai divisi...
                        </p>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>5 Desember 2024</span>
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Baca Selengkapnya →</a>
                        </div>
                    </div>
                </article>

                <!-- News Card 3 -->
                <article class="news-card bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" 
                         alt="News 3" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="inline-block bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-semibold mb-3">CSR</span>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                            Program Beasiswa untuk Mahasiswa Teknik Informatika Berprestasi
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            Sebagai bentuk kepedulian terhadap pendidikan, PT INTI memberikan beasiswa kepada 50 mahasiswa berprestasi...
                        </p>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>1 Desember 2024</span>
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Baca Selengkapnya →</a>
                        </div>
                    </div>
                </article>

                <!-- News Card 4 -->
                <article class="news-card bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1531973576160-7125cd663d86?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" 
                         alt="News 4" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="inline-block bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-semibold mb-3">Penghargaan</span>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                            Raih Penghargaan Inovasi Digital di BUMN Innovation Award 2024
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            PT INTI berhasil meraih penghargaan bergengsi dalam kategori Inovasi Digital Terbaik pada ajang BUMN Innovation Award...
                        </p>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>28 November 2024</span>
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Baca Selengkapnya →</a>
                        </div>
                    </div>
                </article>

                <!-- News Card 5 -->
                <article class="news-card bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" 
                         alt="News 5" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="inline-block bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-semibold mb-3">Kerjasama</span>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                            MoU dengan Universitas Telkom untuk Riset dan Pengembangan
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            Penandatanganan nota kesepahaman antara PT INTI dengan Universitas Telkom untuk pengembangan riset teknologi...
                        </p>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>25 November 2024</span>
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Baca Selengkapnya →</a>
                        </div>
                    </div>
                </article>

                <!-- News Card 6 -->
                <article class="news-card bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" 
                         alt="News 6" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="inline-block bg-teal-100 text-teal-700 px-2 py-1 rounded text-xs font-semibold mb-3">Corporate</span>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                            Rapat Umum Pemegang Saham Tahunan PT INTI (Persero)
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            Pelaksanaan RUPS Tahunan yang membahas kinerja perusahaan tahun 2024 serta rencana strategis untuk tahun mendatang...
                        </p>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>20 November 2024</span>
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Baca Selengkapnya →</a>
                        </div>
                    </div>
                </article>

            </div>

            <!-- Load More Button -->
            <div class="text-center mt-12">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl">
                    Muat Lebih Banyak
                </button>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-indigo-700">
        <div class="container mx-auto px-4 md:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Berlangganan Newsletter</h2>
            <p class="text-indigo-200 mb-8 max-w-2xl mx-auto">
                Dapatkan update berita terbaru langsung ke email Anda
            </p>
            <form class="flex flex-col md:flex-row gap-4 max-w-lg mx-auto">
                <input type="email" placeholder="Masukkan email Anda" 
                       class="flex-1 px-5 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <button type="submit" class="bg-white text-indigo-700 font-bold px-6 py-3 rounded-lg hover:bg-gray-100 transition">
                    Berlangganan
                </button>
            </form>
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
                        <li><a href="berita.php" class="hover:text-white transition">Berita</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Ikuti Kami</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-indigo-600 transition">
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
