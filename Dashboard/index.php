<?php
// Include your existing database connection file
include_once '../config.php'; // <--- Make sure this path is correct relative to your index.php

// --- Fetch Total Users ---
$totalUsers = 0;
$sqlTotalUsers = "SELECT COUNT(*) AS total_users FROM users";
$resultTotalUsers = $conn->query($sqlTotalUsers);
if ($resultTotalUsers && $resultTotalUsers->num_rows > 0) {
    $rowTotalUsers = $resultTotalUsers->fetch_assoc();
    $totalUsers = $rowTotalUsers['total_users'];
}

// --- Get Today's Date ---
$todayDate = date("F j, Y"); // e.g., "July 10, 2025"

// IMPORTANT: Close the database connection after fetching all necessary data
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
                    N/A<br><span>Total Bookings</span>
                </div>
                <div class="card blue">
                    N/A<br><span>Total Earnings</span>
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