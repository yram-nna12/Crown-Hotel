<?php
session_start();
require_once 'crowndb.php';

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ./LoginPage/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';

// ✅ Update Profile Info
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, phone=? WHERE id=?");
    $stmt->bind_param("sssi", $first_name, $last_name, $phone, $user_id);
    $stmt->execute();
}

// ✅ Change Password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $new_password = $_POST['new_password'];

    if (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hashed, $user_id);
        $stmt->execute();
        session_destroy();
        header("Location: ./LoginPage/login.php?password_changed=true");
        exit();
    }
}

// ✅ Fetch user data
$stmt = $conn->prepare("SELECT first_name, last_name, email, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crown Tower Hotel</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="topbar">
        <div class="logo">
            <img src="./assets/img/LOGO.png" alt="Crown Tower Logo">
        </div>
        <nav class="nav-links">
            <a href="./LoginPage/index.php" class="btn login" style="color: #0a2240;">Booking</a>
            <a href="#">Home</a>
            <a href="" ></a>
        </nav>
    </header>
    

  <div class="account-container">
    <img src="assets/img/LOGO.png" alt="Logo" class="logo">
    <h1>
      Hi <?= htmlspecialchars($user['first_name']) ?>!
      <span class="icon-buttons">
        <button id="edit-btn" onclick="enableEdit()">✏️ Edit</button>
        <button id="save-btn" type="submit" form="profile-form" name="save_profile" style="display:none;">💾 Save</button>
      </span>
    </h1>

    <form id="profile-form" method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="text" value="<?= htmlspecialchars($user['email']) ?>" readonly>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" class="editable" value="<?= htmlspecialchars($user['first_name']) ?>" readonly>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" class="editable" value="<?= htmlspecialchars($user['last_name']) ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="phone" class="editable" value="<?= htmlspecialchars($user['phone']) ?>" readonly>
        </div>
    </form>

    <form method="POST" class="form-group password-form">
        <label>Change Password</label>
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <button type="submit">Change Password</button>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    </form>
  </div>

<footer class="footer">
  <div class="footer-content">
    <div class="footer-left">
      <img src="./assets/img/LOGO.png" alt="Crown Hotel Logo" class="footer-logo">
      <p class="footer-description">
        Offers a seamless stay with elegant rooms, warm hospitality, and everything you need to relax or recharge.
      </p>
    </div>
    <div class="footer-right">
      <h3 class="footer-contact-title">Get in Touch</h3>
      <ul class="footer-contact-list">
        <li><i class="fas fa-map-marker-alt icon"></i> Sampaloc, Manila</li>
        <li><i class="fas fa-phone icon"></i> 0227336689</li>
        <li><i class="fas fa-envelope icon"></i> Crownhotel07@gmail.com</li>
      </ul>
    </div>
  </div>

  <!-- Updated line with white circles -->
  <div class="footer-line"></div>

  <div class="footer-bottom">
    <span>@2025 CrownHotel All Rights Reserved</span>
    <div class="footer-links">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms & Condition</a>
    </div>
  </div>
</footer>

</body>
</html>