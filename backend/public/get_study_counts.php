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
$stmt = $conn->prepare('SELECT 
    SUM(CASE WHEN Status = "new" THEN 1 ELSE 0 END) AS new,
    SUM(CASE WHEN Status = "learning" THEN 1 ELSE 0 END) AS learning,
    SUM(CASE WHEN Status = "review" THEN 1 ELSE 0 END) AS review
    FROM Cards WHERE DeckID = ?');
$stmt->bind_param('i', $deckId);
$stmt->execute();
$result = $stmt->get_result();
$counts = $result->fetch_assoc();

$stmt->close();
$conn->close();

echo json_encode(['status' => 'success', 'counts' => $counts]);
?>