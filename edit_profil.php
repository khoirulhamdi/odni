<?php
session_start();
include 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $bio = $_POST['bio'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $avatar = $_FILES['avatar'];
    $target_dir = "uploads/avatar/";
    $target_file = $target_dir . uniqid() . "_" . basename($avatar['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    // Validasi format file
    if (!empty($avatar['name']) && !in_array($imageFileType, $allowedTypes)) {
        echo "Format file tidak valid. Hanya JPG, JPEG, PNG, dan GIF yang diizinkan.";
        exit();
    }

    // Update username dan bio dan email
    $stmt = $pdo->prepare("UPDATE users SET username = ?, bio = ?, email = ? WHERE id = ?");
    $stmt->execute([$username, $bio, $email, $user_id]);

    // Update password jika diisi
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $user_id]);
    }

    // Update avatar
    if (!empty($avatar['name']) && move_uploaded_file($avatar['tmp_name'], $target_file)) {
        $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        $stmt->execute([$target_file, $user_id]);
    }

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="profile-container">
            <div class="profile-card" style="width: 350px;">
                <h2>Edit Profile</h2>
                <form method="POST" enctype="multipart/form-data">
                <img id="avatar-preview" src="" alt="Image Preview">
                    <input type="file" id="image" name="avatar" accept="image/*" onchange="previewImage(event)">
                    <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    <input type="text" name="email" placeholder="Nama" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    <input type="password" name="password" placeholder="Ganti Kata Sandi">
                    <textarea maxlength="30" name="bio" rows="2" placeholder="Bio"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                    <br>
                    <br>
                    <a href="profile.php"><button class="button">Gajadi</button></a>
                    <button class="button" type="submit">Simpen</button>
                </form>
            </div>
        </div>
    </div>

    <nav class="navbar">
        <a href="index.php">
            <span class="material-icons"><i class="fas fa-home"></i></span>
            <span>Post</span>
        </a>
        <a href="chat.php">
            <span class="material-icons"><i class="fas fa-comment"></i></span>
            <span>Chat</span>
        </a>
        <a href="group.php">
            <span class="material-icons"><i class="fas fa-users"></i></span>
            <span>Group</span>
        </a>
        <a href="search.php">
            <span class="material-icons"><i class="fas fa-search"></i></span>
            <span>Search</span>
        </a>
    </nav>
    <script>
        // Fungsi untuk menampilkan preview gambar
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            // Ketika file sudah dibaca
            reader.onload = function() {
                const imagePreview = document.getElementById('avatar-preview');
                imagePreview.src = reader.result;
                imagePreview.style.display = 'block';  // Menampilkan preview gambar
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
