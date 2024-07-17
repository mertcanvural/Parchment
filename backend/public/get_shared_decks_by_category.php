<?php
session_start();
include '../config/functions.php';
$conn = get_db_connection();

$category = $_GET['category'];
$sql = "
    SELECT 
        sd.DeckID, 
        sd.Title, 
        sd.SharedDate, 
        sd.Rating, 
        COUNT(c.CardID) AS DeckCount, 
        sd.Description,
        sd.SharedDate AS ModifiedDate
    FROM 
        SharedDecks sd 
    LEFT JOIN 
        Cards c ON sd.DeckID = c.DeckID 
    WHERE
        sd.CategoryID = (
            SELECT CategoryID FROM Categories WHERE CategoryName = ?
        )
    GROUP BY 
        sd.DeckID, sd.Title, sd.SharedDate, sd.Rating, sd.Description
    ORDER BY 
        sd.SharedDate DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $category);
$stmt->execute();
$result = $stmt->get_result();
$sharedDecks = $result->fetch_all(MYSQLI_ASSOC);

if ($sharedDecks) {
    echo json_encode(['status' => 'success', 'decks' => $sharedDecks]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No decks found']);
}
?>