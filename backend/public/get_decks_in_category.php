<?php
session_start();
include '../config/functions.php';
$conn = get_db_connection();

$categoryId = $_GET['category'];

$sql = "
    SELECT sd.SharedDeckID, d.DeckName AS Title, sd.Rating, sd.SharedDate AS LastModified, COUNT(c.CardID) AS Cards, cat.CategoryName
    FROM Decks d
    JOIN SharedDecks sd ON d.DeckID = sd.DeckID
    LEFT JOIN Cards c ON d.DeckID = c.DeckID
    JOIN Categories cat ON sd.CategoryID = cat.CategoryID
    WHERE cat.CategoryID = ?
    GROUP BY sd.SharedDeckID, d.DeckName, sd.Rating, sd.SharedDate, cat.CategoryName
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $categoryId);
$stmt->execute();
$result = $stmt->get_result();

$decks = [];
$categoryName = '';
while ($row = $result->fetch_assoc()) {
    $decks[] = $row;
    $categoryName = $row['CategoryName'];
}

echo json_encode(['status' => 'success', 'decks' => $decks, 'categoryName' => $categoryName]);

$stmt->close();
$conn->close();
?>