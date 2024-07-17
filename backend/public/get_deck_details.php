<?php
require '../config/db.php';
require '../config/functions.php';

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
$stmt = $conn->prepare('
    SELECT 
        d.DeckID, 
        d.DeckName, 
        COUNT(c.CardID) AS TotalCards,
        SUM(CASE WHEN c.Status = "new" THEN 1 ELSE 0 END) AS NewCards,
        SUM(CASE WHEN c.Status = "learning" THEN 1 ELSE 0 END) AS LearningCards,
        SUM(CASE WHEN c.Status = "to review" THEN 1 ELSE 0 END) AS ToReviewCards
    FROM 
        Decks d
    LEFT JOIN 
        Cards c ON d.DeckID = c.DeckID
    WHERE 
        d.DeckID = ?
    GROUP BY 
        d.DeckID, d.DeckName
');
$stmt->bind_param('i', $deckId);  // Corrected the bind_param type
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['status' => 'success', 'deck' => $row]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Deck not found']);
}

$stmt->close();
$conn->close();
?>