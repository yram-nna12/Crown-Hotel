<!DOCTYPE html>
<html>
<head>
<title>Login Module</title>
</head>
<body>
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
  background: url('./img/bg2.jpg') no-repeat center center;
  background-size: cover;
  flex-direction: row;
}

.form-panel {
  min-height: 100vh;
  background-size: cover;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 60px 20px;
  width: 100%;
}
  
  .form-container {
    background: linear-gradient(to bottom,rgba(241, 246, 247, 0.6),rgba(255, 255, 255, 0.79));
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.53);
    width: 100%;
    max-width: 400px;
  }
  
  h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #012d42;
    font-size: 2rem;
  }
  
  input[type="text"],
  input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-top: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    margin-bottom: 16px;
  }
  
  .register-btn {
    width: 100%;
    padding: 14px;
    background: linear-gradient(to bottom, #012d42, #0478bb);
    color: white;
    font-weight: bold;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  
  .register-btn:hover {
    background: #012d42;
  }
  
  .result {
    margin-top: 20px;
    padding: 15px;
    border-radius: 6px;
    background: #e8f4f5;
    color: #2e717d;
    font-size: 14px;
    height: 80px;
    width: 100px;
  }
  
  /* Responsive Design */
  @media (max-width: 768px) {
    .form-panel {
      padding: 40px 15px;
    }
  
    .form-container {
      padding: 30px 20px;
    }
  }
  
</style>
<div class="form-panel">
  <div class="form-container">
    <form method="POST">
      <h2>Login</h2>

      <label>Username</label><br>
      <input type="text" name="username" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>" required><br><br>

      <label>Password</label><br>
      <input type="password" name="password" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>" required><br><br>

      <label><input type="checkbox" name="remember" <?php echo (isset($_COOKIE['username'])) ? 'checked' : ''; ?>> Remember Me</label><br><br>

      <input type="submit" class="register-btn" value="Login">
    </form>

  </div>
</div>
</body>
</html>

<?php
require 'config.php'; // include DB connection at the top

session_start(); // start session to store login info

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    // Look up user in DB
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Successful login
        echo "<div class='result'>Login successful.</div>";

        // Start session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Set cookies
        if ($remember) {
            setcookie("username", $username, time() + (86400 * 7), "/");
            setcookie("password", $password, time() + (86400 * 7), "/");
        } else {
            setcookie("username", "", time() - 3600, "/");
            setcookie("password", "", time() - 3600, "/");
        }

        // Redirect or load dashboard...
        // header("Location: dashboard.php");
    } else {
        echo "<div class='error'>Invalid username or password.</div>";
    }
}
?>

