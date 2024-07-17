<?php
require '../config/db.php';
require '../config/functions.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$cardId = $data['cardId'] ?? null;
$front = $data['front'] ?? null;
$back = $data['back'] ?? null;

if (!$cardId || !$front || !$back) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

$conn = get_db_connection();
$stmt = $conn->prepare('UPDATE Cards SET Front = ?, Back = ?, ModifiedDate = NOW() WHERE CardID = ?');
$stmt->bind_param('ssi', $front, $back, $cardId);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Card updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update card']);
}

$stmt->close();
$conn->close();
?>