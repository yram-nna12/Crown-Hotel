<?php
require_once '../config.php';

$hotel_id = $_GET['hotel_id'] ?? '';

$sql = "SELECT * FROM rooms WHERE hotel_id = ? AND status = 'available'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hotel_id);
$stmt->execute();

$result = $stmt->get_result();
$rooms = [];

while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}

header('Content-Type: application/json');
echo json_encode($rooms);
?>
