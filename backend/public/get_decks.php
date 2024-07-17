<?php
require '../config/db.php';
require '../config/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = getUserId();
$conn = get_db_connection();
$stmt = $conn->prepare('SELECT DeckID, DeckName FROM Decks WHERE UserID = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$decks = [];
while ($row = $result->fetch_assoc()) {
    $decks[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode(['status' => 'success', 'decks' => $decks]);
?>