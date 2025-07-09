<?php
session_start();
require_once './crowndb.php'; // adjust path as needed

$is_logged_in = isset($_SESSION['user_id']);
$user = [];

if ($is_logged_in) {
    $stmt = $conn->prepare("SELECT first_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>