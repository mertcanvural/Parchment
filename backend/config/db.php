<?php
// This function establishes a connection to the MySQL database.
// It uses the specified server name, username, password, database name, and port.
// If the connection fails, it will terminate the script and display an error message.
// The function returns the connection object which can be used to interact with the database.

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