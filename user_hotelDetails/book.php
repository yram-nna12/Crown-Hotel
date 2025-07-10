<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $room_id = $_POST['room_id'];

  $sql = "UPDATE rooms SET status = 'booked' WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $room_id);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    header("Location: ../hotel details/hotel-details.html?id=" . $_POST['hotel_id']);
    exit;
  } else {
    echo "Booking failed or room already booked.";
  }
}
?>
