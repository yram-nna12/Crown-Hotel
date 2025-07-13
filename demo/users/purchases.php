<?php
session_start();
include "../config.php";

if ($_SESSION['userlevel'] !== 'user') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($userQuery);
$user_id = $user['id'];

$query = "SELECT videos.* FROM videos 
JOIN purchases ON videos.id = purchases.video_id 
WHERE purchases.user_id = $user_id";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Rentals</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ffe6b3, #ff99ff);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            background-color: #ffd84c;
            width: 200px;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo img {
            max-width: 120px;
            height: auto;
            margin-bottom: 20px;
        }

        .sidebar a {
            width: 100%;
            text-align: center;
            padding: 15px 0;
            color: #6b004d;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.2s;
            font-size: 18px;
        }

    

        .main-content {
            flex: 1;
            padding: 40px;
        }

        .main-content h2 {
            color: #800040;
            font-size: 28px;
            margin-bottom: 30px;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .video-card {
            width: 200px;
            background-color: white;
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            padding: 20px 10px;
        }

        .video-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 10px;
        }

        .video-card h3 {
            color: #800040;
            font-size: 18px;
            margin: 10px 0 5px;
        }

        .video-card p {
            font-size: 14px;
            color: #444;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="../admin/assets/images/logo (3).png" alt="Video Rental Logo">
        </div>
        <a href="users.php">Dashboard</a>
        <a href="purchases.php">Purchases</a>
        <a href="./account.php" class="active">Account</a>
        <a href="../logout.php">Logout</a>
    </div>

    <!-- Main Section -->
    <div class="main-content">
        <h2>Your Rentals</h2>
        <div class="card-container">
            <?php while ($video = mysqli_fetch_assoc($result)) { ?>
                <div class="video-card">
                    <?php if ($video['image']): ?>
                        <img src="../admin/uploads/<?= htmlspecialchars($video['image']) ?>" alt="<?= htmlspecialchars($video['title']) ?>">
                    <?php else: ?>
                        <img src="uploads/default.png" alt="Default Image">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($video['title']) ?></h3>
                    <p><?= htmlspecialchars($video['genre']) ?> | <?= htmlspecialchars($video['year']) ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

</body>
</html>
