<?php
// File: voter/submit_vote.php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'voter') {
    http_response_code(403); exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = intval($_POST['event_id']);
    $candidate_id = intval($_POST['candidate_id']);
    $voter_id = $_SESSION['user_id'];

    // --- VALIDASI GANDA DI SERVER ---
    // 1. Cek apakah sudah pernah vote
    $check_sql = "SELECT id FROM votes WHERE event_id = ? AND voter_id = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("ii", $event_id, $voter_id);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        header("Location: dashboard.php"); // Sudah vote, redirect
        exit;
    }
    $stmt_check->close();

    // 2. Masukkan suara
    // Kunci UNIK di database (event_id, voter_id) akan menjadi lapisan keamanan terakhir
    $insert_sql = "INSERT INTO votes (event_id, candidate_id, voter_id) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_sql);
    $stmt_insert->bind_param("iii", $event_id, $candidate_id, $voter_id);
    
    if ($stmt_insert->execute()) {
        // Berhasil
        header("Location: dashboard.php");
    } else {
        // Gagal (kemungkinan karena race condition yang dihentikan oleh UNIQUE key)
        echo "Terjadi kesalahan atau Anda sudah pernah memberikan suara.";
        // Mungkin redirect ke halaman error
    }
    $stmt_insert->close();
    $conn->close();
}
?>