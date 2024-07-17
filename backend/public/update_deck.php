<?php
session_start();
require '../config/functions.php';
$conn = get_db_connection();

$deckId = $_POST['deckId'];
$deckName = $_POST['deckName'];
$description = $_POST['description'];
$cards = json_decode($_POST['cards'], true);

$sql = "UPDATE Decks SET DeckName = ?, Description = ? WHERE DeckID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssi', $deckName, $description, $deckId);
$stmt->execute();
$stmt->close();

foreach ($cards as $card) {
    $sql = "UPDATE Cards SET Front = ?, Back = ? WHERE CardID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $card['Front'], $card['Back'], $card['CardID']);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
echo json_encode(['status' => 'success']);
?>
