<?php
// File: admin/event_create_handler.php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "Akses ditolak.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_event = $_POST['nama_event'];
    $posisi_jabatan = $_POST['posisi_jabatan'];
    $wilayah = $_POST['wilayah'];
    $deskripsi = $_POST['deskripsi'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_selesai = $_POST['waktu_selesai'];
    $created_by = $_SESSION['user_id'];

    $sql = "INSERT INTO events (nama_event, posisi_jabatan, wilayah, deskripsi, waktu_mulai, waktu_selesai, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $nama_event, $posisi_jabatan, $wilayah, $deskripsi, $waktu_mulai, $waktu_selesai, $created_by);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>