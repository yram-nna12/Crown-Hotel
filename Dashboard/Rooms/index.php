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
    <!-- Sidebar -->
    <aside class="sidebar">
     <div class="logo">
        <img src="./assets/images/LOGO.png" alt="Crown Hotel Logo" class="logo-img">
      </div>
      <nav class="menu">
        <a href="../index.php">Dashboard</a>
        <a href="#"class="active">Rooms</a>
        <a href="../Users/index.php">Users</a>
        <a href="../Bookings/index.php">Bookings</a>
      </nav>
      <div class="profile">
        <img src="./assets/images/admin-profile.png" alt="Admin Icon" />
      </div>
    </aside>


    <main class="main-content">
        <div class="date-section">
            <h2>DATE</h2>
            <?php
                // Get the current date and format it as requested (e.g., "Thursday, July 10, 2025")
                $currentDate = date("l, F j, Y");
                echo "<p>" . $currentDate . "</p>";
            ?>
        </div>

        <div class="rooms-section">
            <h3>Available Rooms</h3>
            <div class="room-table">
                <div class="room-table-header">
                    <span class="branch-label">BRANCH</span>
                    <span class="arrow-icon">▲</span>
                </div>
                <div class="room-row">
                    <span class="room-type">Standard Room</span>
                    <span class="room-count">00</span>
                </div>
                <div class="room-row">
                    <span class="room-type">Deluxe Room</span>
                    <span class="room-count">00</span>
                </div>
                <div class="room-row">
                    <span class="room-type">Superior Room</span>
                    <span class="room-count">00</span>
                </div>
                <div class="room-row">
                    <span class="room-type">Suite Room</span>
                    <span class="room-count">00</span>
                </div>
            </div>
        </div>

        <div class="rooms-section">
            <h3>Booked Rooms</h3>
            <div class="room-table">
                <div class="room-table-header">
                    <span class="branch-label">BRANCH</span>
                    <span class="arrow-icon">▲</span>
                </div>
                <div class="room-row">
                    <span class="room-type">Standard Room</span>
                    <span class="room-count">00</span>
                </div>
                <div class="room-row">
                    <span class="room-type">Deluxe Room</span>
                    <span class="room-count">00</span>
                </div>
                <div class="room-row">
                    <span class="room-type">Superior Room</span>
                    <span class="room-count">00</span>
                </div>
                <div class="room-row">
                    <span class="room-type">Suite Room</span>
                    <span class="room-count">00</span>
                </div>
            </div>
        </div>
    </main>
  
  </div>
</body>
</html>
