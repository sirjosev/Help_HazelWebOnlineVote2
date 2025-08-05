<?php
// File: auth/register.php
// Halaman ini berisi formulir HTML untuk pendaftaran pengguna baru.
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - E-Voting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-12">

    <div class="w-full max-w-lg bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Daftar sebagai Voter</h2>
        <p class="text-center text-gray-500 mb-8">Buat akun untuk menggunakan hak pilih Anda.</p>

        <?php
        // Tampilkan pesan error atau sukses jika ada
        if (isset($_SESSION['error_message'])) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">';
            echo '<span class="block sm:inline">' . htmlspecialchars($_SESSION['error_message']) . '</span>';
            echo '</div>';
            unset($_SESSION['error_message']);
        }
        if (isset($_SESSION['success_message'])) {
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">';
            echo '<span class="block sm:inline">' . htmlspecialchars($_SESSION['success_message']) . '</span>';
            echo '</div>';
            unset($_SESSION['success_message']);
        }
        ?>

        <form action="register_handler.php" method="POST">
            <div class="mb-4">
                <label for="nama_lengkap" class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" required
                       class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Masukkan nama lengkap Anda">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Alamat Email</label>
                <input type="email" id="email" name="email" required
                       class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="contoh@email.com">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required
                       class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Buat password yang kuat">
            </div>
            <div class="mb-6">
                <label for="domisili" class="block text-gray-700 text-sm font-bold mb-2">Domisili (Kota/Kabupaten)</label>
                <input type="text" id="domisili" name="domisili" required
                       class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Contoh: Jakarta, Surabaya, Bandung">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition-colors duration-300">
                    Daftar
                </button>
            </div>
            <p class="text-center text-gray-500 text-sm mt-6">
                Sudah punya akun? <a href="login.php" class="font-bold text-indigo-600 hover:text-indigo-800">Masuk di sini</a>
            </p>
        </form>
    </div>

</body>
</html>
