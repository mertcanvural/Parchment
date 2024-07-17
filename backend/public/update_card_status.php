<?php
session_start();
require '../config/db.php';
require '../config/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$cardId = $data['cardId'] ?? null;
$action = $data['action'] ?? null;
$deckId = $data['deckId'] ?? null;

if (!$cardId || !$action || !$deckId) {
    echo json_encode(['status' => 'error', 'message' => 'Card ID, action, and deck ID are required']);
    exit();
}

$conn = get_db_connection();
$today = date('Y-m-d');

$stmt = $conn->prepare('SELECT * FROM Cards WHERE CardID = ?');
$stmt->bind_param('i', $cardId);
$stmt->execute();
$result = $stmt->get_result();
$card = $result->fetch_assoc();

if (!$card) {
    echo json_encode(['status' => 'error', 'message' => 'Card not found']);
    exit();
}

$newStatus = $card['Status'];
$newReviewDate = $card['ReviewDate'];
$easyCount = $card['EasyCount'];

if ($action === 'hard') {
    $easyCount = 0;
} elseif ($action === 'easy') {
    $easyCount++;
    if ($easyCount >= 2) {
        $newStatus = 'to review';
        $newReviewDate = date('Y-m-d', strtotime($today . ' +1 day'));
        $easyCount = 0;
    } elseif ($card['Status'] === 'new') {
        $newStatus = 'learning';
    }
}

$stmt = $conn->prepare('UPDATE Cards SET Status = ?, ReviewDate = ?, EasyCount = ? WHERE CardID = ?');
$stmt->bind_param('ssii', $newStatus, $newReviewDate, $easyCount, $cardId);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>