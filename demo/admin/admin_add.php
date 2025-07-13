<?php
include "../config.php";
session_start();

// Restrict access to admins only
if ($_SESSION['userlevel'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $userlevel = $_POST['userlevel'];
    $status = $_POST['status'];

    // Handle image upload
    $image_name = $_FILES['image']['name'];
    $tmp_image = $_FILES['image']['tmp_name'];

    $upload_dir = "./uploads";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir);
    }

    $target_file = $upload_dir . basename($image_name);
    move_uploaded_file($tmp_image, $target_file);

    $query = "INSERT INTO users (email, username, password, userlevel, status, image)
              VALUES ('$email', '$username', '$password', '$userlevel', '$status', '$image_name')";
    mysqli_query($conn, $query);

    $success = "✅ User added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
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
            font-size: 20px;
        }
        .content {
            flex: 1;
            padding: 50px;
        }

        .form-box {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            color: #800040;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            color: #800040;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px 15px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            background-color: #fef2f7;
        }

        input[type="submit"] {
            background-color: #800040;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }

        .success-message {
            color: green;
            margin-bottom: 20px;
            font-weight: bold;
        }

        a.back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #800040;
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
        <a href="account.php" class="active">Account</a>
        <a href="../logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="form-box">
            <h2>Add New User</h2>

            <?php if (!empty($success)): ?>
                <div class="success-message"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <label>Email:</label>
                <input type="email" name="email" required>

                <label>Username:</label>
                <input type="text" name="username" required>

                <label>Password:</label>
                <input type="password" name="password" required>

                <label>User Level:</label>
                <select name="userlevel" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>

                <label>Status:</label>
                <select name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>

                <label>Profile Image:</label>
                <input type="file" name="image" accept="image/*" required>

                <input type="submit" value="Add User">
            </form>

            <a class="back-link" href="./admin.php">⬅ Back to Admin Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
