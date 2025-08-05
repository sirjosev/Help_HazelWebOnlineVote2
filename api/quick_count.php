<?php
// File: api/quick_count.php

// Header untuk memberitahu browser bahwa outputnya adalah JSON
header('Content-Type: application/json');
require_once '../config/database.php';

$output = [];

// Ambil semua event yang sedang berlangsung atau sudah selesai
$events_sql = "SELECT id, nama_event, posisi_jabatan FROM events WHERE waktu_selesai > NOW() - INTERVAL 1 DAY ORDER BY created_at DESC";
$events_result = $conn->query($events_sql);

if ($events_result->num_rows > 0) {
    while ($event = $events_result->fetch_assoc()) {
        $event_data = [
            'eventName' => $event['nama_event'],
            'position' => $event['posisi_jabatan'],
            'candidates' => []
        ];

        // Untuk setiap event, ambil kandidat dan jumlah suaranya
        $candidates_sql = "
            SELECT c.id, c.nama_kandidat, c.foto_kandidat, COUNT(v.id) AS votes
            FROM candidates c
            LEFT JOIN votes v ON c.id = v.candidate_id
            WHERE c.event_id = ?
            GROUP BY c.id
            ORDER BY c.nomor_urut ASC
        ";
        
        $stmt = $conn->prepare($candidates_sql);
        $stmt->bind_param("i", $event['id']);
        $stmt->execute();
        $candidates_result = $stmt->get_result();

        while ($candidate = $candidates_result->fetch_assoc()) {
            $event_data['candidates'][] = [
                'name' => $candidate['nama_kandidat'],
                'votes' => (int)$candidate['votes'],
                'image' => $candidate['foto_kandidat']
            ];
        }
        $stmt->close();
        
        $output[] = $event_data;
    }
}

$conn->close();

// Cetak output sebagai JSON
echo json_encode($output, JSON_PRETTY_PRINT);

?>
