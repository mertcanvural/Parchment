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

if (!$deckId) {
    echo json_encode(['status' => 'error', 'message' => 'Deck ID is required']);
    exit();
}

$conn = get_db_connection();
$stmt = $conn->prepare("DELETE FROM Decks WHERE DeckID = ?");
$stmt->bind_param('i', $deckId);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Deck deleted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete deck']);
}

$stmt->close();
$conn->close();
?>