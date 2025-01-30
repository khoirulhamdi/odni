<?php
session_start();
include 'includes/connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

if (!$post_id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid post ID']);
    exit();
}

// Cek apakah user sudah like post ini
$sql_check = "SELECT * FROM post_likes WHERE user_id = ? AND post_id = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ii", $user_id, $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Jika sudah like, hapus like
    $sql_unlike = "DELETE FROM post_likes WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($sql_unlike);
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();

    // Kurangi jumlah like di tabel posts
    $sql_decrease_like = "UPDATE posts SET likes = likes - 1 WHERE id = ?";
    $stmt = $conn->prepare($sql_decrease_like);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'action' => 'unlike']);
} else {
    // Jika belum like, tambahkan like
    $sql_like = "INSERT INTO post_likes (user_id, post_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql_like);
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();

    // Tambahkan jumlah like di tabel posts
    $sql_increase_like = "UPDATE posts SET likes = likes + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql_increase_like);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'action' => 'like']);
}

$conn->close();
?>
