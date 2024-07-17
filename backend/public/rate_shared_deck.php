<?php
session_start();
include '../config/functions.php';
$conn = get_db_connection();

if (!isset($_GET['deckId']) || !isset($_GET['rating'])) {
    echo json_encode(['status' => 'error', 'message' => 'Deck ID and rating are required']);
    exit;
}

$deckId = intval($_GET['deckId']);
$rating = intval($_GET['rating']); // 1 for like, -1 for dislike
$userId = $_SESSION['user_id']; // Assuming user ID is stored in session

// Check if the user has already rated this deck
$stmt = $conn->prepare("SELECT Rating FROM UserRatings WHERE UserID = ? AND DeckID = ?");
$stmt->bind_param("ii", $userId, $deckId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // If user has already rated, update their rating
    $stmt->bind_result($existingRating);
    $stmt->fetch();
    $stmt->close();

    if ($existingRating == $rating) {
        echo json_encode(['status' => 'error', 'message' => 'You have already given this rating']);
        $conn->close();
        exit;
    }

    // Update the rating
    $stmt = $conn->prepare("UPDATE UserRatings SET Rating = ? WHERE UserID = ? AND DeckID = ?");
    $stmt->bind_param("iii", $rating, $userId, $deckId);

    if ($stmt->execute()) {
        $ratingChange = $rating - $existingRating;
        $updateStmt = $conn->prepare("UPDATE SharedDecks SET Rating = Rating + ? WHERE DeckID = ?");
        $updateStmt->bind_param("ii", $ratingChange, $deckId);
        $updateStmt->execute();
        $updateStmt->close();

        echo json_encode(['status' => 'success', 'message' => 'Rating updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update rating']);
    }

} else {
    $stmt->close();
    // Insert the user's rating
    $stmt = $conn->prepare("INSERT INTO UserRatings (UserID, DeckID, Rating) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $userId, $deckId, $rating);

    if ($stmt->execute()) {
        // Update the total likes or dislikes in SharedDecks
        $updateStmt = $conn->prepare("UPDATE SharedDecks SET Rating = Rating + ? WHERE DeckID = ?");
        $updateStmt->bind_param("ii", $rating, $deckId);
        $updateStmt->execute();
        $updateStmt->close();

        echo json_encode(['status' => 'success', 'message' => 'Rating recorded']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to record rating']);
    }
}

$conn->close();
?>