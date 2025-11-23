<?php
$host = 'localhost';
$db   = 'pizza_shop';
$user = 'root';
$pass = ''; // Ganti jika Anda menggunakan password MySQL

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk membuat hash password (Hanya digunakan sekali untuk setup admin)
// function hash_password($password) {
//     return password_hash($password, PASSWORD_DEFAULT);
// }
?>