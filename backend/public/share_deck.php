<?php
session_start();
require '../config/functions.php';
$conn = get_db_connection();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$title = $data['title'] ?? null;
$category = $data['category'] ?? null;
$description = $data['description'] ?? null;
$deckId = $data['deckId'] ?? null;
$userId = $_SESSION['user_id'];

if (!$title || !$category || !$description || !$deckId) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

// Check if the deck is already shared
$sql = "SELECT * FROM SharedDecks WHERE DeckID = ? AND CategoryID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $deckId, $category);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update the shared deck
    $sql = "UPDATE SharedDecks SET Title = ?, Description = ?, SharedDate = NOW() WHERE DeckID = ? AND CategoryID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssii', $title, $description, $deckId, $category);
} else {
    // Insert new shared deck
    $sql = "INSERT INTO SharedDecks (DeckID, Title, Description, UserID, CategoryID, SharedDate, Rating) VALUES (?, ?, ?, ?, ?, NOW(), 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issii', $deckId, $title, $description, $userId, $category);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
