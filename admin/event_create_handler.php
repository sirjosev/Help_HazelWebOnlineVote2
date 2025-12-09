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
    $banner_event = null; // Default null jika tidak ada banner

    // Handle banner file upload
    if (isset($_FILES['banner_event']) && $_FILES['banner_event']['error'] == 0) {
        $target_dir = "../asset/images/banners/";
        
        // Buat folder jika belum ada
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Validasi tipe file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['banner_event']['type'];
        
        if (in_array($file_type, $allowed_types)) {
            // Validasi ukuran (max 5MB)
            if ($_FILES['banner_event']['size'] <= 5 * 1024 * 1024) {
                // Buat nama file unik
                $extension = pathinfo($_FILES['banner_event']['name'], PATHINFO_EXTENSION);
                $banner_event = 'banner_' . uniqid() . '.' . $extension;
                $target_file = $target_dir . $banner_event;
                
                // Pindahkan file
                if (!move_uploaded_file($_FILES['banner_event']['tmp_name'], $target_file)) {
                    $banner_event = null; // Reset jika gagal upload
                }
            }
        }
    }

    // Query dengan banner_event
    $sql = "INSERT INTO events (nama_event, posisi_jabatan, wilayah, deskripsi, waktu_mulai, waktu_selesai, created_by, banner_event) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $nama_event, $posisi_jabatan, $wilayah, $deskripsi, $waktu_mulai, $waktu_selesai, $created_by, $banner_event);

    if ($stmt->execute()) {
        header("Location: dashboard.php?success=1");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>