<?php
session_start();
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (register($username, $email, $password)) {
        header("Location: login.php");
        exit();
    } else {
        echo "Registration failed!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="container">
        <h2>Daftar</h2>
        <form method="POST">
        <input type="text" name="email" placeholder="Nama" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
        <p>Udah punya akun? <a href="login.php">Masuk</a> dulu.</p>
    </form>
    </div>
</body>
</html>