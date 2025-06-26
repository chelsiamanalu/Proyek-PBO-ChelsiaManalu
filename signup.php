<?php
// koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "user_auth"; // Pastikan database ini sudah kamu buat di phpMyAdmin

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// ambil data dari form
$email = $_POST['email'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // amankan password

// cek apakah email sudah digunakan
$check = $conn->prepare("SELECT * FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "Email sudah digunakan. Silakan <a href='Plushy sign in.html'>login</a>.";
} else {
    // simpan data
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        header("Location: Plushy beranda.html"); // arahkan ke halaman utama
        exit;
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }
}

$conn->close();
?>
