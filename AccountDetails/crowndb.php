<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "crown_db"; // <-- Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
