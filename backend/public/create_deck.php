<?php
// This file handles the creation of a new deck in the database.
// It checks if the user is logged in using a helper function, retrieves the deck name from the request,
// and inserts the deck into the Decks table. It returns a JSON response indicating
// success or failure.

require_once '../config/db.php';
require_once '../config/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$deckName = $data['deckName'] ?? null;

if (!$deckName) {
    echo json_encode(['status' => 'error', 'message' => 'Deck name is required']);
    exit();
}

$user_id = getUserId();
$createdDate = date('Y-m-d H:i:s');
$modifiedDate = $createdDate;

$conn = get_db_connection();
$stmt = $conn->prepare("INSERT INTO Decks (DeckName, UserID, CreatedDate, ModifiedDate) VALUES (?, ?, ?, ?)");
$stmt->bind_param('siss', $deckName, $user_id, $createdDate, $modifiedDate);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Deck created successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create deck']);
}

$stmt->close();
$conn->close();
?>