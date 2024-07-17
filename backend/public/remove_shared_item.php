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

// Validate if the user owns the shared deck
$userId = $_SESSION['user_id']; // Assuming user ID is stored in session
$stmt = $conn->prepare("SELECT SharedDeckID FROM SharedDecks WHERE DeckID = ? AND UserID = ?");
$stmt->bind_param("ii", $deckId, $userId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Shared deck not found or you do not have permission to remove it']);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();

// Delete related records from userratings table
$stmt = $conn->prepare("DELETE FROM UserRatings WHERE DeckID = ?");
$stmt->bind_param("i", $deckId);
$stmt->execute();
$stmt->close();

// Remove the deck from SharedDecks
$stmt = $conn->prepare("DELETE FROM SharedDecks WHERE DeckID = ? AND UserID = ?");
$stmt->bind_param("ii", $deckId, $userId);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Shared deck removed successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to remove shared deck']);
}

$stmt->close();
$conn->close();
?>