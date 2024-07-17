<?php
session_start();
require '../config/functions.php';
$conn = get_db_connection();

$data = json_decode(file_get_contents('php://input'), true);

$deckId = $data['deckId'];
$question = $data['question'];
$answer = $data['answer'];

$sql = "INSERT INTO Cards (DeckID, Front, Back) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iss', $deckId, $question, $answer);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Flashcard added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add flashcard']);
}

$stmt->close();
$conn->close();
?>
