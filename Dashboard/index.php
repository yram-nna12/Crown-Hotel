<?php

include_once '../config.php'; 


error_reporting(E_ALL);
ini_set('display_errors', 1);
// -----------------------------------------------------------

// --- Fetch Total Users ---
$totalUsers = 0;
$sqlTotalUsers = "SELECT COUNT(*) AS total_users FROM users";
$resultTotalUsers = $conn->query($sqlTotalUsers);
if ($resultTotalUsers && $resultTotalUsers->num_rows > 0) {
    $rowTotalUsers = $resultTotalUsers->fetch_assoc();
    $totalUsers = $rowTotalUsers['total_users'];
} else {
    error_log("PHP: Error fetching total users: " . $conn->error);
}

// --- Fetch Total Bookings ---
$totalBookings = 0;
$sqlTotalBookings = "SELECT COUNT(*) AS total_bookings FROM booking_db";
$resultTotalBookings = $conn->query($sqlTotalBookings);
if ($resultTotalBookings) {
    if ($resultTotalBookings->num_rows > 0) {
        $rowTotalBookings = $resultTotalBookings->fetch_assoc();
        $totalBookings = $rowTotalBookings['total_bookings'];
    }
} else {
    error_log("PHP: Error fetching total bookings: " . $conn->error);
}

$totalEarnings = 0.00;

$sqlTotalEarnings = "SELECT SUM(amount) AS total_earnings FROM booking_db WHERE status = 'paid'";
$resultTotalEarnings = $conn->query($sqlTotalEarnings);
if ($resultTotalEarnings) {
    if ($resultTotalEarnings->num_rows > 0) {
        $rowTotalEarnings = $resultTotalEarnings->fetch_assoc();
        // Ensure that if SUM returns NULL (no paid bookings), it defaults to 0
        $totalEarnings = $rowTotalEarnings['total_earnings'] ?? 0.00; 
    }
} else {
    error_log("PHP: Error fetching total earnings: " . $conn->error);
}

// --- Get Today's Date ---
$todayDate = date("F j, Y"); // e.g., "July 10, 2025"


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
           <div class="logo">
                <img src="./assets/images/LOGO.png" alt="Crown Hotel Logo" class="logo-img">
            </div>
            <nav class="menu">
                <a href="#" class="active">Dashboard</a>
                <a href="./Rooms/index.php">Rooms</a>
                <a href="./Users/index.php">Users</a>
                <a href="./Bookings/index.php">Bookings</a>
            </nav>
            <div class="profile">
                <img src="./assets/images/admin-profile.png" alt="Admin Icon" />
            </div>
        </aside>

        <main class="main-content">
            <h1><strong>Hi Admin!</strong><br>Dashboard</h1>

            <div class="stats">
                <div class="card gold">
                    <?php echo $totalBookings; ?><br><span>Total Bookings</span>
                </div>
                <div class="card blue">
                    ₱<?php echo number_format($totalEarnings, 2); ?><br><span>Total Earnings</span>
                </div>
                <div class="card gold">
                    <?php echo $totalUsers; ?><br><span>Total Users</span>
                </div>
            </div>

            <div class="row">
                <div class="room-table">
                    <div class="table-header">
                        <span>Type of Room</span>
                        <span>Today's Date: <?php echo $todayDate; ?></span>
                    </div>
                    <div class="table-body">
                        <div class="table-row"></div>
                        <div class="table-row"></div>
                        <div class="table-row"></div>
                        <div class="table-row"></div>
                    </div>
                </div>

                <div class="co-admin-box">
                    <h2>Co-Admin</h2>
                    <ul>
                        <li>Marielle Torres</li>
                        <li>Mary Ann Camacho</li>
                        <li>Aviona Bianca Bernil</li>
                        <li>Rona Mae Serrano</li>
                    </ul>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
