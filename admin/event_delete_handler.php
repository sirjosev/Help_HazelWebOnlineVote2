<?php
// File: admin/event_delete_handler.php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "Akses ditolak.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    
    // Logika konfirmasi nama event sudah ditangani di sisi client (JS),
    // di sini kita langsung proses penghapusan.
    // Di aplikasi production, validasi ganda di server sangat disarankan.

    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        // Karena ada ON DELETE CASCADE di database,
        // semua kandidat dan vote terkait akan terhapus otomatis.
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>