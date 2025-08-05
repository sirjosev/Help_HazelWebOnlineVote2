<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Voting - Hitung Cepat</title>
    <!-- Memuat Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Menggunakan font Inter sebagai default */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
        }
        /* Animasi untuk progress bar */
        .progress-bar-inner {
            transition: width 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <!-- Tombol Aksi di Pojok Kanan Atas -->
    <div class="absolute top-0 right-0 p-4 md:p-6">
        <a href="auth/login.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm md:text-base transition-colors duration-300 shadow-md">
            Login
        </a>
    </div>

    <div class="container mx-auto p-4 md:p-8">
        <!-- Header -->
        <header class="text-center mb-10 pt-16 md:pt-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800">Sistem E-Voting</h1>
            <p class="text-lg text-gray-600 mt-2">Hasil Hitung Cepat (Quick Count) Pemilihan Umum</p>
            <div class="mt-4 inline-flex items-center">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
                <span class="ml-3 font-semibold text-red-600">LIVE</span>
            </div>
        </header>

        <!-- Kontainer untuk semua kartu event pemilihan -->
        <main id="quick-count-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Kartu event akan dimuat oleh JavaScript di sini -->
            <!-- Contoh struktur kartu (akan dihapus dan diganti data live) -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200 animate-pulse">
                <div class="h-6 bg-gray-300 rounded w-3/4 mb-2"></div>
                <div class="h-4 bg-gray-300 rounded w-1/2 mb-6"></div>
                <div class="space-y-4">
                    <div class="h-10 bg-gray-300 rounded"></div>
                    <div class="h-10 bg-gray-300 rounded"></div>
                    <div class="h-10 bg-gray-300 rounded"></div>
                </div>
                <div class="h-4 bg-gray-300 rounded w-1/3 mt-6 ml-auto"></div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="text-center mt-12 text-gray-500">
            <p>&copy; 2024 Proyek E-Voting. Dibuat dengan semangat transparansi.</p>
        </footer>
    </div>

    <script>
        // Elemen kontainer untuk menampung kartu-kartu quick count
        const container = document.getElementById('quick-count-container');

        /**
         * Fungsi untuk mengambil data quick count dari backend API.
         * Secara periodik, fungsi ini akan dipanggil untuk memperbarui tampilan.
         */
        async function fetchQuickCountData() {
            try {
                // --- BAGIAN UNTUK KONEKSI KE BACKEND ---
                // Mengambil data langsung dari API quick count.
                const response = await fetch('api/quick_count.php');

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                // -----------------------------------------

                updateUI(data);

            } catch (error) {
                console.error('Gagal mengambil data quick count:', error);
                container.innerHTML = `<p class="text-center text-red-500 col-span-full">Tidak dapat memuat data. Silakan coba lagi nanti.</p>`;
            }
        }

        /**
         * Fungsi untuk memperbarui tampilan (UI) berdasarkan data yang diterima.
         * @param {Array} events - Array berisi objek-objek event pemilihan.
         */
        function updateUI(events) {
            // Kosongkan kontainer sebelum mengisi dengan data baru
            container.innerHTML = '';

            if (!events || events.length === 0) {
                container.innerHTML = `<p class="text-center text-gray-600 col-span-full">Belum ada event pemilihan yang berlangsung.</p>`;
                return;
            }

            events.forEach(event => {
                const totalVotes = event.candidates.reduce((sum, candidate) => sum + candidate.votes, 0);

                // Membuat HTML untuk setiap kandidat
                const candidatesHtml = event.candidates.map(candidate => {
                    const percentage = totalVotes > 0 ? ((candidate.votes / totalVotes) * 100).toFixed(2) : 0;
                    const imageUrl = `assets/images/${candidate.image || 'placeholder.png'}`;
                    return `
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center">
                                    <img src="${imageUrl}" alt="${candidate.name}" class="h-8 w-8 rounded-full object-cover mr-3">
                                    <span class="font-semibold text-gray-700">${candidate.name}</span>
                                </div>
                                <span class="text-sm font-bold text-gray-800">${percentage}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-6">
                                <div class="bg-indigo-600 h-6 rounded-full text-white text-xs font-medium flex items-center justify-center progress-bar-inner" style="width: ${percentage}%">
                                   ${candidate.votes.toLocaleString('id-ID')} Suara
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');

                // Membuat kartu event
                const eventCard = `
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200 transform hover:-translate-y-1 transition-transform duration-300">
                        <h2 class="text-xl font-bold text-gray-900">${event.eventName}</h2>
                        <p class="text-sm text-gray-500 mb-6">${event.position}</p>
                        <div>
                            ${candidatesHtml}
                        </div>
                        <div class="text-right mt-4 text-sm text-gray-600 font-medium">
                            Total Suara Masuk: <span class="font-bold">${totalVotes.toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                `;

                // Menambahkan kartu ke kontainer
                container.innerHTML += eventCard;
            });
        }


        // --- INISIALISASI ---
        // Panggil fungsi untuk pertama kali saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            fetchQuickCountData();

            // Atur interval untuk memperbarui data setiap 5 detik (5000 ms)
            setInterval(fetchQuickCountData, 5000);
        });
    </script>

</body>
</html>
