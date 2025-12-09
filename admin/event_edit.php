<?php
// File: admin/event_edit.php
// Halaman formulir untuk mengedit event yang sudah ada.

session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../config/database.php';
require_once '../config/options.php'; // Memuat opsi dropdown

// 1. Get Event ID from URL
$event_id = $_GET['id'] ?? null;
if (!$event_id) {
    // Redirect if no ID is provided
    header("Location: dashboard.php");
    exit;
}

// 2. Fetch Event Data from Database
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

// Redirect if event not found
if (!$event) {
    header("Location: dashboard.php");
    exit;
}

// Format datetime-local values
$waktu_mulai = date('Y-m-d\TH:i', strtotime($event['waktu_mulai']));
$waktu_selesai = date('Y-m-d\TH:i', strtotime($event['waktu_selesai']));

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - <?php echo htmlspecialchars($event['nama_event']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/style.css">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <h1 class="text-xl font-bold text-indigo-600">Edit Event</h1>
                <a href="dashboard.php" class="text-sm font-medium text-gray-600 hover:text-indigo-600">&larr; Kembali ke Dashboard</a>
            </div>
        </div>
    </nav>

    <!-- Form Content -->
    <main class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 rounded-2xl shadow-lg">
            <form action="event_edit_handler.php" method="POST" enctype="multipart/form-data">
                <!-- Hidden input for event ID -->
                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                <input type="hidden" name="current_banner" value="<?php echo htmlspecialchars($event['banner_event'] ?? ''); ?>">

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="nama_event" class="block text-sm font-medium text-gray-700">Nama Event</label>
                        <input type="text" name="nama_event" id="nama_event" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2" value="<?php echo htmlspecialchars($event['nama_event']); ?>">
                    </div>
                    <div>
                        <label for="posisi_jabatan" class="block text-sm font-medium text-gray-700">Posisi Jabatan</label>
                        <select name="posisi_jabatan" id="posisi_jabatan" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                            <option value="" disabled>Pilih Posisi Jabatan</option>
                            <?php foreach ($jabatan_options as $jabatan): ?>
                                <option value="<?php echo htmlspecialchars($jabatan); ?>" <?php echo ($event['posisi_jabatan'] === $jabatan) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($jabatan); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="wilayah" class="block text-sm font-medium text-gray-700">Wilayah Pemilihan</label>
                        <select name="wilayah" id="wilayah" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                            <option value="Nasional" <?php echo ($event['wilayah'] === 'Nasional') ? 'selected' : ''; ?>>Nasional (Contoh: Pilpres)</option>
                            <optgroup label="Provinsi">
                                <?php foreach ($provinsi_options as $provinsi): ?>
                                    <option value="<?php echo htmlspecialchars($provinsi); ?>" <?php echo ($event['wilayah'] === $provinsi) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($provinsi); ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Pilih "Nasional" untuk pemilihan tingkat nasional.</p>
                    </div>
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Singkat</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"><?php echo htmlspecialchars($event['deskripsi']); ?></textarea>
                    </div>
                    
                    <!-- Banner Campaign Upload/Edit -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 bg-gray-50">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <svg class="inline-block w-5 h-5 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Banner Kampanye Event
                        </label>
                        
                        <?php if (!empty($event['banner_event'])): ?>
                        <!-- Banner Saat Ini -->
                        <div class="mb-4">
                            <p class="text-xs font-medium text-gray-600 mb-2">Banner Saat Ini:</p>
                            <img src="../asset/images/banners/<?php echo htmlspecialchars($event['banner_event']); ?>" alt="Banner Event" class="w-full max-h-40 object-cover rounded-lg shadow-sm border">
                        </div>
                        <?php endif; ?>
                        
                        <div class="mt-2">
                            <input type="file" name="banner_event" id="banner_event" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                            <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, GIF (Maks. 5MB). Kosongkan jika tidak ingin mengubah banner.</p>
                            <!-- Preview Banner Baru -->
                            <div id="banner-preview" class="mt-4 hidden">
                                <p class="text-xs font-medium text-green-600 mb-2">Preview Banner Baru:</p>
                                <img id="banner-preview-img" src="" alt="Preview Banner" class="w-full max-h-40 object-cover rounded-lg shadow-sm border border-green-300">
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="waktu_mulai" class="block text-sm font-medium text-gray-700">Waktu Mulai Voting</label>
                            <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2" value="<?php echo $waktu_mulai; ?>">
                        </div>
                        <div>
                            <label for="waktu_selesai" class="block text-sm font-medium text-gray-700">Waktu Selesai Voting</label>
                            <input type="datetime-local" name="waktu_selesai" id="waktu_selesai" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2" value="<?php echo $waktu_selesai; ?>">
                        </div>
                    </div>
                </div>
                <div class="mt-8 text-right">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Event
                    </button>
                </div>
            </form>
            
            <script>
            // Preview banner sebelum upload
            document.getElementById('banner_event').addEventListener('change', function(e) {
                const preview = document.getElementById('banner-preview');
                const previewImg = document.getElementById('banner-preview-img');
                const file = e.target.files[0];
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.classList.add('hidden');
                }
            });
            </script>
        </div>
    </main>
</body>
</html>
<?php
$conn->close();
?>
