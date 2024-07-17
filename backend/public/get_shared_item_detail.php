<?php
require '../config/db.php';
require '../config/functions.php';

header('Content-Type: application/json');

$deckId = $_GET['deckId'] ?? null;

if (!$deckId) {
    echo json_encode(['status' => 'error', 'message' => 'Deck ID is required']);
    exit();
}

$conn = get_db_connection();
$stmt = $conn->prepare('SELECT sd.Title, sd.Description, c.Front, c.Back FROM SharedDecks sd LEFT JOIN Cards c ON sd.DeckID = c.DeckID WHERE sd.DeckID = ?');
$stmt->bind_param('i', $deckId);
$stmt->execute();
$result = $stmt->get_result();

$deck = [];
while ($row = $result->fetch_assoc()) {
    $deck['Title'] = $row['Title'];
    $deck['Description'] = $row['Description'];
    $deck['Cards'][] = ['Front' => $row['Front'], 'Back' => $row['Back']];
}

$stmt->close();
$conn->close();

echo json_encode(['status' => 'success', 'deck' => $deck]);
?>