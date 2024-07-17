<?php
session_start();
include '../config/functions.php';
$conn = get_db_connection();

$sql = "
    SELECT 
        sd.DeckID, 
        sd.Title, 
        sd.SharedDate, 
        sd.Rating, 
        COUNT(c.CardID) AS DeckCount, 
        sd.Description 
    FROM 
        SharedDecks sd 
    LEFT JOIN 
        Cards c ON sd.DeckID = c.DeckID 
    GROUP BY 
        sd.DeckID, sd.Title, sd.SharedDate, sd.Rating, sd.Description
    ORDER BY 
        sd.SharedDate DESC
";

$result = $conn->query($sql);
$sharedDecks = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['status' => 'success', 'data' => $sharedDecks]);
?>