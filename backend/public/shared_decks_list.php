<?php
// shared_decks_list.php
// This file retrieves the decks in a specific category and returns the data as a JSON response.

require_once '../config/db.php'; // Ensure this points to your database connection script
require_once '../config/functions.php';

header('Content-Type: application/json');

$categoryId = $_GET['categoryId'] ?? null;

if (!$categoryId) {
    echo json_encode(['status' => 'error', 'message' => 'Category ID is required']);
    exit();
}

$conn = get_db_connection();

$query = "
    SELECT d.DeckID, d.DeckName, d.Rating, d.ModifiedDate, COUNT(c.CardID) AS TotalCards
    FROM Decks d
    LEFT JOIN Cards c ON d.DeckID = c.DeckID
    JOIN DeckCategories dc ON d.DeckID = dc.DeckID
    WHERE dc.CategoryID = ?
    GROUP BY d.DeckID, d.DeckName, d.Rating, d.ModifiedDate
";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $categoryId);
$stmt->execute();
$result = $stmt->get_result();

$decks = [];
while ($row = $result->fetch_assoc()) {
    $decks[] = $row;
}

echo json_encode(['status' => 'success', 'decks' => $decks]);

$stmt->close();
$conn->close();
?>
