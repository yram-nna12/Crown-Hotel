<?php
$host = "localhost";
$user = "root";
$password = ""; // or your MySQL password
$database = "crown_hotel";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
