<?php
session_start();
include '../config/functions.php';

$conn = get_db_connection();

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['deckId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Deck ID is required']);
    exit;
}

$deckId = intval($data['deckId']);

// Validate if the user owns the deck
$userId = $_SESSION['user_id']; // Assuming user ID is stored in session
$stmt = $conn->prepare("SELECT DeckID FROM Decks WHERE DeckID = ? AND UserID = ?");
$stmt->bind_param("ii", $deckId, $userId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Deck not found or you do not have permission to delete it']);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();

// Delete the deck
$stmt = $conn->prepare("DELETE FROM Decks WHERE DeckID = ?");
$stmt->bind_param("i", $deckId);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Deck deleted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete deck']);
}

$stmt->close();
$conn->close();
?>