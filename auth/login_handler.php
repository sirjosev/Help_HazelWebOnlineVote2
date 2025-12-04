<?php
// File: auth/login_handler.php
// File ini tidak menampilkan HTML, hanya memproses data login.

// Always start session at the top
session_start();

// Include the database connection file
require_once '../config/database.php';

// Check if the form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // --- Secure Query using Prepared Statements to prevent SQL Injection ---
    $sql = "SELECT id, nama_lengkap, email, password, role, domisili FROM users WHERE email = ?";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind the email parameter
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // User found, fetch the data
        $user = $result->fetch_assoc();

        // Verify the password against the stored hash
        if (password_verify($password, $user['password'])) {
            // Password is correct, login successful
            
            // Store user data in the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['domisili'] = $user['domisili'];
            $_SESSION['logged_in'] = true;

            // Redirect user based on their role
            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
                exit();
            } elseif ($user['role'] === 'voter') {
                header("Location: ../voter/dashboard.php");
                exit();
            }

        } else {
            // Password is not correct
            $_SESSION['error_message'] = "Password yang Anda masukkan salah.";
            header("Location: login.php");
            exit();
        }
    } else {
        // User with that email not found
        $_SESSION['error_message'] = "Tidak ada akun yang terdaftar dengan email tersebut.";
        header("Location: login.php");
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

} else {
    // If not a POST request, redirect to login page
    header("Location: login.php");
    exit();
}
?>