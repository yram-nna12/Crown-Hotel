<?php
session_start();
include "../config.php";

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    echo "<p style='color:red;'>⚠️ No user session found. Please log in as admin.</p>";
    exit();
}

$username = $_SESSION['username'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND userlevel='admin'");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    echo "<div style='padding:20px; border:1px solid red; max-width:400px; margin:auto; text-align:center;'>
            <p style='color:red;'>❌ Admin account not found in the database.</p>
            <form method='POST' action='create_admin.php'>
                <input type='submit' name='create_admin' value='Create Default Admin (admin/admin123)'>
            </form>
          </div>";
    exit();
}

// Determine profile image path
$profileImage = (!empty($user['image']) && file_exists("../uploads/" . $user['image']))
    ? "../uploads/" . $user['image']
    : "../uploads/default.png";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Account</title>
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
            font-size: 18px;
        }

        .account-container {
            flex: 1;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 60px;
        }

        .left-section {
            flex: 1;
            max-width: 50%;
        }

        .left-section h2 {
            color: #800040;
            font-size: 36px;
            margin-bottom: 30px;
        }

        .profile-info p {
            font-size: 20px;
            font-weight: bold;
            color: #800040;
            margin: 12px 0;
        }

        .reset-form {
            margin-top: 300px;
        }
        .reset-form p {
            font-size: 20px;
            color: #800040;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #800040;
            border-radius: 25px;
            background-color: #ffe6f0;
            color: #800040;
            font-size: 14px;
        }


        .submit-btn input[type="submit"] {
            padding: 10px 25px;
            background-color: #800040;
            color: white;
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
        }

        .right-section {
            width: 300px;
            background-color: white;
            border: 3px solid #000;
            border-radius: 10px;
            padding: 20px;
            background: linear-gradient(to bottom, #ffccff, #ff99ff);
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 400px;
            margin-right: 300px;
        }
        .right-section img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #000;
            margin-bottom: 20px;
        }

        .upload-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .upload-form input[type="file"] {
            margin-bottom: 15px;
        }

        .upload-form input[type="submit"] {
            background-color: #800040;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 25px;
            font-weight: bold;
            cursor: pointer;
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
        <a href="index.php">Videos</a>
        <a href="viewusers.php">Users</a>
        <a href="#" class="active">Account</a>
        <a href="../logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="account-container">
        <div class="left-section">
            <h2>Welcome User!</h2>
            <div class="profile-info">
                <p>Username: <?= htmlspecialchars($user['username']) ?></p>
                <p>Userlevel: <?= htmlspecialchars($user['userlevel']) ?></p>
                <p>Email: <?= htmlspecialchars($user['email']) ?></p>
            </div>

            <div class="reset-form">
                <p>Reset Password</p>
                <form action="reset_password.php" method="POST">
                    <div class="form-group">
                        <input type="password" name="old_password" placeholder="Current Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="new_password" placeholder="New Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                    <div class="submit-btn">
                        <input type="submit" name="submit" value="Submit">
                    </div>
                </form>
            </div>
        </div>

        <!-- Profile Image Section -->
        <div class="right-section">
        <img src="<?= $profileImage ?>" alt="Profile Image">

            <form class="upload-form" action="upload_image.php" method="POST" enctype="multipart/form-data">

                <input type="file" name="image" required>
                <input type="submit" value="Upload">
            </form>
        </div>
    </div>
</div>
</body>
</html>
