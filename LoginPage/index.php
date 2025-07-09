<?php
session_start();
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Log In</title>
  <link rel="stylesheet" href="./assets/css/style.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
  <div class="container">
    <div class="left-panel">
      <img src="./assets/images/logo.png" alt="Logo" class="logo"/>
    </div>

    <div class="right-panel">
      <form action="login.php" method="POST" class="form">
        <h2>LOG IN</h2>

         <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

        <div class="form-group">
          <input type="email" name="email" placeholder="example@gmail.com" required/>
        </div>

        <div class="form-group password-group">
          <input type="password" name="password" placeholder="Password" required/>
          <span class="toggle-password"></span> 
        </div>

        <div class="checkbox">
          <input type="checkbox" name="terms" required/>
          <label>I agree to the terms of services and privacy policy</label>
        </div>

        <button type="submit" class="login-btn">Log In</button>

        <div class="divider">Or Log In With</div>
        <div class="social-icons">

          <a href="https://www.facebook.com/" target="_blank">
          <img src="./assets/images/facebook.png"  alt="Facebook" /></a>

          <a href="https://www.google.co.uk/" target="_blank">
          <img src="./assets/images/google.png" alt="Google" /></a>
          
        </div>

        <p class="footer">@crowntower25</p>
      </form>
    </div>
  </div>

  <script>
  document.querySelector('.toggle-password').addEventListener('click', function () {
    const passwordInput = document.querySelector('input[name="password"]');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    if (type === 'password') {
      // Corrected path for click event
      this.style.backgroundImage = 'url("./assets/images/eye-slash.png")'; 
    } else {
      // Corrected path for click event
      this.style.backgroundImage = 'url("./assets/images/eye.png")'; 
    }
  });

    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.querySelector('input[name="password"]');
        const toggleIcon = document.querySelector('.toggle-password');
        if (passwordInput.getAttribute('type') === 'password') {
            // Corrected path for initial load
            toggleIcon.style.backgroundImage = 'url("./assets/images/eye-slash.png")';
        } else {
            // Corrected path for initial load
            toggleIcon.style.backgroundImage = 'url("./assets/images/eye.png")';
        }
    });
</script>

</body>
</html>
