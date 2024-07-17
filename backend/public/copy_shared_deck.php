<?php
session_start();
include '../config/functions.php';
$conn = get_db_connection();

if (!isset($_GET['deckId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Deck ID is required']);
    exit;
}

$deckId = intval($_GET['deckId']);
$userId = $_SESSION['user_id']; // Assuming user ID is stored in session

// Check if the user is trying to copy their own deck
$stmt = $conn->prepare("SELECT UserID FROM SharedDecks WHERE DeckID = ?");
$stmt->bind_param("i", $deckId);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($deckOwnerId);
$stmt->fetch();
$stmt->close();

if ($deckOwnerId == $userId) {
    echo json_encode(['status' => 'error', 'message' => 'You cannot copy your own deck']);
    exit;
}

// Copy the deck details
$stmt = $conn->prepare("
    INSERT INTO Decks (UserID, Title, Description, CategoryID, CreatedDate)
    SELECT ?, Title, Description, CategoryID, NOW()
    FROM SharedDecks
    WHERE DeckID = ?");
$stmt->bind_param("ii", $userId, $deckId);

if ($stmt->execute()) {
    $newDeckId = $stmt->insert_id;
    $stmt->close();

    // Copy the cards associated with the deck
    $stmt = $conn->prepare("
        INSERT INTO Cards (DeckID, Text, Extra, Tags)
        SELECT ?, Text, Extra, Tags
        FROM Cards
        WHERE DeckID = ?");
    $stmt->bind_param("ii", $newDeckId, $deckId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Deck copied to your local decks.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to copy cards']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to copy deck']);
}

$stmt->close();
$conn->close();
?>