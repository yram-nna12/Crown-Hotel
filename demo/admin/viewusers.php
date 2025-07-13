<?php
session_start();
include "../config.php";

if ($_SESSION['userlevel'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>View Users</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ffe0c2, #fba1ff);
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
    font-size: 20px;
}


        .main {
            flex: 1;
            padding: 40px;
        }

        h2 {
            color: #6b004d;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px 10px;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-radius: 12px;
            background-color: white;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }

        th {
            background-color: #6b004d;
            color: white;
            font-weight: bold;
        }

        a.button {
            margin-right: 20px;
            display: inline-block;
            padding: 10px 18px;
            background: #6b004d;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s;
        }

        a.button:hover {
            background-color: #9e006d;
        }

        img {
            border-radius: 8px;
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
    <a href="admin.php" class="active">Dashboard</a>
    <a href="index.php">Videos</a>
    <a href="viewusers.php">Users</a>
    <a href="account.php">Account</a>

    <a href="../logout.php">Logout</a>
</div>


    <div class="main">
        <h2>All Registered Users</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Username</th>
                <th>User Level</th>
                <th>Status</th>
                <th>Profile</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $row['userlevel'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <?php if (!empty($row['image'])): ?>
                            <img src="../uploads/<?= $row['image'] ?>" width="60">
                        <?php else: ?>
                            No image
                        <?php endif; ?>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <br><br>
        <a href="admin_add.php" class="button">➕ Add User</a>
    </div>
</div>
</body>
</html>
