<?php
// File: admin/event_edit_handler.php
// Memproses data dari formulir edit event dan memperbarui database.

session_start();
require_once '../config/database.php';

// --- Security & Input Check ---
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php");
    exit;
}

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    // Pengguna tidak login atau bukan admin
    header("Location: ../auth/login.php");
    exit;
}

// --- Ambil Data dari Form ---
$event_id = $_POST['event_id'];
$nama_event = $_POST['nama_event'];
$posisi_jabatan = $_POST['posisi_jabatan'];
$wilayah = $_POST['wilayah'];
$deskripsi = $_POST['deskripsi'];
$waktu_mulai = $_POST['waktu_mulai'];
$waktu_selesai = $_POST['waktu_selesai'];

// --- Validasi Sederhana ---
if (empty($event_id) || empty($nama_event) || empty($posisi_jabatan) || empty($wilayah) || empty($waktu_mulai) || empty($waktu_selesai)) {
    $_SESSION['error_message'] = "Semua kolom wajib diisi.";
    // Arahkan kembali ke halaman edit dengan ID yang benar
    header("Location: event_edit.php?id=" . $event_id);
    exit;
}

// Cek validitas tanggal
if (strtotime($waktu_mulai) >= strtotime($waktu_selesai)) {
    $_SESSION['error_message'] = "Waktu selesai harus setelah waktu mulai.";
    header("Location: event_edit.php?id=" . $event_id);
    exit;
}

// --- Query Update Database ---
$sql = "UPDATE events
        SET nama_event = ?,
            posisi_jabatan = ?,
            wilayah = ?,
            deskripsi = ?,
            waktu_mulai = ?,
            waktu_selesai = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    // Handle error, misalnya log ke file
    error_log("Prepare statement failed: " . $conn->error);
    $_SESSION['error_message'] = "Terjadi kesalahan pada sistem. Silakan coba lagi.";
    header("Location: event_edit.php?id=" . $event_id);
    exit;
}

// Bind parameter ke statement
$stmt->bind_param("ssssssi",
    $nama_event,
    $posisi_jabatan,
    $wilayah,
    $deskripsi,
    $waktu_mulai,
    $waktu_selesai,
    $event_id
);

// Eksekusi statement
if ($stmt->execute()) {
    $_SESSION['success_message'] = "Event berhasil diperbarui!";
    header("Location: dashboard.php");
} else {
    $_SESSION['error_message'] = "Gagal memperbarui event. Silakan coba lagi.";
    header("Location: event_edit.php?id=" . $event_id);
}

$stmt->close();
$conn->close();
exit;
?>
