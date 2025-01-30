<?php
session_start();
include 'includes/connect.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data pengguna yang sedang login
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT avatar FROM users WHERE id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Tangani request like
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_post_id'])) {
    $post_id = intval($_POST['like_post_id']);

    // Update jumlah like di database
    $sql_like = "UPDATE posts SET likes = likes + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql_like);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

    // Redirect untuk mencegah refresh ulang form
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Query untuk mengambil postingan
$sql = "SELECT posts.id, posts.upload_time, posts.photo, posts.caption, users.username, users.avatar 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.upload_time DESC";

$result = $conn->query($sql);
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
    <div class="topnav">
        <h1>Odni App</h1>
        <div class="topnav-container">
            <a href="upload_post.php" class="material-icons"><i class="fas fa-plus"></i></a>
            <!-- Tampilkan gambar avatar pengguna yang sedang login -->
            <a href="profile.php">
                <img src="<?php echo htmlspecialchars($user['avatar'] ?? 'https://via.placeholder.com/40'); ?>" alt="Avatar">
            </a>
        </div>
    </div>                                               

    <div class="container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='post' data-post-id='" . $row['id'] . "'>
                    <div class='header'>
                        <img src='" . htmlspecialchars($row['avatar'] ?? 'https://via.placeholder.com/40') . "' alt='User Avatar' style='width: 40px; height: 40px; border-radius: 50%;'>
                        <span class='username'>" . htmlspecialchars($row['username']) . "</span>
                    </div>
                    <img src='" . $row['photo'] . "' alt='Post Image' class='image'>
                    <div class='caption'>
                        " . htmlspecialchars($row['caption']) . " </br>
                        <span style='font-size: 8px; color: gray; text-align: right;'>" . htmlspecialchars($row['upload_time']) . "</span>
                    </div>
                    <div class='reactions'>
                        <div class='like-container'>
                        <button class='like-button' data-post-id='" . $row['id'] . "'><i class='fas fa-heart' style='color: " . ($row['likes'] > 0 ? 'red' : 'gray') . ";'></i></button>
                        <span class='like-count'>" . htmlspecialchars($row['likes']) . "</span>
                        </div>
                        <button class='comment-button'><i class='fas fa-message'></i> Hujatan</button>
                        </div>
                    </div>";
        }
    } else {
        echo "<p style='text-align: center; padding-top: 10px; color: gray;'>Ora ada postingan didieu.</p>";
    }
    ?>
</div>

    <nav class="navbar">
        <a href="index.php">
            <span class="material-icons active"><i class="fas fa-home"></i></span>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js"></script>

</body>
</html>
