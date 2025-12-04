<?php
// File: auth/login.php
// Halaman ini berisi formulir HTML untuk login.

// Mulai session untuk bisa menampilkan pesan error dari login_handler.php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Voting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/style.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Selamat Datang</h2>
        <p class="text-center text-gray-500 mb-8">Silakan masuk ke akun Anda</p>

        <?php
        // Tampilkan pesan error jika ada (dikirim dari login_handler.php)
        if (isset($_SESSION['error_message'])) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">';
            echo '<span class="block sm:inline">' . $_SESSION['error_message'] . '</span>';
            echo '</div>';
            // Hapus pesan error dari session setelah ditampilkan
            unset($_SESSION['error_message']);
        }
        ?>

        <form action="login_handler.php" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Alamat Email</label>
                <input type="email" id="email" name="email" required
                       class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="contoh@email.com">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required
                       class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="******************">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition-colors duration-300">
                    Masuk
                </button>
            </div>
            <p class="text-center text-gray-500 text-sm mt-6">
                Belum punya akun? <a href="register.php" class="font-bold text-indigo-600 hover:text-indigo-800">Daftar di sini</a>
            </p>
        </form>
    </div>

</body>
</html>
