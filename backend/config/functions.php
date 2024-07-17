<?php
require_once 'db.php'; // Adjusted the path

header('Content-Type: text/html');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserId() {
    return $_SESSION['user_id'];
}
?>