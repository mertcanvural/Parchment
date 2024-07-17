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

// Get new cards count
$newCardsQuery = "SELECT COUNT(*) AS newCards FROM Cards WHERE DeckID = ? AND Status = 'new'";
$stmt = $conn->prepare($newCardsQuery);
$stmt->bind_param('i', $deckId);
$stmt->execute();
$newCardsResult = $stmt->get_result()->fetch_assoc();
$newCardsCount = $newCardsResult['newCards'];

// Get learning cards count
$learningCardsQuery = "SELECT COUNT(*) AS learningCards FROM Cards WHERE DeckID = ? AND Status = 'learning'";
$stmt = $conn->prepare($learningCardsQuery);
$stmt->bind_param('i', $deckId);
$stmt->execute();
$learningCardsResult = $stmt->get_result()->fetch_assoc();
$learningCardsCount = $learningCardsResult['learningCards'];

// Get review cards count
$reviewCardsQuery = "SELECT COUNT(*) AS reviewCards FROM Cards WHERE DeckID = ? AND Status = 'to review'";
$stmt = $conn->prepare($reviewCardsQuery);
$stmt->bind_param('i', $deckId);
$stmt->execute();
$reviewCardsResult = $stmt->get_result()->fetch_assoc();
$reviewCardsCount = $reviewCardsResult['reviewCards'];

// Get total cards count
$totalCardsQuery = "SELECT COUNT(*) AS totalCards FROM Cards WHERE DeckID = ?";
$stmt = $conn->prepare($totalCardsQuery);
$stmt->bind_param('i', $deckId);
$stmt->execute();
$totalCardsResult = $stmt->get_result()->fetch_assoc();
$totalCardsCount = $totalCardsResult['totalCards'];

$response = [
    'status' => 'success',
    'newCards' => $newCardsCount,
    'learningCards' => $learningCardsCount,
    'reviewCards' => $reviewCardsCount,
    'totalCards' => $totalCardsCount
];

echo json_encode($response);

$stmt->close();
$conn->close();
?>