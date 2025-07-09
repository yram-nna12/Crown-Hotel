<?php
session_start();
require_once '../config.php'; // your DB connection

// Capture form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];
$room_type = $_POST['room_type'];
$reservation_date = date('Y-m-d'); // current date

// Generate Transaction ID
$initials = strtoupper(substr($first_name, 0, 2));
$month_str = strtoupper(date('M'));       // e.g., JUL
$day_str = date('d');                     // e.g., 09
$checkInDate = new DateTime($check_in);
$month_digit = $checkInDate->format('m'); // 02
$year_short = $checkInDate->format('y');  // 25
$room_code = strtoupper(substr($room_type, 0, 3));

// Get the current count
$query = "SELECT COUNT(*) as total FROM booking_db";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$count = $row['total'] + 1;
$count_formatted = str_pad($count, 5, '0', STR_PAD_LEFT);

// Final Transaction ID
$transaction_id = "{$initials}{$month_str}{$day_str}{$month_digit}{$year_short}-{$room_code}{$count_formatted}";

// Insert into DB
$sql = "INSERT INTO booking_db (transaction_id, first_name, last_name, email, contact_number, reservation_date, check_in, check_out, room_type)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssss", $transaction_id, $first_name, $last_name, $email, $contact, $reservation_date, $check_in, $check_out, $room_type);
$stmt->execute();
$stmt->close();

// Save to session
$_SESSION['transaction_id'] = $transaction_id;
$_SESSION['first_name'] = $first_name;
$_SESSION['last_name'] = $last_name;
$_SESSION['email'] = $email;
$_SESSION['contact'] = $contact;
$_SESSION['check_in'] = $check_in;
$_SESSION['check_out'] = $check_out;
$_SESSION['room_type'] = $room_type;

// Redirect
header("Location: ../payment/payment.php");
exit();
?>
