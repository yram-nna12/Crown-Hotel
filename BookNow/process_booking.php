<?php
session_start();
require_once '../config.php'; // your DB connection

// ✅ Validate reservation at least 2 full days in advance
date_default_timezone_set('Asia/Manila');

if (isset($_POST['check_in'])) {
    $checkIn = $_POST['check_in'];
    $checkInTime = strtotime($checkIn . ' 00:00:00');
    $now = time();
    $minAdvance = 60 * 60 * 24 * 2; // 2 full days in seconds

    if (($checkInTime - $now) < $minAdvance) {
        echo "<script>alert('Reservation must be made at least 2 full days in advance (24-hour format).'); window.history.back();</script>";
        exit;
    }
}

// Capture form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];
$room_type = $_POST['room_type'];
$reservation_date = date('Y-m-d'); // current date

// ✅ Capture hotel_name and room_price if passed from booknow.php
$hotel_name = $_POST['hotel_name'] ?? 'Crown Hotel';
$room_price = $_POST['room_price'] ?? 1200;

// ✅ Calculate number of nights and total price
$check_in_date = new DateTime($check_in);
$check_out_date = new DateTime($check_out);
$nights = $check_out_date->diff($check_in_date)->days;
$total_price = $room_price * $nights;

// ✅ Get hotel location based on name
function getHotelLocation($name) {
    $hotels = [
        "Crown Hotel at Legaspi" => "Pasay City, Metro Manila",
        "Crown Hotel at Westside City Tambo" => "Parañaque, Metro Manila",
        "Crown Hotel at General Espino" => "Taguig, Metro Manila",
        "Crown Hotel at San Roque" => "San Roque, Antipolo",
        "Crown Hotel at Tatlong Hari" => "Tatlong Hari, Laguna"
    ];
    return $hotels[$name] ?? 'Unknown Location';
}
$hotel_location = getHotelLocation($hotel_name);

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

// ✅ Save booking details to session
$_SESSION['transaction_id'] = $transaction_id;
$_SESSION['first_name'] = $first_name;
$_SESSION['last_name'] = $last_name;
$_SESSION['email'] = $email;
$_SESSION['contact'] = $contact;
$_SESSION['check_in'] = $check_in;
$_SESSION['check_out'] = $check_out;
$_SESSION['room_type'] = $room_type;
$_SESSION['hotel_name'] = $hotel_name;
$_SESSION['hotel_location'] = $hotel_location;
$_SESSION['nights'] = $nights;

// ✅ Room price & total
$room_prices = [
    "Standard Room" => 1200,
    "Deluxe Room" => 1350,
    "Superior Room" => 1500,
    "Suite Room" => 1600
];
$room_price = $room_prices[$room_type] ?? $room_price;
$_SESSION['room_price'] = $room_price;
$_SESSION['total_price'] = $total_price;

// Redirect to payment
header("Location: ../payment/payment.php");
exit();
