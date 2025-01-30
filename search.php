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
            <h1>Search</h1>
            <div class="topnav-container">
            <a href="upload_post.php" class="material-icons"><i class="fas fa-plus"></i></a>
            <a href="profile.php">
                <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar">
            </a>
            </div>
    </div>

    <form method="GET" action="search.php" class="search-form">
    <input type="text" name="query" placeholder="Teangan didieu...." required>
    <button type="submit"><i class="fas fa-search"></i></button>
    </form>

<div class="search-results">

    <?php
    if (isset($_GET['query'])) {
        $query = $_GET['query'];


        // Query untuk mencari username dan caption
        $sql = "
            SELECT u.username, p.caption 
            FROM users u
            LEFT JOIN posts p ON u.id = p.user_id
            WHERE u.username LIKE '%$query%' OR p.caption LIKE '%$query%'";
        
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="search-card">';
                echo '<div class="search-card-header">' . htmlspecialchars($row['username']) . '</div>';
                echo '<div class="search-card-body">' . htmlspecialchars($row['caption']) . '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>Gaada, gausa nyari yang gaada.</p>';
        }

        $conn->close();
    }
    ?>
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
            <span class="material-icons active"><i class="fas fa-search"></i></span>
            <span>Search</span>
        </a>
    </nav>
</body>
</html>