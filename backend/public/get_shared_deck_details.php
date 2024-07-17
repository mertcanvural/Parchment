<?php
require '../config/db.php';
require '../config/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$deckId = $_GET['deckId'] ?? null;

if (!$deckId) {
    echo json_encode(['status' => 'error', 'message' => 'Deck ID is required']);
    exit();
}

$conn = get_db_connection();
$stmt = $conn->prepare('
    SELECT 
        d.DeckID, 
        sd.Title, 
        sd.Description, 
        sd.Rating, 
        d.DeckName
    FROM 
        Decks d
    JOIN 
        SharedDecks sd ON d.DeckID = sd.DeckID
    WHERE 
        d.DeckID = ?
');
$stmt->bind_param('i', $deckId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $deckDetails = [
        'Title' => $row['Title'],
        'Description' => $row['Description'],
        'Rating' => $row['Rating'],
        'SampleCards' => []
    ];

    // Fetch sample cards (up to 10)
    $sampleStmt = $conn->prepare('SELECT Front, Back FROM Cards WHERE DeckID = ? LIMIT 10');
    $sampleStmt->bind_param('i', $deckId);
    $sampleStmt->execute();
    $sampleResult = $sampleStmt->get_result();

    while ($card = $sampleResult->fetch_assoc()) {
        $deckDetails['SampleCards'][] = $card;
    }

    echo json_encode(['status' => 'success', 'data' => $deckDetails]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Deck not found']);
}

$stmt->close();
$conn->close();
?>