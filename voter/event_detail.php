<?php
// File: voter/event_detail.php
session_start();

// Set timezone to ensure accurate time comparisons
date_default_timezone_set('Asia/Jakarta');

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
    <link rel="stylesheet" href="../asset/css/style.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Styling untuk kandidat yang dipilih */
        .candidate-card {
            transition: all 0.3s ease;
            position: relative;
        }
        
        .candidate-card.selected {
            border-color: #10b981 !important;
            border-width: 3px !important;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%) !important;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.3) !important;
            transform: scale(1.02);
        }
        
        .candidate-card.selected .nomor-urut {
            background-color: #10b981 !important;
            color: white !important;
        }
        
        .candidate-card.selected .candidate-name {
            color: #065f46 !important;
        }
        
        /* Ikon centang */
        .check-icon {
            display: none;
            position: absolute;
            top: -10px;
            right: -10px;
            background: #10b981;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            z-index: 10;
        }
        
        .candidate-card.selected .check-icon {
            display: flex;
            animation: popIn 0.3s ease;
        }
        
        @keyframes popIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        /* Kandidat tidak terpilih menjadi lebih pudar */
        .candidate-card.not-selected {
            opacity: 0.6;
            filter: grayscale(30%);
        }
    </style>
</head>
<body class="bg-gray-100">
    <main class="max-w-2xl mx-auto py-8 px-4">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900"><?php echo htmlspecialchars($event['nama_event']); ?></h1>
            <p class="text-gray-600">Silakan pilih salah satu kandidat di bawah ini.</p>
        </div>

        <form action="submit_vote.php" method="POST" onsubmit="return confirm('Apakah Anda yakin dengan pilihan Anda? Pilihan tidak dapat diubah setelah dikirim.');">
            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
            <div class="space-y-4" id="candidates-container">
                <?php while($candidate = $candidates->fetch_assoc()): ?>
                    <label class="candidate-card block bg-white p-4 rounded-xl shadow-md cursor-pointer border-2 border-gray-200 hover:border-indigo-300 hover:shadow-lg">
                        <!-- Ikon Centang -->
                        <span class="check-icon">✓</span>
                        
                        <input type="radio" name="candidate_id" value="<?php echo $candidate['id']; ?>" required class="hidden candidate-radio">
                        <div class="flex flex-col sm:flex-row items-center text-center sm:text-left">
                            <div class="nomor-urut flex-shrink-0 h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center text-2xl font-bold text-gray-700 mb-4 sm:mb-0 transition-all duration-300">
                                <?php echo $candidate['nomor_urut']; ?>
                            </div>
                            <div class="ml-0 sm:ml-4 flex-grow">
                                <p class="candidate-name text-lg font-semibold text-gray-900 transition-colors duration-300"><?php echo htmlspecialchars($candidate['nama_kandidat']); ?></p>
                                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($candidate['partai_asal'] ?? 'Independen'); ?></p>
                            </div>
                            <div class="ml-0 sm:ml-auto mt-4 sm:mt-0 sm:pl-4 flex-shrink-0">
                                <img src="<?php echo $base_url . 'assets/images/' . htmlspecialchars($candidate['foto_kandidat'] ?: 'placeholder.png'); ?>" alt="Foto <?php echo htmlspecialchars($candidate['nama_kandidat']); ?>" class="w-48 h-auto aspect-[16/9] rounded-lg object-cover shadow-md border">
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <details>
                                <summary class="font-semibold text-indigo-600 cursor-pointer">Lihat Visi, Misi & Materi Kampanye</summary>
                                <div class="mt-2 text-sm text-gray-700 space-y-3">
                                    <div>
                                        <h4 class="font-bold">Visi:</h4>
                                        <p><?php echo nl2br(htmlspecialchars($candidate['visi'] ?? 'Belum ada visi.')); ?></p>
                                    </div>
                                    <div>
                                        <h4 class="font-bold">Misi:</h4>
                                        <ul class="list-disc list-inside">
                                            <?php
                                            $misi_items = !empty($candidate['misi']) ? explode(',', htmlspecialchars($candidate['misi'])) : ['Belum ada misi.'];
                                            foreach ($misi_items as $item) {
                                                echo '<li>' . trim($item) . '</li>';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <?php if (!empty($candidate['materi_kampanye'])): ?>
                                    <div>
                                        <h4 class="font-bold">Materi Kampanye:</h4>
                                        <a href="<?php echo htmlspecialchars($candidate['materi_kampanye']); ?>" target="_blank" class="text-indigo-500 hover:underline">Lihat Materi</a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </details>
                        </div>
                    </label>
                <?php endwhile; ?>
            </div>
            <div class="mt-8">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg text-lg transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed" id="submit-btn">Kirim Pilihan Saya</button>
            </div>
        </form>
        
        <div class="mt-4 text-center">
            <a href="dashboard.php" class="text-gray-500 hover:text-indigo-600 text-sm">&larr; Kembali ke Dashboard</a>
        </div>
    </main>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.candidate-card');
        const radios = document.querySelectorAll('.candidate-radio');
        
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Reset semua kartu
                cards.forEach(card => {
                    card.classList.remove('selected', 'not-selected');
                });
                
                // Tandai yang dipilih dan yang tidak dipilih
                cards.forEach(card => {
                    const cardRadio = card.querySelector('.candidate-radio');
                    if (cardRadio.checked) {
                        card.classList.add('selected');
                    } else {
                        card.classList.add('not-selected');
                    }
                });
            });
        });
    });
    </script>
</body>
</html>
<?php $stmt_candidates->close(); $conn->close(); ?>