<?php
// File: voter/dashboard.php
session_start();

// Set timezone to ensure accurate time comparisons
date_default_timezone_set('Asia/Jakarta');

require_once '../config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'voter') {
    header("Location: ../auth/login.php");
    exit;
}

$voter_id = $_SESSION['user_id'];
$voter_domisili = $_SESSION['domisili'];

// Ambil event yang sesuai dengan domisili voter ATAU event berskala Nasional.
// Juga, ambil status apakah voter sudah pernah vote dan jumlah kandidat.
$sql = "SELECT
            e.*,
            (SELECT COUNT(*) FROM votes v WHERE v.event_id = e.id AND v.voter_id = ?) AS sudah_vote,
            (SELECT COUNT(*) FROM candidates c WHERE c.event_id = e.id) AS jumlah_kandidat
        FROM events e 
        WHERE e.wilayah = ? OR e.wilayah = 'Nasional'
        ORDER BY e.waktu_mulai DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $voter_id, $voter_domisili);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Voter - E-Voting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <h1 class="text-xl font-bold text-indigo-600">Event Pemilihan Anda</h1>
                <div class="flex items-center">
                    <span class="text-gray-700 mr-4">Halo, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!</span>
                    <a href="../auth/logout.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    
    <main class="max-w-4xl mx-auto py-8 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php if ($result->num_rows > 0): ?>
                <?php while($event = $result->fetch_assoc()): ?>
                    <?php
                        $now = new DateTime();
                        $start = new DateTime($event['waktu_mulai']);
                        $end = new DateTime($event['waktu_selesai']);
                        $status_voting = '';
                        $status_class = '';
                        $is_active = ($now >= $start && $now <= $end);

                        if ($now < $start) {
                            $status_voting = 'Akan Datang'; $status_class = 'bg-blue-100 text-blue-800';
                        } elseif ($is_active) {
                            $status_voting = 'Sedang Berlangsung'; $status_class = 'bg-green-100 text-green-800';
                        } else {
                            $status_voting = 'Telah Selesai'; $status_class = 'bg-gray-100 text-gray-800';
                        }
                    ?>
                    <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($event['nama_event']); ?></h3>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full <?php echo $status_class; ?>"><?php echo $status_voting; ?></span>
                            </div>
                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($event['wilayah']); ?></p>
                            <p class="text-sm text-gray-500 mt-2"><?php echo date('d M Y', strtotime($event['waktu_mulai'])); ?> - <?php echo date('d M Y', strtotime($event['waktu_selesai'])); ?></p>
                        </div>
                        <div class="mt-4">
                            <?php if ($event['sudah_vote'] > 0): ?>
                                <button disabled class="w-full bg-green-200 text-green-800 font-bold py-2 px-4 rounded-lg cursor-not-allowed">✔ Anda Sudah Memilih</button>
                            <?php elseif (!$is_active): ?>
                                <button disabled class="w-full bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded-lg cursor-not-allowed">Voting Belum/Sudah Selesai</button>
                            <?php elseif ($event['jumlah_kandidat'] == 0): ?>
                                <button disabled class="w-full bg-yellow-200 text-yellow-800 font-bold py-2 px-4 rounded-lg cursor-not-allowed">Kandidat Belum Tersedia</button>
                            <?php else: ?>
                                <a href="event_detail.php?id=<?php echo $event['id']; ?>" class="block text-center w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">Lihat & Pilih Kandidat</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="col-span-full text-center text-gray-500">Tidak ada event pemilihan yang tersedia untuk Anda saat ini.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
<?php $stmt->close(); $conn->close(); ?>
