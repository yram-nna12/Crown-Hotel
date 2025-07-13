<?php
session_start();
require_once '../config.php';
date_default_timezone_set('Asia/Manila');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate check-in is at least 2 days ahead
    if (isset($_POST['check_in'])) {
        $checkIn = $_POST['check_in'];
        $checkInTime = strtotime($checkIn . ' 00:00:00');
        $now = time();
        $minAdvance = 60 * 60 * 24 * 2; // 2 full days

        if (($checkInTime - $now) < $minAdvance) {
            echo "<script>alert('Reservation must be made at least 2 full days in advance.'); window.history.back();</script>";
            exit;
        }
    }

    // Get form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $room_type = $_POST['room_type'];
    $hotel_name = $_POST['hotel_name'] ?? 'Crown Hotel';
    $room_price = $_POST['room_price'] ?? 1200;
    $reservation_date = date('Y-m-d');
    $payment_status = 'pending';

    // Calculate number of nights
    $check_in_date = new DateTime($check_in);
    $check_out_date = new DateTime($check_out);
    $nights = $check_out_date->diff($check_in_date)->days;
    $total_price = $room_price * $nights;

    // Determine hotel location
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

    // Generate transaction ID
    $initials = strtoupper(substr($first_name, 0, 2));
    $month_str = strtoupper(date('M'));
    $day_str = date('d');
    $month_digit = $check_in_date->format('m');
    $year_short = $check_in_date->format('y');
    $room_code = strtoupper(substr($room_type, 0, 3));

    $query = "SELECT COUNT(*) as total FROM booking_db";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $count = $row['total'] + 1;
    $count_formatted = str_pad($count, 5, '0', STR_PAD_LEFT);
    $transaction_id = "{$initials}{$month_str}{$day_str}{$month_digit}{$year_short}-{$room_code}{$count_formatted}";

    // Insert into DB (✅ including hotel_name, room_price, and payment_status)
    $sql = "INSERT INTO booking_db (
        transaction_id,
        first_name,
        last_name,
        email,
        contact_number,
        reservation_date,
        check_in,
        check_out,
        room_type,
        hotel_name,
        room_price,
        payment_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssds",
        $transaction_id,
        $first_name,
        $last_name,
        $email,
        $contact,
        $reservation_date,
        $check_in,
        $check_out,
        $room_type,
        $hotel_name,
        $room_price,
        $payment_status
    );
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
    $_SESSION['hotel_name'] = $hotel_name;
    $_SESSION['hotel_location'] = $hotel_location;
    $_SESSION['nights'] = $nights;
    $_SESSION['room_price'] = $room_price;
    $_SESSION['total_price'] = $total_price;

    // Redirect to payment
    header("Location: ../payment/payment.php");
    exit();
}
?>
