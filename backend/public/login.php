<?php
// login.php
require 'db.php';
require 'functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'] ?? null;
$password = $data['password'] ?? null;

if (!$username || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'Username and password are required']);
    exit();
}

$conn = get_db_connection();
$stmt = $conn->prepare("SELECT Userid, password FROM Users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit();
}

$user = $result->fetch_assoc();

if ($password !== $user['password']) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
    exit();
}

$_SESSION['user_id'] = $user['Userid'];

echo json_encode(['status' => 'success']);
?>