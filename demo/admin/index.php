<?php
include '../config.php';
session_start();

if ($_SESSION['userlevel'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM videos ORDER BY title ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Manage Videos</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ffe0c2, #ffb6f0);
            color: #6b004d;
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

        .main {
            flex: 1;
            padding: 40px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 30px;
        }

        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 30px;
        }

        .video-card {
            background: white;
            border-radius: 28px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            text-align: center;
            width: 300px;
        }

        .video-card img {
            width: 150px;
            height: 180px;
            object-fit: cover;
            border-radius: 16px;
        }

        .video-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }

        .video-meta {
            font-size: 14px;
            margin: 4px 0;
        }

        .actions {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .actions a {
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            color: white;
        }

        .edit-btn {
            background-color: #6b004d;
        }

        .delete-btn {
            background-color: #ff4d4d;
        }

        .top-links {
            margin-top: 20px;
        }

        .top-links a {
            color: #6b004d;
            font-weight: bold;
            text-decoration: none;
            margin-right: 20px;
        }

        .top-links a:hover {
            text-decoration: underline;
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
        <h2>Manage Videos</h2>

        <div class="video-grid">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="video-card">
                    <?php if (!empty($row['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Video Poster">
                    <?php else: ?>
                        <img src="uploads/default.png" alt="Default Poster">
                    <?php endif; ?>

                    <div class="video-title"><?= htmlspecialchars($row['title']) ?></div>
                    <div class="video-meta"><?= htmlspecialchars($row['genre']) ?> | <?= $row['year'] ?></div>
                    <div class="video-meta">Available: <?= $row['available'] ? 'Yes' : 'No' ?></div>

                    <div class="actions">
                        <a href="edit.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="top-links">
            <a href="add.php">➕ Add New Video</a>
        </div>
    </div>
</div>

</body>
</html>
