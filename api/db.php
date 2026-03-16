<?php
// api/db.php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "prince_e_shopping"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}
?>