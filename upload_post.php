<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to upload a post. <a href='login.php'>Login here</a>";
    exit();
}

$servername = "localhost";
$username = "root"; // ganti dengan username DB kamu
$password = ""; // ganti dengan password DB kamu
$dbname = "odni_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil nama uploader dari session

    $caption = $_POST['caption'];

    // Handle upload foto
    $photo = $_FILES['photo']['name'];
    $target_dir = "uploads/post/";
    $target_file = $target_dir . basename($photo);

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
        // Simpan data ke database dengan user_id dari session
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO posts (user_id, caption, photo) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $caption, $target_file);

        if ($stmt->execute()) {
            echo "Post uploaded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    header("Location: index.php");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odni App</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
    <div class="profile-container">
        <div class="profile-card">
            <br><br>
        <h2>Gimana hari ini?</h2>
        <br>
            <form action="upload_post.php" method="POST" enctype="multipart/form-data">
            <img id="image-preview" src="" alt="Image Preview">
            <input type="file" id="image" name="photo" accept="image/*" onchange="previewImage(event)"><br>
            <textarea name="caption" placeholder="Ketik kata-kata mutiara disini..."></textarea><br>
            <a href="index.php"><button class="button">Gajadi</button></a>
            <button class="button" type="submit">Kirim</button>
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
                const imagePreview = document.getElementById('image-preview');
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