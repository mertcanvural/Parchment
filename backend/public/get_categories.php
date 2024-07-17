<?php
include '../config/functions.php';
$conn = get_db_connection();

$sql = "SELECT CategoryID, CategoryName FROM Categories ORDER BY CategoryName";
$result = $conn->query($sql);
$categories = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['status' => 'success', 'data' => $categories]);
?>