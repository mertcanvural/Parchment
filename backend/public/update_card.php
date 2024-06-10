<?php
require 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['cardId']) || !isset($data['front']) || !isset($data['back'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit();
}

$cardId = $data['cardId'];
$front = $data['front'];
$back = $data['back'];

$conn = get_db_connection();

$stmt = $conn->prepare("UPDATE Cards SET Front = ?, Back = ?, ModifiedDate = NOW() WHERE CardID = ?");
$stmt->bind_param('ssi', $front, $back, $cardId);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Card updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update card']);
}

$stmt->close();
$conn->close();
?>