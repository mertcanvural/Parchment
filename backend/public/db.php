<?php
function get_db_connection() {
    $servername = "localhost:8889";
    $username = "root";
    $password = "root";
    $dbname = "Parchment";
    $port = "8889";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>