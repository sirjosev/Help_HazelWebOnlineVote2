<?php
// File: admin/candidate_add_handler.php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $nama_kandidat = $_POST['nama_kandidat'];
    $partai_asal = $_POST['partai_asal'];
    $nomor_urut = $_POST['nomor_urut'];
    $visi = $_POST['visi'] ?? '';
    $misi = $_POST['misi'] ?? '';
    $materi_kampanye = $_POST['materi_kampanye'] ?? '';
    $foto_kandidat_name = 'placeholder.png'; // Default image

    // Handle file upload
    if (isset($_FILES['foto_kandidat']) && $_FILES['foto_kandidat']['error'] == 0) {
        $target_dir = "../assets/images/";
        // Buat nama file unik untuk mencegah penimpaan
        $foto_kandidat_name = uniqid() . '-' . basename($_FILES["foto_kandidat"]["name"]);
        $target_file = $target_dir . $foto_kandidat_name;
        
        // Pindahkan file yang di-upload ke direktori tujuan
        if (!move_uploaded_file($_FILES["foto_kandidat"]["tmp_name"], $target_file)) {
            // Jika gagal, gunakan gambar default
            $foto_kandidat_name = 'placeholder.png';
        }
    }

    $sql = "INSERT INTO candidates (event_id, nama_kandidat, partai_asal, nomor_urut, foto_kandidat, visi, misi, materi_kampanye) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ississss", $event_id, $nama_kandidat, $partai_asal, $nomor_urut, $foto_kandidat_name, $visi, $misi, $materi_kampanye);
    
    $stmt->execute();
    $stmt->close();
    $conn->close();
    
    header("Location: manage_candidates.php?event_id=" . $event_id);
}
?>
