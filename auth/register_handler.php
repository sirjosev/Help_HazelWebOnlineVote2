<?php
// File: auth/register_handler.php
// Memproses data pendaftaran dari formulir register.php

session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $domisili = $_POST['domisili'];

    // --- Validasi Sederhana ---
    if (empty($nama_lengkap) || empty($email) || empty($password) || empty($domisili)) {
        $_SESSION['error_message'] = "Semua field wajib diisi.";
        header("Location: register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Format email tidak valid.";
        header("Location: register.php");
        exit();
    }

    // --- Cek apakah email sudah terdaftar ---
    $sql_check = "SELECT id FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $_SESSION['error_message'] = "Email sudah terdaftar. Silakan gunakan email lain atau login.";
        header("Location: register.php");
        exit();
    }
    $stmt_check->close();

    // --- Hash Password ---
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // --- Simpan Pengguna Baru ke Database ---
    // Atur peran secara eksplisit sebagai 'voter' untuk semua pendaftar baru
    $role = 'voter';

    $sql_insert = "INSERT INTO users (nama_lengkap, email, password, role, domisili) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);

    if ($stmt_insert === false) {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        $_SESSION['error_message'] = "Terjadi kesalahan pada sistem. Coba lagi nanti.";
        header("Location: register.php");
        exit();
    }

    $stmt_insert->bind_param("sssss", $nama_lengkap, $email, $hashed_password, $role, $domisili);

    if ($stmt_insert->execute()) {
        // Pendaftaran berhasil
        $_SESSION['success_message'] = "Pendaftaran sebagai voter berhasil! Silakan login.";
        header("Location: login.php"); // Arahkan ke halaman login setelah sukses
        exit();
    } else {
        error_log("Execute failed: (" . $stmt_insert->errno . ") " . $stmt_insert->error);
        $_SESSION['error_message'] = "Pendaftaran gagal karena masalah teknis. Silakan coba lagi.";
        header("Location: register.php");
        exit();
    }

    $stmt_insert->close();
    $conn->close();

} else {
    // Jika bukan request POST, kembalikan ke halaman utama
    header("Location: ../index.php");
    exit();
}
?>
