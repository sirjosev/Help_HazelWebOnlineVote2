<?php
require_once 'config/database.php';

// Ambil event terbaru yang sedang atau akan berlangsung
$event_sql = "
    SELECT id, nama_event, posisi_jabatan, deskripsi
    FROM events
    WHERE waktu_selesai > NOW()
    ORDER BY waktu_mulai ASC
    LIMIT 1
";
$event_result = $conn->query($event_sql);
$event = $event_result->fetch_assoc();

$candidates = [];
if ($event) {
    // Ambil kandidat untuk event tersebut
    $candidates_sql = "SELECT nama_kandidat, foto_kandidat, visi, misi FROM candidates WHERE event_id = ? ORDER BY nomor_urut ASC";
    $stmt = $conn->prepare($candidates_sql);
    $stmt->bind_param("i", $event['id']);
    $stmt->execute();
    $candidates_result = $stmt->get_result();
    while ($row = $candidates_result->fetch_assoc()) {
        $candidates[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Voting - Selamat Datang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f0f2f5; }
    </style>
</head>
<body>
    <!-- Tombol Aksi di Pojok Kanan Atas -->
    <div class="absolute top-0 right-0 p-4 md:p-6 z-10 flex space-x-2">
        <a href="quick_count.php" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-sm md:text-base transition-colors duration-300 shadow-md">
            Lihat Quick Count
        </a>
        <a href="auth/login.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm md:text-base transition-colors duration-300 shadow-md">
            Login
        </a>
    </div>

    <div class="container mx-auto p-4 md:p-8">
        <!-- Header -->
        <header class="text-center mb-10 pt-16 md:pt-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800">Sistem E-Voting</h1>
            <p class="text-lg text-gray-600 mt-2">Kenali Kandidat Anda Sebelum Memilih</p>
        </header>

        <!-- Konten Utama: Visi & Misi Kandidat -->
        <main>
            <?php if ($event && !empty($candidates)): ?>
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-gray-200">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 text-center"><?php echo htmlspecialchars($event['nama_event']); ?></h2>
                    <p class="text-md text-gray-600 text-center mb-8"><?php echo htmlspecialchars($event['posisi_jabatan']); ?></p>

                    <div class="grid grid-cols-1 lg:grid-cols-<?php echo count($candidates) > 1 ? '2' : '1'; ?> gap-8">
                        <?php foreach ($candidates as $candidate): ?>
                            <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow duration-300">
                                <div class="flex items-center space-x-4 mb-4">
                                    <img src="<?php echo $base_url . 'assets/images/' . htmlspecialchars($candidate['foto_kandidat'] ?: 'placeholder.png'); ?>" alt="Foto <?php echo htmlspecialchars($candidate['nama_kandidat']); ?>" class="w-20 h-20 rounded-full object-cover border-2 border-indigo-200">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($candidate['nama_kandidat']); ?></h3>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <h4 class="font-semibold text-lg text-gray-700 border-b pb-1 mb-2">Visi</h4>
                                        <p class="text-gray-600 text-sm"><?php echo nl2br(htmlspecialchars($candidate['visi'] ?? 'Visi belum tersedia.')); ?></p>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-lg text-gray-700 border-b pb-1 mb-2">Misi</h4>
                                        <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
                                            <?php
                                            if (!empty($candidate['misi'])) {
                                                $misi_items = explode(',', htmlspecialchars($candidate['misi']));
                                                foreach ($misi_items as $item) {
                                                    echo '<li>' . trim($item) . '</li>';
                                                }
                                            } else {
                                                echo '<li>Misi belum tersedia.</li>';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center bg-white p-10 rounded-2xl shadow-lg">
                    <h2 class="text-2xl font-bold text-gray-700">Belum Ada Event Pemilihan Aktif</h2>
                    <p class="text-gray-500 mt-2">Saat ini belum ada event pemilihan yang akan datang atau sedang berlangsung. Silakan cek kembali nanti.</p>
                </div>
            <?php endif; ?>
        </main>

        <!-- Footer -->
        <footer class="text-center mt-12 text-gray-500">
            <p>&copy; 2024 Proyek E-Voting. Dibuat dengan semangat transparansi.</p>
        </footer>
    </div>
</body>
</html>
