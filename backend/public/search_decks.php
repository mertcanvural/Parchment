<?php
require '../config/db.php';
require '../config/functions.php';

header('Content-Type: application/json');

$query = $_GET['query'] ?? null;

if (!$query) {
    echo json_encode(['status' => 'error', 'message' => 'Keyword is required']);
    exit();
}

$conn = get_db_connection();
$stmt = $conn->prepare("SELECT d.DeckID, d.DeckName, c.CardID, c.Front, c.Back FROM Decks d JOIN Cards c ON d.DeckID = c.DeckID WHERE c.Front LIKE ? OR c.Back LIKE ?");
$searchQuery = '%' . $query . '%';
$stmt->bind_param('ss', $searchQuery, $searchQuery);
$stmt->execute();
$result = $stmt->get_result();

$decks = [];
while ($row = $result->fetch_assoc()) {
    $decks[] = $row;
}

if (count($decks) > 0) {
    echo json_encode(['status' => 'success', 'decks' => $decks]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No decks found']);
}

$stmt->close();
$conn->close();
?>