<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      display: flex;
      min-height: 100vh;
      background-size: cover;
      background: linear-gradient(to bottom, rgb(231, 231, 231), rgb(17, 75, 102), rgb(6, 38, 41)) no-repeat center center fixed;
      gap: 1px;
    }

    /* Left Panel */
    .left-panel {
  flex: 1;
  position: relative;
  overflow: hidden;
  color: white;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 30px;
  text-align: center;
}

    .left-panel h1 {
      font-size: 4rem;
      line-height: 1.5;
      font-family: Verdana, Geneva, Tahoma, sans-serif;
      color:rgb(243, 243, 243);
    }

    /* Right Panel */
    .right-panel {
      flex: 1.2;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 60px 20px;
      background: linear-gradient(to bottom, rgb(231, 231, 231), rgb(17, 75, 102), rgb(6, 38, 41)) no-repeat center center fixed;
      background-size: cover;
    }

    .form-container {
      background-color: rgba(255, 255, 255, 0.44);
      padding: 50px 30px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 50%;
      max-width: 600px;
    height: 60%;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #012d42;
      font-size: 2rem;
      margin-top:10%;
    }

    form label {
      display: block;
      margin: 10px 0 5px;
      color: #333;
      font-weight: 500;
      font-size: 22px;
    }

    form input[type="text"],
    form input[type="password"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 20px;
      margin-bottom: 10px;
    }

    form input[type="checkbox"] {
      margin-right: 8px;
    }

    .login-btn {
      width: 100%;
      padding: 14px;
      background-color: rgb(8, 204, 253);
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
      transition: background 0.3s ease;
    }
      

    .login-btn:hover {
      background:rgb(8, 245, 253);
    }

    .error-message {
      color: #c62828;
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }

      .left-panel {
        height: 200px;
        padding: 20px;
      }

      .left-panel h1 {
        font-size: 1.8rem;
      }

      .right-panel {
        padding: 30px 15px;
      }

      .form-container {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <!-- Left Panel -->
  <div class="left-panel">
    <h1>Welcome Back!<br>Let's Access Your Portal</h1>
  </div>

  <!-- Right Panel -->
  <div class="right-panel">
    <div class="form-container">
      <h2>Login</h2>
      <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required value="<?php echo $_COOKIE['username'] ?? ''; ?>">

        <label>Password</label>
        <input type="password" name="password" required value="<?php echo $_COOKIE['password'] ?? ''; ?>">

        <label><input type="checkbox" name="remember" <?php echo isset($_COOKIE['username']) ? 'checked' : ''; ?>> Remember Me</label>

        <input type="submit" class="login-btn" value="Login">

        <?php if (!empty($message)): ?>
          <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>
      </form>
    </div>
  </div>
</body>
</html>

<?php
session_start();
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    // Cookie handling
    if ($remember) {
        setcookie("username", $username, time() + (86400 * 7), "/");
        setcookie("password", $password, time() + (86400 * 7), "/");
    } else {
        setcookie("username", "", time() - 3600, "/");
        setcookie("password", "", time() - 3600, "/");
    }

    // Check credentials in database
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            header("Location: home.php");
            exit;
        } else {
            $message = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        $message = "Database error: " . $e->getMessage();
    }
}
?>

