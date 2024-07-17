<?php
// This file handles adding a new card to the specified deck in the database.
// It checks if the user is logged in, retrieves the card details from the request, 
// and inserts the card into the Cards table. It returns a JSON response indicating 
// success or failure.

require_once '../config/db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$deckId = $data['deckId'] ?? null;
$question = $data['question'] ?? null;
$answer = $data['answer'] ?? null;

if (!$deckId || !$question || !$answer) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

$conn = get_db_connection();
$stmt = $conn->prepare('INSERT INTO Cards (DeckID, Front, Back) VALUES (?, ?, ?)');
$stmt->bind_param('iss', $deckId, $question, $answer);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Card added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add card']);
}

$stmt->close();
$conn->close();
?>