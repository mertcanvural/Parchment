<?php
session_start();
require_once '../config/db.php';
require_once '../config/functions.php';

$conn = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['deckId']) && isset($data['rating'])) {
        $deckId = intval($data['deckId']);
        $rating = intval($data['rating']);

        $sql = "UPDATE SharedDecks SET Ratings = Ratings + ? WHERE DeckID = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $rating, $deckId);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Rating updated.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update rating.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}

$conn->close();
?>
