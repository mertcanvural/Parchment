<?php
// signup.php
// This file handles the user signup process. It validates the provided username, email, and password, 
// checks for duplicates, and inserts the new user into the database.

require_once '../config/db.php'; // Ensure this points to your database connection script
require_once '../config/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'] ?? null;
$email = $data['email'] ?? null;
$password = $data['password'] ?? null;

if (!$username || !$email || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

$conn = get_db_connection();

// Check if the username or email already exists
$query = "SELECT * FROM Users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Username or email already exists']);
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert the new user into the database
$query = "INSERT INTO Users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $username, $email, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create user']);
}

$stmt->close();
$conn->close();
?>