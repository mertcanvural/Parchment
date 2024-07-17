<?php
session_start();
require '../config/db.php';
require '../config/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$deckId = $_GET['deckId'] ?? null;
$limit = $_GET['limit'] ?? 20;

if (!$deckId) {
    echo json_encode(['status' => 'error', 'message' => 'Deck ID is required']);
    exit();
}

$conn = get_db_connection();
$today = date('Y-m-d');

// Fetch new, learning, and due to review cards
$stmt = $conn->prepare('SELECT * FROM Cards WHERE DeckID = ? AND (Status IN ("new", "learning") OR (Status = "to review" AND ReviewDate <= ?)) LIMIT ?');
$stmt->bind_param('isi', $deckId, $today, $limit);
$stmt->execute();
$result = $stmt->get_result();
$cards = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['status' => 'success', 'cards' => $cards]);

$stmt->close();
$conn->close();
?>