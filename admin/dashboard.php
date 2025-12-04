<?php
// File: admin/dashboard.php

// Start the session to access logged-in user data
session_start();

// --- Security Check ---
// Check if user is logged in and is an admin.
// If not, redirect to login page.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Include database connection
require_once '../config/database.php';

// Fetch all events from the database to display
// We use a LEFT JOIN to get the admin's name who created the event
$sql = "SELECT events.*, users.nama_lengkap AS admin_name 
        FROM events 
        LEFT JOIN users ON events.created_by = users.id 
        ORDER BY events.created_at DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - E-Voting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/style.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f9fafb; }
        .modal { display: none; }
        .modal.is-open { display: flex; }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <h1 class="text-xl font-bold text-indigo-600">Admin Panel</h1>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-700 mr-4">Halo, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!</span>
                    <a href="../auth/logout.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Manajemen Event Pemilihan</h2>
            <a href="event_create.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300">
                + Buat Event Baru
            </a>
        </div>

        <!-- Events Table -->
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Event</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wilayah</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal Voting</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($event = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($event['nama_event']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($event['posisi_jabatan']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($event['wilayah']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php echo date('d M Y, H:i', strtotime($event['waktu_mulai'])); ?> - <?php echo date('d M Y, H:i', strtotime($event['waktu_selesai'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $now = new DateTime();
                                            $start = new DateTime($event['waktu_mulai']);
                                            $end = new DateTime($event['waktu_selesai']);
                                            if ($now < $start) {
                                                echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Akan Datang</span>';
                                            } elseif ($now >= $start && $now <= $end) {
                                                echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Berlangsung</span>';
                                            } else {
                                                echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Selesai</span>';
                                            }
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="manage_candidates.php?event_id=<?php echo $event['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Kelola Kandidat</a>
                                        <a href="event_edit.php?id=<?php echo $event['id']; ?>" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                        <button onclick="openDeleteModal(<?php echo $event['id']; ?>, '<?php echo htmlspecialchars(addslashes($event['nama_event'])); ?>')" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada event yang dibuat.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full items-center justify-center">
        <div class="relative mx-auto p-8 border w-full max-w-md shadow-lg rounded-2xl bg-white">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-gray-900">Konfirmasi Penghapusan</h3>
                <div class="mt-4 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Untuk menghapus event <strong id="eventNameToDelete" class="text-red-600"></strong>, silakan ketik ulang nama event di bawah ini untuk konfirmasi.
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Tindakan ini tidak dapat diurungkan.</p>
                </div>
                <form id="deleteForm" action="event_delete_handler.php" method="POST" class="px-4">
                    <input type="hidden" name="event_id" id="eventIdInput">
                    <input type="text" id="eventNameConfirmation" class="w-full border rounded-lg p-3 text-center" placeholder="Ketik nama event di sini">
                    <div class="items-center px-4 py-3 mt-4">
                        <button id="confirmDeleteButton" type="submit" class="w-full px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:bg-gray-300 disabled:cursor-not-allowed" disabled>
                            Saya Mengerti, Hapus Event Ini
                        </button>
                    </div>
                </form>
                 <button onclick="closeDeleteModal()" class="mt-2 text-sm text-gray-500 hover:text-gray-700">Batal</button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('deleteModal');
        const eventNameToDeleteEl = document.getElementById('eventNameToDelete');
        const eventIdInput = document.getElementById('eventIdInput');
        const confirmationInput = document.getElementById('eventNameConfirmation');
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        let correctEventName = '';

        function openDeleteModal(id, name) {
            eventIdInput.value = id;
            eventNameToDeleteEl.textContent = name;
            correctEventName = name;
            confirmationInput.value = ''; // Clear input
            confirmDeleteButton.disabled = true; // Disable button
            modal.classList.add('is-open');
        }

        function closeDeleteModal() {
            modal.classList.remove('is-open');
        }

        confirmationInput.addEventListener('input', () => {
            if (confirmationInput.value === correctEventName) {
                confirmDeleteButton.disabled = false;
            } else {
                confirmDeleteButton.disabled = true;
            }
        });

        // Close modal if clicked outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                closeDeleteModal();
            }
        }
    </script>

</body>
</html>
<?php
// Close the database connection
$conn->close();
?>
