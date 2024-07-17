<?php
session_start();
include '../config/functions.php';
$conn = get_db_connection();
$userId = $_SESSION['user_id']; // Assuming user ID is stored in session

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
    WHERE 
        sd.UserID = ?
    GROUP BY 
        sd.DeckID, sd.Title, sd.SharedDate, sd.Rating, sd.Description
    ORDER BY 
        sd.SharedDate DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$sharedDecks = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['status' => 'success', 'data' => $sharedDecks]);
?>