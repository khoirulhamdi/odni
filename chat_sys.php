<?php
// Database Connection
$dsn = 'mysql:host=localhost;dbname=odni_app';
$username = 'root';
$password = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

$pdo = new PDO($dsn, $username, $password, $options);

session_start();
if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to access the chat.');
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['message'])) {
        $message = trim($_POST['message']);
        $image_url = null;

        if (!empty($_FILES['image']['name'])) {
            $upload_dir = 'uploads/post/';
            $image_name = time() . '_' . basename($_FILES['image']['name']);
            $target_file = $upload_dir . $image_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_url = $target_file;
            }
        }

        $stmt = $pdo->prepare("INSERT INTO public_chat (user_id, message, image_url) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $message, $image_url]);
        echo json_encode(['status' => 'success']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT pc.*, u.username FROM public_chat pc JOIN users u ON pc.user_id = u.id ORDER BY pc.created_at ASC");
    $chats = $stmt->fetchAll();
    echo json_encode($chats);
    exit;
}
?>
