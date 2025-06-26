<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "user_auth";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

// Ambil data user dari database berdasarkan username
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        header("Location: Plushy beranda.html");
        exit;
    } else {
        // Password salah → tampilkan popup alert
        echo "<script>
                alert('Password salah!');
                window.location.href = 'Plushy sign in.html';
              </script>";
        exit;
    }
} else {
    // Username tidak ditemukan → popup alert
    echo "<script>
            alert('Username tidak ditemukan!');
            window.location.href = 'Plushy sign in.html';
          </script>";
    exit;
}

$conn->close();
?>
