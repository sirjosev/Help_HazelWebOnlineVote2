<?php
// File: admin/candidate_edit_handler.php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php");
    exit;
}

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil semua data dari form
$candidate_id = $_POST['candidate_id'];
$event_id = $_POST['event_id']; // Penting untuk redirect kembali
$nama_kandidat = $_POST['nama_kandidat'];
$partai_asal = $_POST['partai_asal'];
$nomor_urut = $_POST['nomor_urut'];
$visi = $_POST['visi'] ?? '';
$misi = $_POST['misi'] ?? '';
$materi_kampanye = $_POST['materi_kampanye'] ?? '';
$current_photo = $_POST['current_photo'];
$foto_kandidat_name = $current_photo; // Default ke foto yang sudah ada

// --- Logika Upload Foto Baru ---
if (isset($_FILES['foto_kandidat']) && $_FILES['foto_kandidat']['error'] == 0) {
    $target_dir = "../assets/images/";

    // Hapus foto lama jika bukan placeholder
    if ($current_photo !== 'placeholder.png' && file_exists($target_dir . $current_photo)) {
        unlink($target_dir . $current_photo);
    }

    // Buat nama file unik baru dan upload
    $foto_kandidat_name = uniqid() . '-' . basename($_FILES["foto_kandidat"]["name"]);
    $target_file = $target_dir . $foto_kandidat_name;

    if (!move_uploaded_file($_FILES["foto_kandidat"]["tmp_name"], $target_file)) {
        // Jika upload gagal, kembali gunakan foto lama (atau default jika tidak ada)
        $foto_kandidat_name = $current_photo;
    }
}

// --- Update Database ---
$sql = "UPDATE candidates SET nama_kandidat = ?, partai_asal = ?, nomor_urut = ?, foto_kandidat = ?, visi = ?, misi = ?, materi_kampanye = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssissssi", $nama_kandidat, $partai_asal, $nomor_urut, $foto_kandidat_name, $visi, $misi, $materi_kampanye, $candidate_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION['success_message'] = "Kandidat berhasil diperbarui.";
} else {
    // Tidak ada perubahan atau ada error
    // Bisa ditambahkan pengecekan error $stmt->error jika perlu
    $_SESSION['info_message'] = "Tidak ada perubahan yang disimpan.";
}

$stmt->close();
$conn->close();

// Redirect kembali ke halaman manajemen kandidat
header("Location: manage_candidates.php?event_id=" . $event_id);
exit;
?>
