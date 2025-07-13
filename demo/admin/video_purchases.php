<?php
session_start();
include "../config.php";

if ($_SESSION['userlevel'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$video_id = $_GET['id'] ?? 0;
$videoQuery = mysqli_query($conn, "SELECT * FROM videos WHERE id=$video_id");
$video = mysqli_fetch_assoc($videoQuery);

$purchasesQuery = mysqli_query($conn, "
    SELECT users.username, purchases.purchase_date
    FROM purchases
    JOIN users ON purchases.user_id = users.id
    WHERE purchases.video_id = $video_id
    ORDER BY purchases.purchase_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Video Purchases - <?= htmlspecialchars($video['title']) ?></title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #fff0b3, #ffcce6, #ffccff);
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
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
        }


        .logo img {
            max-width: 120px;
            height: auto;
            margin-bottom: 20px;
        }

        .sidebar a {
            width: 100%;
            text-align: center;
            padding: 12px;
            color: #6b004d;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.2s;
            font-size: 20px;
        }

        /* Main content */
        .main {
            flex: 1;
            display: flex;
            padding: 50px;
            gap: 30px;
            align-items: center;
        }

        .card {
            flex: 0 0 700px;
            border: 3px solid black;
            padding: 20px;
            text-align: center;
            background-color: #ffe6f2;
            width: 100%;
        }

        .card img, .card video {
            width: 100%;
            height: auto;
            border: none;
        }

        .card h2 {
            margin-top: 15px;
            color: #800040;
        }

        .purchase-list {
            display: flex;
            flex-direction: column;
            flex: 1;
            padding-left: 30px;
            border-left: 3px dashed #800040;
            height: 100%;
        }

        .purchase-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 2px dashed #800040;
            font-weight: bold;
            color: #800040;
        }

        .purchase-header {
            display: flex;
            justify-content: space-between;
            padding-bottom: 10px;
            font-size: 18px;
            color: #800040;
            border-bottom: 3px dashed #800040;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #800040;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="./assets/images/logo (3).png" alt="Video Rental Logo">
        </div>
        <a href="admin.php">Dashboard</a>
        <a href="index.php" class="active">Videos</a>
        <a href="viewusers.php">Users</a>
        <a href="#" onclick="toggleAccount()">Account</a>
        <a href="../logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <!-- Video Card -->
        <div class="card">
            <?php if (str_ends_with($video['image'], '.mp4')): ?>
                <video controls>
                    <source src="../uploads/<?= htmlspecialchars($video['image']) ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php else: ?>
                <img src="../uploads/<?= $video['image'] ?: 'default.png' ?>" alt="Video Image">
            <?php endif; ?>
            <h2><?= htmlspecialchars($video['title']) ?></h2>
        </div>

        <!-- Purchases -->
        <div class="purchase-list">
            <div class="purchase-header">
                <span>Name</span>
                <span>Date</span>
            </div>

            <?php if (mysqli_num_rows($purchasesQuery) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($purchasesQuery)): ?>
                    <div class="purchase-item">
                        <span><?= htmlspecialchars($row['username']) ?></span>
                        <span><?= date("M d, Y", strtotime($row['purchase_date'])) ?></span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="padding-top:20px;">No users have purchased this video yet.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
