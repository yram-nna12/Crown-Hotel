<!DOCTYPE html>
<html>
<head>
  <title>Registration Module</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>

   <!-- Left Panel -->
   <div class="left-panel">
    <h1>WELCOME! </h1>
  </div>

  <!-- Right Form Panel -->
  <div class="form-panel">
    <div class="form-container">
      <form method="POST">
        <h2>Fill up here!</h2>
        <div class="row">
          <input type="text" name="fname" placeholder="First Name" pattern="[A-Za-z\s]+" title="Letters only" required>
          <input type="text" name="lname" placeholder="Last Name" pattern="[A-Za-z\s]+" title="Letters only" required>
        </div>

        <div class="row">
          <input type="email" name="email" placeholder="Email Address" required>
        </div>

        <div class="row">
          <input type="text" name="phone" placeholder="Phone Number" pattern="[0-9]{11}" maxlength="11" title="Enter 11-digit number" required>
          <input type="text" name="birthday" placeholder="Birthday (e.g. January 30, 2000)" required>
        </div>

        <div class="row">
          <input type="password" name="password" placeholder="Password" required>
          <input type="password" name="repeat_password" placeholder="Confirm Password" required>
        </div>

        <button type="submit" class="register-btn">REGISTER NOW!</button>
      <!-- PHP Response Handling -->
      <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fname = htmlspecialchars($_POST['fname']);
        $lname = htmlspecialchars($_POST['lname']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $birthday = htmlspecialchars($_POST['birthday']);
        $password = $_POST['password'];
        $repeat_password = $_POST['repeat_password'];

        if (!preg_match("/^[a-zA-Z\s]+$/", $fname) || !preg_match("/^[a-zA-Z\s]+$/", $lname)) {
          echo "<div class='error'>Names should contain letters only.</div>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          echo "<div class='error'>Invalid email format.</div>";
        } elseif (!preg_match("/^[0-9]{11}$/", $phone)) {
          echo "<div class='error'>Phone number should be exactly 11 digits.</div>";
        } elseif ($password !== $repeat_password) {
          echo "<div class='error'>Password and repeat password do not match.</div>";
        } else {
          echo "<div class='result'>
            <strong>Submitted Information:</strong><br>
            Name: $fname $lname<br>
            Email: $email<br>
            Phone: $phone<br>
            Birthday: $birthday
          </div>";
        }
      }
      ?>
    </form>
  </div>


</body>
</html>
