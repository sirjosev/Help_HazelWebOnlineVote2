<?php
// File: admin/manage_candidates.php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Dapatkan ID event dari URL
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
if ($event_id === 0) {
    header("Location: dashboard.php");
    exit;
}

// Ambil detail event
$event_sql = "SELECT nama_event, posisi_jabatan FROM events WHERE id = ?";
$stmt_event = $conn->prepare($event_sql);
$stmt_event->bind_param("i", $event_id);
$stmt_event->execute();
$event_result = $stmt_event->get_result();
$event = $event_result->fetch_assoc();
$stmt_event->close();

if (!$event) {
    echo "Event tidak ditemukan.";
    exit;
}

// Ambil daftar kandidat untuk event ini
$candidates_sql = "SELECT * FROM candidates WHERE event_id = ? ORDER BY nomor_urut ASC";
$stmt_candidates = $conn->prepare($candidates_sql);
$stmt_candidates->bind_param("i", $event_id);
$stmt_candidates->execute();
$candidates_result = $stmt_candidates->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kandidat - <?php echo htmlspecialchars($event['nama_event']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/style.css">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <h1 class="text-xl font-bold text-indigo-600">Kelola Kandidat</h1>
                <a href="dashboard.php" class="text-sm font-medium text-gray-600 hover:text-indigo-600">&larr; Kembali ke Dashboard</a>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($event['nama_event']); ?></h2>
        <p class="text-md text-gray-600 mb-6"><?php echo htmlspecialchars($event['posisi_jabatan']); ?></p>

        <!-- Form Tambah Kandidat -->
        <div class="bg-white p-6 rounded-2xl shadow-lg mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Tambah Kandidat Baru</h3>
            <form action="candidate_add_handler.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input name="nama_kandidat" required class="border p-2 rounded-md" placeholder="Nama Lengkap Kandidat">
                    <input name="partai_asal" class="border p-2 rounded-md" placeholder="Partai Asal (Opsional)">
                    <input type="number" name="nomor_urut" required class="border p-2 rounded-md" placeholder="No. Urut">
                </div>
                <div class="mt-4 grid grid-cols-1 gap-4">
                    <textarea name="visi" class="border p-2 rounded-md" placeholder="Visi Kandidat"></textarea>
                    <textarea name="misi" class="border p-2 rounded-md" placeholder="Misi Kandidat (pisahkan dengan koma)"></textarea>
                    <textarea name="materi_kampanye" class="border p-2 rounded-md" placeholder="Materi Kampanye (URL/Link)"></textarea>
                </div>
                <div class="mt-4">
                     <label for="foto" class="block text-sm font-medium text-gray-700">Foto Kandidat</label>
                     <input type="file" name="foto_kandidat" id="foto" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <div class="mt-4 text-right">
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg">Tambah</button>
                </div>
            </form>
        </div>

        <!-- Daftar Kandidat -->
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Foto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Partai</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if ($candidates_result->num_rows > 0): ?>
                        <?php while($candidate = $candidates_result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-6 py-4 text-lg font-bold text-gray-700"><?php echo $candidate['nomor_urut']; ?></td>
                                <td class="px-6 py-4">
                                    <img src="<?php echo $base_url . 'assets/images/' . htmlspecialchars($candidate['foto_kandidat'] ?: 'placeholder.png'); ?>" alt="Foto" class="h-12 w-12 rounded-full object-cover">
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900"><?php echo htmlspecialchars($candidate['nama_kandidat']); ?></td>
                                <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($candidate['partai_asal']); ?></td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <a href="candidate_edit.php?id=<?php echo $candidate['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <a href="candidate_delete_handler.php?id=<?php echo $candidate['id']; ?>&event_id=<?php echo $event_id; ?>" onclick="return confirm('Anda yakin ingin menghapus kandidat ini?')" class="text-red-600 hover:text-red-900">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4 text-gray-500">Belum ada kandidat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
<?php $stmt_candidates->close(); $conn->close(); ?>
