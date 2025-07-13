<?php
session_start();
include "../config.php";

if ($_SESSION['userlevel'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

// Get logged-in user info
$username = $_SESSION['username'];
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($userQuery);
$user_id = $user['id'];

// Handle Buy action
if (isset($_GET['buy'])) {
    $video_id = intval($_GET['buy']);
    $check = mysqli_query($conn, "SELECT * FROM purchases WHERE user_id=$user_id AND video_id=$video_id");

    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO purchases (user_id, video_id) VALUES ($user_id, $video_id)");
        mysqli_query($conn, "UPDATE videos SET available = 0 WHERE id = $video_id");
        header("Location: users.php?msg=success");
    } else {
        header("Location: users.php?msg=already");
    }
    exit();
}

// Show messages
$message = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'success') {
        $message = "<p style='color:green;'>✅ Purchase successful!</p>";
    } elseif ($_GET['msg'] === 'already') {
        $message = "<p style='color:orange;'>⚠️ You already purchased this video.</p>";
    }
}

// Get videos
$videos = mysqli_query($conn, "SELECT * FROM videos");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
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
            padding: 20px;
            color: #6b004d;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.2s;
            font-size: 18px;
    
        }


        .main {
            flex: 1;
            padding: 40px;
        }

        .main h2 {
            color: #800040;
            margin-bottom: 30px;
        }

        .video-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .card {
            background: white;
            width: 200px;
            padding: 15px;
            border-radius: 30px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .card img, .card video {
            width: 100%;
            height: auto;
            border-radius: 20px;
        }

        .card h3 {
            margin-top: 10px;
            color: #800040;
        }

        .card p {
            margin: 5px 0;
        }

        .card button, .card a.buy-btn {
            margin-top: 10px;
            padding: 6px 12px;
            background-color: #800040;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .card button[disabled] {
            background-color: gray;
            cursor: default;
        }

        .message {
            margin: 10px 0;
            font-weight: bold;
        }

        .account-toggle {
            margin-top: 30px;
        }

        .logout-link {
            display: inline-block;
            margin-top: 30px;
            color: #800040;
            font-weight: bold;
            text-decoration: none;
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
        <a href="account.php" class="active">Account</a>
        <a href="../logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <h2>WELCOME <?= htmlspecialchars(strtoupper($username)) ?>!</h2>

        <div class="message"><?= $message ?></div>

        <div class="video-grid">
            <?php while ($video = mysqli_fetch_assoc($videos)) { ?>
                <div class="card">
                    <?php if (str_ends_with($video['image'], '.mp4')): ?>
                        <video controls>
                            <source src="../admin/uploads<?= htmlspecialchars($video['image']) ?>" type="video/mp4">
                        </video>
                    <?php else: ?>
                        <img src="../admin/uploads/<?= $video['image'] ?: 'default.png' ?>" alt="Video Poster">
                    <?php endif; ?>

                    <h3><?= htmlspecialchars($video['title']) ?></h3>

                    <p>
                        <?= $video['available'] 
                            ? "<span style='color:green;'>Available</span>" 
                            : "<span style='color:red;'>Not Available</span>" 
                        ?>
                    </p>

                    <?php if ($video['available']): ?>
                        <a class="buy-btn" href="users.php?buy=<?= $video['id'] ?>">🛒 Buy</a>
                    <?php else: ?>
                        <button disabled>Not Available</button>
                    <?php endif; ?>
                </div>
            <?php } ?>
        </div>

        <div class="account-toggle" id="accountPanel" style="display:none;">
            <?php include("account.php"); ?>
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
