<?php
// File: admin/candidate_edit.php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$candidate_id = $_GET['id'] ?? 0;
if ($candidate_id === 0) {
    header("Location: dashboard.php"); // Redirect if no candidate ID
    exit;
}

// Fetch candidate data
$stmt = $conn->prepare("SELECT * FROM candidates WHERE id = ?");
$stmt->bind_param("i", $candidate_id);
$stmt->execute();
$result = $stmt->get_result();
$candidate = $result->fetch_assoc();
$stmt->close();

if (!$candidate) {
    echo "Kandidat tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kandidat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/style.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <h1 class="text-xl font-bold text-indigo-600">Edit Kandidat</h1>
                <a href="manage_candidates.php?event_id=<?php echo $candidate['event_id']; ?>" class="text-sm font-medium text-gray-600 hover:text-indigo-600">&larr; Kembali ke Manajemen Kandidat</a>
            </div>
        </div>
    </nav>
    <main class="max-w-2xl mx-auto py-8 px-4">
        <div class="bg-white p-8 rounded-2xl shadow-lg">
            <form action="candidate_edit_handler.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="candidate_id" value="<?php echo $candidate['id']; ?>">
                <input type="hidden" name="event_id" value="<?php echo $candidate['event_id']; ?>">
                <input type="hidden" name="current_photo" value="<?php echo htmlspecialchars($candidate['foto_kandidat']); ?>">

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="nama_kandidat" class="block text-sm font-medium text-gray-700">Nama Lengkap Kandidat</label>
                        <input type="text" name="nama_kandidat" id="nama_kandidat" value="<?php echo htmlspecialchars($candidate['nama_kandidat']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
                    </div>
                    <div>
                        <label for="partai_asal" class="block text-sm font-medium text-gray-700">Partai Asal (Opsional)</label>
                        <input type="text" name="partai_asal" id="partai_asal" value="<?php echo htmlspecialchars($candidate['partai_asal']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
                    </div>
                    <div>
                        <label for="nomor_urut" class="block text-sm font-medium text-gray-700">Nomor Urut</label>
                        <input type="number" name="nomor_urut" id="nomor_urut" value="<?php echo $candidate['nomor_urut']; ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
                    </div>
                     <div>
                        <label for="visi" class="block text-sm font-medium text-gray-700">Visi</label>
                        <textarea name="visi" id="visi" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2"><?php echo htmlspecialchars($candidate['visi'] ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label for="misi" class="block text-sm font-medium text-gray-700">Misi (pisahkan dengan koma)</label>
                        <textarea name="misi" id="misi" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2"><?php echo htmlspecialchars($candidate['misi'] ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label for="materi_kampanye" class="block text-sm font-medium text-gray-700">Materi Kampanye (URL/Link)</label>
                        <input type="text" name="materi_kampanye" id="materi_kampanye" value="<?php echo htmlspecialchars($candidate['materi_kampanye'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto Saat Ini</label>
                        <img src="../asset/images/<?php echo htmlspecialchars($candidate['foto_kandidat'] ?: 'placeholder.png'); ?>" alt="Foto saat ini" class="mt-2 h-24 w-auto rounded-md">
                    </div>
                    <div>
                        <label for="foto_kandidat" class="block text-sm font-medium text-gray-700">Ganti Foto (Opsional)</label>
                        <input type="file" name="foto_kandidat" id="foto_kandidat" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti foto.</p>
                    </div>
                </div>
                <div class="mt-8 text-right">
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg">Update Kandidat</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
<?php $conn->close(); ?>
