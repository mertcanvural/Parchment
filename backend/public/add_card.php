<?php
require 'db.php';
require 'functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$deckId = $data['deckId'] ?? null;
$front = $data['front'] ?? null;
$back = $data['back'] ?? null;

if (!$deckId || !$front || !$back) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

$conn = get_db_connection();
$stmt = $conn->prepare("INSERT INTO Cards (DeckID, Front, Back) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $deckId, $front, $back);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Card added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add card']);
}

$stmt->close();
$conn->close();
?>