<?php
// login_create.php
// This file handles the user registration process. It validates the provided username, email, and password, checks for duplicates, and then inserts the new user into the database.

require_once '../config/db.php'; // Ensure this points to your database connection script

header('Content-Type: application/json');

// Check if all required fields are provided
if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

// Check if the username or email already exists
$conn = get_db_connection();
$query = "SELECT * FROM Users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Username or email already exists']);
    exit();
}

// Insert the new user into the database
$query = "INSERT INTO Users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('sss', $username, $email, $password);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Registration successful']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Registration failed']);
}

$stmt->close();
$conn->close();
?>