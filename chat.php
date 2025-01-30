<?php
    session_start();
    include 'includes/connect.php';

    // Cek apakah user sudah login
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    $user_id = $_SESSION['user_id'];
    $sql_user = "SELECT avatar FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql_user);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user = $user_result->fetch_assoc();

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
        <div class="topnav">
            <h1>Chat</h1>
            <div class="topnav-container">
            <a href="upload_post.php" class="material-icons"><i class="fas fa-plus"></i></a>
            <!-- Tampilkan gambar avatar pengguna yang sedang login -->
            <a href="profile.php">
                <img src="<?php echo htmlspecialchars($user['avatar'] ?? 'https://via.placeholder.com/40'); ?>" alt="Avatar">
            </a>
            </div>
        </div>
   

    <div class="card-chat">
        <h4><i class="fas fa-home"></i> Masuk ke chat publik</h4>
    </div>
    <div style="text-align: center;">
        <h4 class="card-chat" style="padding: 15px; border-radius: 20px 20px 0 0;">Chat Pribadi</h4>
        <p>Kamingsun...</p>
    </div>

    <nav class="navbar">
        <a href="index.php">
            <span class="material-icons"><i class="fas fa-home"></i></span>
            <span>Post</span>
        </a>
        <a href="chat.php">
            <span class="material-icons active"><i class="fas fa-comment"></i></span>
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
    </div>
</body>
</html>