<?php
// login_read.php
// This file handles the user login process. It validates the provided username/email and password, checks the credentials against the database, and starts a session for the user if the login is successful.

require_once '../config/db.php'; // Ensure this points to your database connection script

session_start();
header('Content-Type: application/json');

// Check if all required fields are provided
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

// Fetch the user from the database
$conn = get_db_connection();
$query = "SELECT * FROM Users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $username, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
    exit();
}

$user = $result->fetch_assoc();

if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    echo json_encode(['status' => 'success', 'message' => 'Login successful']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
}

$stmt->close();
$conn->close();
?>