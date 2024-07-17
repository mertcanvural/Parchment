<?php
// rename_deck.php
// This file handles renaming a deck. It requires the user to be logged in and takes a deck ID and a new name as inputs.

require_once '../config/db.php'; // Ensure this points to your database connection script
require_once '../config/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$deckId = $data['deckId'] ?? null;
$newName = $data['newName'] ?? null;

if (!$deckId || !$newName) {
    echo json_encode(['status' => 'error', 'message' => 'Deck ID and new name are required']);
    exit();
}

$conn = get_db_connection();
$stmt = $conn->prepare("UPDATE Decks SET DeckName = ? WHERE DeckID = ?");
$stmt->bind_param('si', $newName, $deckId);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Deck renamed successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to rename deck']);
}

$stmt->close();
$conn->close();
?>