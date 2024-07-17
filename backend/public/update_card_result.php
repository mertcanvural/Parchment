<?php
// update_card_result.php
// This file updates the result of a card study session based on the user's feedback (easy or hard).
// It updates the card status and review date accordingly.

require_once '../config/db.php'; // Ensure this points to your database connection script

$data = json_decode(file_get_contents('php://input'), true);
$cardId = $data['cardId'] ?? null;
$result = $data['result'] ?? null;

if (!$cardId || !$result) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit();
}

$conn = get_db_connection();

if ($result === 'easy') {
    $newReviewDate = date("Y-m-d", strtotime("+1 day"));
    $stmt = $conn->prepare('UPDATE Cards SET Status = "learning", ReviewDate = ? WHERE CardID = ?');
    $stmt->bind_param('si', $newReviewDate, $cardId);
} else if ($result === 'hard') {
    $stmt = $conn->prepare('UPDATE Cards SET Status = "review" WHERE CardID = ?');
    $stmt->bind_param('i', $cardId);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid result value']);
    exit();
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update card']);
}

$stmt->close();
$conn->close();
?>