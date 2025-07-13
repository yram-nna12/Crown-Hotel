<?php
// Include your existing database connection file
// Assuming config.php is in the parent directory of the 'Users' folder
include_once '../../config.php';

// --- Fetch Total Users Count ---
$totalUsersCount = 0;
$sqlTotalUsers = "SELECT COUNT(*) AS total_users FROM users";
$resultTotalUsers = $conn->query($sqlTotalUsers);
if ($resultTotalUsers && $resultTotalUsers->num_rows > 0) {
    $rowTotalUsers = $resultTotalUsers->fetch_assoc();
    $totalUsersCount = $rowTotalUsers['total_users'];
}

// --- Fetch Users Data ---
// Adjust column names to match your 'users' table: first_name, last_name, contact_number, email
// Concatenate first_name and last_name to display as a full name
$sqlUsers = "SELECT first_name, last_name, phone, email FROM users ORDER BY first_name ASC";
$resultUsers = $conn->query($sqlUsers);

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
    <!-- Sidebar -->
    <aside class="sidebar">
     <div class="logo">
        <img src="./assets/images/LOGO.png" alt="Crown Hotel Logo" class="logo-img">
      </div>
      <nav class="menu">
        <a href="../index.php">Dashboard</a>
        <a href="../Rooms/index.php">Rooms</a>
        <a href="#"class="active">Users</a>
        <a href="../Bookings/index.php">Bookings</a>
      </nav>
      <div class="profile">
        <img src="./assets/images/admin-profile.png" alt="Admin Icon" />
      </div>
    </aside>


   <main class="main-content">
        <div class="users-header">
            <h2>Total Users <span class="total-users-count"><?php echo $totalUsersCount; ?></span></h2>
        </div>

        <div class="users-table-container">
            <div class="user-table">
                <div class="user-table-header">
                    <span class="user-header-name">NAME</span>
                    <span class="user-header-number">NUMBER</span>
                    <span class="user-header-email">EMAIL</span>
                </div>
                <?php
                $row_count = 0;
                if ($resultUsers && $resultUsers->num_rows > 0) {
                    while($row = $resultUsers->fetch_assoc()) {
                        $row_class = ($row_count % 2 == 0) ? '' : 'alternate-row';
                        $full_name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                        $contact_number = htmlspecialchars($row['phone']);
                        $email = htmlspecialchars($row['email']);
                        ?>
                        <div class="user-row <?php echo $row_class; ?>">
                            <span class="user-name"><?php echo $full_name; ?></span>
                            <span class="user-number"><?php echo $contact_number; ?></span>
                            <span class="user-email"><?php echo $email; ?></span>
                        </div>
                        <?php
                        $row_count++;
                    }
                } else {
                    // No users found in the database
                    ?>
                    <div class="user-row">
                        <span class="user-name">No users found.</span>
                        <span class="user-number"></span>
                        <span class="user-email"></span>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </main>
  
  </div>
</body>
</html>