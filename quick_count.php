<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Voting - Hitung Cepat</title>
    <!-- Memuat Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat Chart.js untuk Pie Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Memuat Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="asset/css/style.css">
    <style>
        /* Menggunakan font Inter sebagai default */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
        }
        /* Styling untuk pie chart container */
        .chart-container {
            position: relative;
            max-width: 280px;
            margin: 0 auto;
        }
        /* Legend styling */
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }
        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <!-- Tombol Aksi di Pojok Kanan Atas -->
    <div class="absolute top-0 right-0 p-4 md:p-6">
        <a href="index.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm md:text-base transition-colors duration-300 shadow-md">
            &larr; Kembali ke Beranda
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

        // Menyimpan instance chart untuk update
        const chartInstances = {};

        // Warna-warna untuk pie chart
        const chartColors = [
            '#6366f1', // Indigo
            '#f43f5e', // Rose
            '#10b981', // Emerald
            '#f59e0b', // Amber
            '#8b5cf6', // Violet
            '#06b6d4', // Cyan
            '#ec4899', // Pink
            '#84cc16', // Lime
            '#3b82f6', // Blue
            '#ef4444'  // Red
        ];

        /**
         * Fungsi untuk memperbarui tampilan (UI) berdasarkan data yang diterima.
         * @param {Array} events - Array berisi objek-objek event pemilihan.
         */
        function updateUI(events) {
            // Kosongkan kontainer sebelum mengisi dengan data baru
            container.innerHTML = '';

            // Destroy existing chart instances
            Object.values(chartInstances).forEach(chart => chart.destroy());
            Object.keys(chartInstances).forEach(key => delete chartInstances[key]);

            if (!events || events.length === 0) {
                container.innerHTML = `<p class="text-center text-gray-600 col-span-full">Belum ada event pemilihan yang berlangsung.</p>`;
                return;
            }

            events.forEach((event, eventIndex) => {
                const totalVotes = event.candidates.reduce((sum, candidate) => sum + candidate.votes, 0);
                const chartId = `pie-chart-${eventIndex}`;

                // Membuat legend untuk setiap kandidat
                const legendHtml = event.candidates.map((candidate, index) => {
                    const percentage = totalVotes > 0 ? ((candidate.votes / totalVotes) * 100).toFixed(1) : 0;
                    const color = chartColors[index % chartColors.length];
                    return `
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: ${color};"></div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-700 text-sm">${candidate.name}</div>
                                <div class="text-xs text-gray-500">${candidate.votes.toLocaleString('id-ID')} suara (${percentage}%)</div>
                            </div>
                        </div>
                    `;
                }).join('');

                // Membuat kartu event dengan pie chart
                const eventCard = `
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200 transform hover:-translate-y-1 transition-transform duration-300">
                        <h2 class="text-xl font-bold text-gray-900 text-center">${event.eventName}</h2>
                        <p class="text-sm text-gray-500 mb-4 text-center">${event.position}</p>
                        
                        <!-- Pie Chart -->
                        <div class="chart-container mb-4">
                            <canvas id="${chartId}"></canvas>
                        </div>
                        
                        <!-- Legend -->
                        <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                            ${legendHtml}
                        </div>
                        
                        <div class="text-center mt-4 text-sm text-gray-600 font-medium">
                            Total Suara Masuk: <span class="font-bold text-indigo-600">${totalVotes.toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                `;

                // Menambahkan kartu ke kontainer
                container.innerHTML += eventCard;
            });

            // Membuat chart setelah HTML sudah ditambahkan ke DOM
            events.forEach((event, eventIndex) => {
                const chartId = `pie-chart-${eventIndex}`;
                const ctx = document.getElementById(chartId).getContext('2d');
                
                const labels = event.candidates.map(c => c.name);
                const data = event.candidates.map(c => c.votes);
                const colors = event.candidates.map((_, index) => chartColors[index % chartColors.length]);

                chartInstances[chartId] = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: colors,
                            borderColor: '#ffffff',
                            borderWidth: 3,
                            hoverBorderWidth: 4,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false // Kita pakai custom legend
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 13 },
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const value = context.raw;
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${value.toLocaleString('id-ID')} suara (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            animateScale: true
                        }
                    }
                });
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
