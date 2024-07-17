<?php
require_once '../config/db.php';
require_once '../config/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$deckId = $_GET['deckId'] ?? null;

if (!$deckId) {
    echo json_encode(['status' => 'error', 'message' => 'Deck ID is required']);
    exit();
}

$conn = get_db_connection();
$stmt = $conn->prepare("SELECT DeckName, Description FROM Decks WHERE DeckID = ?");
$stmt->bind_param('i', $deckId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $deck = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'deck' => $deck]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Deck not found']);
}

$stmt->close();
$conn->close();
?>