<?php
// File: voter/event_detail.php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'voter') {
    header("Location: ../auth/login.php");
    exit;
}

$event_id = intval($_GET['id']);
$voter_id = $_SESSION['user_id'];

// Ambil detail event dan cek apakah voter sudah vote & apakah event aktif
$sql_event = "SELECT *, (SELECT COUNT(*) FROM votes WHERE event_id = ? AND voter_id = ?) AS sudah_vote FROM events WHERE id = ?";
$stmt_event = $conn->prepare($sql_event);
$stmt_event->bind_param("iii", $event_id, $voter_id, $event_id);
$stmt_event->execute();
$event = $stmt_event->get_result()->fetch_assoc();
$stmt_event->close();

if (!$event || $event['sudah_vote'] > 0 || new DateTime() < new DateTime($event['waktu_mulai']) || new DateTime() > new DateTime($event['waktu_selesai'])) {
    // Redirect jika event tidak ada, sudah vote, atau waktu tidak sesuai
    header("Location: dashboard.php");
    exit;
}

// Ambil kandidat
$sql_candidates = "SELECT * FROM candidates WHERE event_id = ? ORDER BY nomor_urut";
$stmt_candidates = $conn->prepare($sql_candidates);
$stmt_candidates->bind_param("i", $event_id);
$stmt_candidates->execute();
$candidates = $stmt_candidates->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kandidat - <?php echo htmlspecialchars($event['nama_event']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100">
    <main class="max-w-2xl mx-auto py-8 px-4">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900"><?php echo htmlspecialchars($event['nama_event']); ?></h1>
            <p class="text-gray-600">Silakan pilih salah satu kandidat di bawah ini.</p>
        </div>

        <form action="submit_vote.php" method="POST" onsubmit="return confirm('Apakah Anda yakin dengan pilihan Anda? Pilihan tidak dapat diubah setelah dikirim.');">
            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
            <div class="space-y-4">
                <?php while($candidate = $candidates->fetch_assoc()): ?>
                    <label class="block bg-white p-4 rounded-xl shadow-md cursor-pointer hover:ring-2 hover:ring-indigo-500 transition">
                        <input type="radio" name="candidate_id" value="<?php echo $candidate['id']; ?>" class="hidden peer">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center text-2xl font-bold text-gray-700 peer-checked:bg-indigo-600 peer-checked:text-white">
                                <?php echo $candidate['nomor_urut']; ?>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-semibold text-gray-900 peer-checked:text-indigo-700"><?php echo htmlspecialchars($candidate['nama_kandidat']); ?></p>
                                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($candidate['partai_asal']); ?></p>
                            </div>
                            <div class="ml-auto">
                                <img src="../assets/images/<?php echo htmlspecialchars($candidate['foto_kandidat'] ?: 'placeholder.png'); ?>" class="h-16 w-16 rounded-full object-cover">
                            </div>
                        </div>
                    </label>
                <?php endwhile; ?>
            </div>
            <div class="mt-8">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg text-lg">Kirim Pilihan Saya</button>
            </div>
        </form>
    </main>
</body>
</html>
<?php $stmt_candidates->close(); $conn->close(); ?>