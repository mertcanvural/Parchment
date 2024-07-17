<?php
session_start();
require '../config/functions.php';
$conn = get_db_connection();

$deckId = $_GET['deckId'];
$sql = "
    SELECT d.DeckName, d.Description, c.CardID, c.Front, c.Back
    FROM Decks d
    JOIN Cards c ON d.DeckID = c.DeckID
    WHERE d.DeckID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $deckId);
$stmt->execute();
$result = $stmt->get_result();

$deck = null;
$cards = [];
while ($row = $result->fetch_assoc()) {
    if ($deck === null) {
        $deck = [
            'DeckName' => $row['DeckName'],
            'Description' => $row['Description'],
            'Cards' => []
        ];
    }
    $deck['Cards'][] = [
        'CardID' => $row['CardID'],
        'Front' => $row['Front'],
        'Back' => $row['Back']
    ];
}

echo json_encode(['status' => 'success', 'data' => $deck]);

$stmt->close();
$conn->close();
?>
