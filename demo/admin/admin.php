<?php
session_start();
include "../config.php";

// Redirect if not admin
if ($_SESSION['userlevel'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get all videos
$videosQuery = mysqli_query($conn, "SELECT * FROM videos ORDER BY title ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
       body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #ffe0c2, #ffb6f0);
    color: #6b004d;
}

.container {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    background-color: #ffd84c;
    width: 190px;
    padding-top: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 2px 0 8px rgba(0,0,0,0.1);
    
}

.logo-img {
    max-width: 120px;
    height: auto;
    margin-bottom: 20px;
}
.sidebar a, .sidebar button {
    width: 100%;
    text-align: center;
    padding: 12px;
    color: #6b004d;
    font-weight: bold;
    background: none;
    border: none;
    text-decoration: none;
    transition: background 0.2s;
    cursor: pointer;
    font-size: 30px;
}

.main {
    flex: 1;
    padding: 30px;
}

h2 {
    font-size: 28px;
    color: #6b004d;
}

.video-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(50px, 1fr));
}

.video-card {
    background: white;
    border-radius: 24px;
    box-shadow: 0 0 12px rgba(0,0,0,0.1);
    text-align: center;
    padding: 20px;
    text-decoration: none;
    color: black;
    transition: transform 0.2s ease;
    width: 300px;
}

.video-card:hover {
    transform: scale(1.02);
}

.video-card img {
    width: 200px;
    height: 180px;
    object-fit: cover;
    border-radius: 16px;
}

.video-title {
    margin-top: 12px;
    font-weight: bold;
    font-size: 16px;
}

    </style>
</head>
<body>
<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
    <div class="logo">
        <img src="./assets/images/logo (3).png" alt="Video Rental Logo" class="logo-img">
    </div>
    <a href="#" class="active">Dashboard</a>
    <a href="index.php">Videos</a>
    <a href="viewusers.php">Users</a>
    <a href="account.php">Account</a>

    <a href="../logout.php">Logout</a>
</div>


    <!-- Main content -->
    <div class="main">
        <h2>Admin</h2>

        <!-- Account Panel -->
        <div id="accountPanel" style="display:none;">
            <?php include("account.php"); ?>
        </div>

        <!-- Video Grid -->
        <div class="video-grid">
            <?php while ($video = mysqli_fetch_assoc($videosQuery)) { ?>
                <a href="video_purchases.php?id=<?= $video['id'] ?>" class="video-card">
                    <img src="./uploads/<?= $video['image'] ?: 'default.png' ?>" alt="Poster">
                    <div class="video-title"><?= htmlspecialchars($video['title']) ?></div>
                </a>
            <?php } ?>
        </div>
    </div>
</div>

<script>
function toggleAccount() {
    const panel = document.getElementById("accountPanel");
    panel.style.display = panel.style.display === "none" ? "block" : "none";
}
</script>
</body>
</html>
