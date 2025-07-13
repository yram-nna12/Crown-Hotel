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
  require 'config.php';

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $fname = htmlspecialchars($_POST['fname']);
      $lname = htmlspecialchars($_POST['lname']);
      $email = htmlspecialchars($_POST['email']);
      $phone = htmlspecialchars($_POST['phone']);
      $birthday = htmlspecialchars($_POST['birthday']);
      $username = htmlspecialchars($_POST['email']); // We’ll use email as username
      $password = $_POST['password'];
      $repeat_password = $_POST['repeat_password'];

      if ($password !== $repeat_password) {
          echo "<div class='error'>Passwords do not match.</div>";
      } else {
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

          try {
              $stmt = $pdo->prepare("INSERT INTO users (fname, lname, email, phone, birthday, username, password) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?)");
              $stmt->execute([$fname, $lname, $email, $phone, $birthday, $username, $hashedPassword]);
              echo "<div class='result'>Registered successfully!</div>";
          } catch (PDOException $e) {
              echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
          }
      }
  }
  ?>
    </form>
  </div>


</body>
</html>
