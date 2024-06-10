<?php
require 'db.php';
require 'functions.php';

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
$stmt = $conn->prepare("SELECT CardID, Front, Back FROM Cards WHERE DeckID = ?");
$stmt->bind_param("i", $deckId);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $cards = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['status' => 'success', 'cards' => $cards]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch cards']);
}

$stmt->close();
$conn->close();
?>