<?php
session_start();
include '../config/functions.php';
$conn = get_db_connection();
$userId = $_SESSION['user_id']; // Assuming user ID is stored in session

// Fetching future due data
$sql1 = "
    SELECT 
        DATE(NextReviewDate) as due_date, 
        COUNT(*) as due_count 
    FROM 
        Reviews 
    WHERE 
        UserID = ? 
    GROUP BY 
        due_date
    ORDER BY 
        due_date
";

$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param('i', $userId);
$stmt1->execute();
$result1 = $stmt1->get_result();
$futureDueData = $result1->fetch_all(MYSQLI_ASSOC);

// Fetching card status data
$sql2 = "
    SELECT 
        CASE 
            WHEN Repetitions = 0 THEN 'New' 
            WHEN Repetitions > 0 AND Repetitions < 5 THEN 'Learning' 
            ELSE 'Review' 
        END as card_status, 
        COUNT(*) as status_count 
    FROM 
        Reviews 
    WHERE 
        UserID = ?
    GROUP BY 
        card_status
";

$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param('i', $userId);
$stmt2->execute();
$result2 = $stmt2->get_result();
$cardStatusData = $result2->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'status' => 'success',
    'future_due_data' => $futureDueData,
    'card_status_data' => $cardStatusData
]);
?>