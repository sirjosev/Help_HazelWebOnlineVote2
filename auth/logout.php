<?php
// File: auth/logout.php
// Menghancurkan session dan mengalihkan pengguna ke landing page.

// Selalu mulai session di awal
session_start();

// Hapus semua variabel session
$_SESSION = array();

// Hancurkan cookie session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Terakhir, hancurkan session.
session_destroy();

// Arahkan pengguna kembali ke halaman utama (landing page)
// Menggunakan path absolut dari root web server untuk kejelasan
$root_path = '/'; // Sesuaikan jika aplikasi berada di subdirektori
header("Location: " . $root_path . "index.php");
exit;
?>
