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
            <form action="event_edit_handler.php" method="POST">
                <!-- Hidden input for event ID -->
                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">

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
                        <input type="text" name="wilayah" id="wilayah" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2" value="<?php echo htmlspecialchars($event['wilayah']); ?>">
                    </div>
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Singkat</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"><?php echo htmlspecialchars($event['deskripsi']); ?></textarea>
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
        </div>
    </main>
</body>
</html>
<?php
$conn->close();
?>
