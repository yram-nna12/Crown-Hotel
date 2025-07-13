<?php
session_start();
include "config.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 🔐 Static admin login
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['username'] = 'admin';
        $_SESSION['userlevel'] = 'admin';
        header("Location: admin/admin.php");
        exit();
    }

    $username = mysqli_real_escape_string($conn, $username);
    $password = md5($password); // Use stronger hash in production

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['userlevel'] = $row['userlevel'];

        header("Location: " . ($row['userlevel'] === 'user' ? "users/users.php" : "admin/admin.php"));
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Video Rental</title>
    <style>
      body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('./admin/assets/images/2.png');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
}

        .login-container {
            display: flex;
            height: 100vh;
        }

        .left-panel {
            flex: 1;
            background: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            background: #ff84d4;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 500px;
            max-width: 400px;
            color: white;
        }

        .login-box h2 {
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: bold;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 0px 12px 12px;
            border: none;
            border-bottom: 2px solid #fff;
            background: transparent;
            color: white;
            font-size: 16px;
        }

        .form-group input:focus {
            outline: none;
            border-color: white;
        }

        .form-group .toggle-password {
            position: absolute;
            right: 10px;
            top: 32px;
            cursor: pointer;
            color: #fff;
        }

        .login-btn {
            width: 100%;
            background: #d90087;
            border: none;
            padding: 12px;
            font-size: 16px;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .login-btn:hover {
            background: #bf0072;
        }

        .error-message {
            color: #fff;
            background: rgba(255, 0, 0, 0.3);
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- Left: Logo & Image -->
    <div class="left-panel">
    </div>

    <!-- Right: Login Box -->
    <div class="right-panel">
        <form method="POST" class="login-box">
            <h2>LOG IN</h2>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="password" required>
                <span class="toggle-password" onclick="togglePassword()"></span>
            </div>

            <button type="submit" class="login-btn">Log in</button>
        </form>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById("password");
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
