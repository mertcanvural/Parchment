<?php
// get_next_card.php
// This file retrieves the next card to be studied from a specific deck based on the card status and creation date.
// The result is returned as a JSON response.

require_once '../config/db.php';

header('Content-Type: application/json');

$deckId = $_GET['deckId'] ?? null;

if (!$deckId) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid deck ID']);
    exit();
}

$conn = get_db_connection();

$sql = "
    SELECT CardID, Front, Back, Status
    FROM Cards
    WHERE DeckID = ?
    ORDER BY FIELD(Status, 'new', 'review', 'learning'), CreatedDate ASC
    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $deckId);
$stmt->execute();
$result = $stmt->get_result();
$card = $result->fetch_assoc();

if ($card) {
    echo json_encode(['status' => 'success', 'card' => $card]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No cards found']);
}

$stmt->close();
$conn->close();
?>