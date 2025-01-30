<?php
session_start();
include 'includes/functions.php';

$login_error = false; // Variabel untuk menandai error login

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = login($username, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        $login_error = true; // Tandai login gagal
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="container">
        <h2>Odni App</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Masuk</button>
            <p>Blm punya akun kah? <a href="register.php"> Daftar</a> dulu dong. </p>
        </form>
    </div>

    <!-- SweetAlert Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if ($login_error): ?>
        Swal.fire({
            icon: 'error',
            title: 'Lu Gagal!',
            text: 'Ulang sono 😂',
            confirmButtonText: 'Heeh Siap'
        });
        <?php endif; ?>
    </script>
</body>
</html>
