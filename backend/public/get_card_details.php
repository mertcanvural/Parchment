<?php
require '../config/db.php';
require '../config/functions.php';

header('Content-Type: application/json');

$cardId = $_GET['cardId'] ?? null;

if (!$cardId) {
    echo json_encode(['status' => 'error', 'message' => 'Card ID is required']);
    exit();
}

$conn = get_db_connection();
$stmt = $conn->prepare('SELECT c.CardID, c.Front, c.Back, d.DeckName FROM Cards c JOIN Decks d ON c.DeckID = d.DeckID WHERE c.CardID = ?');
$stmt->bind_param('i', $cardId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['status' => 'success', 'card' => $row]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Card not found']);
}

$stmt->close();
$conn->close();
?>