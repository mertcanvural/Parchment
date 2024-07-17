<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$conn = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];

        // Get the user ID from session
        $userId = $_SESSION['user_id']; // Adjust this to match how you store user ID in session
        if (!$userId) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            exit;
        }

        // Create a new deck
        $deckName = pathinfo($fileName, PATHINFO_FILENAME);
        $stmt = $conn->prepare("INSERT INTO Decks (DeckName, UserID) VALUES (?, ?)");
        $stmt->bind_param('si', $deckName, $userId);
        $stmt->execute();
        $deckId = $stmt->insert_id;
        $stmt->close();

        // Check file format and process accordingly
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if ($fileExtension === 'csv') {
            $fileHandle = fopen($fileTmpName, 'r');
            $isFirstRow = true;
            while (($data = fgetcsv($fileHandle, 1000, ',')) !== FALSE) {
                if ($isFirstRow) {
                    $isFirstRow = false;
                    continue; // Skip the first row
                }
                $front = $data[0];
                $back = $data[1];
                $stmt = $conn->prepare("INSERT INTO Cards (DeckID, Front, Back) VALUES (?, ?, ?)");
                $stmt->bind_param('iss', $deckId, $front, $back);
                $stmt->execute();
                $stmt->close();
            }
            fclose($fileHandle);
        } elseif ($fileExtension === 'json') {
            $jsonData = file_get_contents($fileTmpName);
            $cards = json_decode($jsonData, true);
            foreach ($cards as $card) {
                $front = $card['Front'];
                $back = $card['Back'];
                $stmt = $conn->prepare("INSERT INTO Cards (DeckID, Front, Back) VALUES (?, ?, ?)");
                $stmt->bind_param('iss', $deckId, $front, $back);
                $stmt->execute();
                $stmt->close();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Unsupported file format']);
            exit;
        }

        echo json_encode(['status' => 'success', 'message' => 'Deck imported successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
    }
}
?>