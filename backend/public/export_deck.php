<?php
session_start();
include '../config/functions.php';
$conn = get_db_connection();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

$deckId = $_GET['deckId'];
$format = $_GET['format'];

$stmt = $conn->prepare('SELECT Front, Back FROM Cards WHERE DeckID = ?');
$stmt->bind_param('i', $deckId);
$stmt->execute();
$result = $stmt->get_result();

$cards = $result->fetch_all(MYSQLI_ASSOC);

if ($format === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="deck.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Front', 'Back']);
    foreach ($cards as $card) {
        fputcsv($output, $card);
    }
    fclose($output);
} elseif ($format === 'json') {
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="deck.json"');

    echo json_encode($cards);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Unsupported export format']);
}
?>