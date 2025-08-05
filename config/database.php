<?php
// File: config/database.php

// --- Konfigurasi Database ---
// Sesuaikan variabel ini dengan pengaturan XAMPP Anda.
$db_host = 'localhost';     // Biasanya 'localhost' untuk XAMPP
$db_user = 'root';          // User default MySQL di XAMPP
$db_pass = '';              // Password default MySQL di XAMPP adalah kosong
$db_name = 'db_evoting';    // Nama database yang telah kita buat

// --- Membuat Koneksi ---
// Membuat koneksi ke database menggunakan MySQLi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// --- Cek Koneksi ---
// Jika koneksi gagal, hentikan skrip dan tampilkan pesan error.
if ($conn->connect_error) {
    // die() akan menghentikan eksekusi skrip sepenuhnya.
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Set charset ke utf8mb4 untuk mendukung berbagai karakter
$conn->set_charset("utf8mb4");

?>
