<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create Account</title>
  <link rel="stylesheet" href="./assest/css/style.css"/>
</head>
<body>

  <div class="container">
    <div class="left-panel">
      <img src="./assest/img/logo.png" alt="Logo" class="logo"/>
    </div>

    <div class="right-panel">
      <form action="register.php" method="POST" class="form">
        <h2>CREATE ACCOUNT</h2>

        <!-- Error message -->
        <?php
        // Display register error
        if (isset($_SESSION['register_error'])) {
  echo "<p style='color: white; text-align: center; font-weight: bold; margin-bottom: 10px;'>" . $_SESSION['register_error'] . "</p>";
  unset($_SESSION['register_error']);
}

// Display login error redirected from login.php
if (isset($_SESSION['login_error'])) {
  echo "<p style='color: white; text-align: center; font-weight: bold; margin-bottom: 10px;'>" . $_SESSION['login_error'] . "</p>";
  unset($_SESSION['login_error']);
}


        // Fetch old values if available
        $old = $_SESSION['old_input'] ?? [];
        ?>

        <div class="form-group">
          <input type="text" name="first_name" placeholder="First Name" required
            value="<?php echo htmlspecialchars($old['first_name'] ?? ''); ?>" />
          <input type="text" name="phone" placeholder="ex. 09562321354" required
            value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>" />
        </div>

        <div class="form-group">
          <input type="text" name="last_name" placeholder="Last Name" required
            value="<?php echo htmlspecialchars($old['last_name'] ?? ''); ?>" />
          <input type="password" name="password" placeholder="Password" required />
        </div>

        <div class="form-group">
          <input type="email" name="email" placeholder="example@gmail.com" required
            value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" />
          <input type="password" name="confirm_password" placeholder="Confirm Password" required />
        </div>
        
        <div class="checkbox">
          <input type="checkbox" name="terms" required/>
          <label>I agree to the terms of services and privacy policy</label>
        </div>

        <button type="submit" class="signup-btn">Sign Up</button>

        <div class="divider">Or Sign Up With</div>
        <div class="social-icons">
          <a href="https://www.facebook.com/" target="_blank">
            <img src="./assest/img/facebook.png" alt="Facebook" />
          </a>
          <a href="https://www.google.co.uk/" target="_blank">
            <img src="./assest/img/google.png" alt="Google" />
          </a>
        </div>

        <p class="footer">@crownhotel25</p>
      </form>
    </div>
  </div>

</body>
</html>

<?php
// Clear old input after loading
unset($_SESSION['old_input']);
?>
