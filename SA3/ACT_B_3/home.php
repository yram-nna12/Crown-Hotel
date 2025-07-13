<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['user'];
$message = "";

// Fetch user info from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

// Handle password change
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $retype = $_POST['re_password'];

    if (!password_verify($current, $user['password'])) {
        $message = "❌ Current password is not the same with the old password.";
    } elseif ($new !== $retype) {
        $message = "❌ New password and Re-entered password should be the same.";
    } else {
        $hashedNew = password_hash($new, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
        $update->execute([$hashedNew, $username]);
        $message = "✅ Password successfully updated.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, rgb(231, 231, 231), rgb(17, 75, 102), rgb(6, 38, 41)) no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: white;
            padding: 60px 80px;
            position: relative;
        }

        .container {
            max-width: 800px;
        }

        h2 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color:rgb(9, 68, 97)
        }

        hr {
            border: none;
            height: 3px;
            background-color:  rgb(97, 199, 224);
            width: 100rem;
            margin: 10px 0 30px 0;
        }

        p, li {
            font-size: 20px;
            margin: 5px 0;
            color:rgb(9, 68, 97);
            gap: 10px;
            
        }

        form {
            margin-top: 30px;
        }

        input[type="password"] {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            background-color: rgb(97, 199, 224);
            color: white;
            border: none;
            border-radius: 6px;
            margin-top: 10px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: rgb(97, 199, 224);
        }

        .message {
            margin-top: 20px;
            font-weight: bold;
            color: yellow;
        }

        a.logout {
            position: absolute;
            bottom: 30px;
            right: 30px;
            display: inline-block;
            padding: 12px 24px;
            background-color:  rgb(97, 199, 224);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
        }

        a.logout:hover {
            background-color:  rgb(59, 212, 250);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>WELCOME: <?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?></h2>
        <p><strong>BIRTHDAY:</strong> <?php echo htmlspecialchars($user['birthday']); ?></p>
        <p><strong>CONTACT DETAILS:</strong></p>
        <ul>
            <li><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
            <li><strong>Contact:</strong> <?php echo htmlspecialchars($user['phone']); ?></li>
        </ul>
        <hr>

        <h3>Change Password</h3>
        <form method="POST">
            <input type="password" name="current_password" placeholder="Enter Current Password" required><br>
            <input type="password" name="new_password" placeholder="Enter New Password" required><br>
            <input type="password" name="re_password" placeholder="Re-enter New Password" required><br>
            <button type="submit">Update Password</button>
        </form>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>

    <a href="logout.php" class="logout">Logout</a>
</body>
</html>
