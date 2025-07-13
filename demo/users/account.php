<?php
if (!isset($user)) {
    session_start();
    include "../config.php";

    $username = $_SESSION['username'];
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <title>User Account</title>
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

        .account-container {
            flex: 1;
            display: flex;
            justify-content: space-between;
            padding: 50px;
        }

        .left-section {
            max-width: 100%;
        }

        h2 {
            color: #800040;
            font-size: 36px;
            margin-bottom: 30px;
        }

        .profile-info p {
            font-size: 20px;
            font-weight: bold;
            color: #800040;
            margin: 10px 0;
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
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #000;
            margin-bottom: 15px;
        }

        .upload-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .upload-form input[type="file"] {
            margin-top: 50px;
            margin-bottom: 10px;
            margin-left: 50px;

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
            width: 200%;
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
        <a href="#" class="active">Account</a>
        <a href="../logout.php">Logout</a>
    </div>

    <!-- Main content -->
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

        <div class="right-section">
        <img src="../uploads/<?= $user['image'] ?: 'default.png' ?>" alt="Profile Image">
        <form class="upload-form" action="upload_image.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="image" required>
                <input type="submit" value="Upload">
            </form>
        </div>
    </div>
</div>
</body>
</html>
