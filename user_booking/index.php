<?php
session_start();
require_once '../user_landing_page/crowndb.php';

$is_logged_in = isset($_SESSION['user_id']);
$user = [];

if ($is_logged_in) {
    $stmt = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Booking</title>
  <link rel="stylesheet" href="./style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <header class="topbar">
    <div class="logo">
      <img src="../assets/img/LOGO.png" alt="Crown Tower Logo">
    </div>
    <nav class="nav-links">
      <a href="#" class="btn booking" style="color: #0a2240;">Booking</a>
      <a href="../user_landing_page/landing.php" class="btn home">Home</a>
      <?php if ($is_logged_in): ?>
        <a href="../AccountDetails/account.php">
          <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Profile" style="width: 32px; height: 32px; border-radius: 50%; border: 2px solid white;" />
        </a>
      <?php else: ?>
        <a href="../LoginPage/index.php" class="btn">Login</a>
      <?php endif; ?>
    </nav>
  </header>

  <section class="payment">
    <div class="left-panel">
      <h2 class="booking-title">Input Your Details</h2>
      <p class="note cancellation-policy">
        Cancellations are subject to a penalty: 20% of the room rate if cancelled within 2 days of check-in, 15% if cancelled 4 days prior, and 10% if cancelled 5 days or more in advance.
      </p>

      <form>
        <label>Email</label>
        <input type="email" value="<?= $is_logged_in ? htmlspecialchars($user['email']) : '' ?>" placeholder="Enter your email" required>

        <div class="name-fields">
          <div class="form-group">
            <label>First Name</label>
            <input type="text" value="<?= $is_logged_in ? htmlspecialchars($user['first_name']) : '' ?>" placeholder="First Name" required>
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" value="<?= $is_logged_in ? htmlspecialchars($user['last_name']) : '' ?>" placeholder="Last Name" required>
          </div>
        </div>

        <label>Contact Number</label>
        <input type="tel" placeholder="Mobile or phone number" required>

        <div class="row-fields">
          <div class="form-group">
            <label>Type of Room</label>
            <select>
              <option disabled selected>Select Room</option>
              <option>Deluxe</option>
              <option>Suite</option>
              <option>Standard</option>
            </select>
          </div>
          <div class="form-group">
            <label>Branch</label>
            <select>
              <option disabled selected>Select Branch</option>
              <option>San Roque, Antipolo</option>
              <option>Tatlong Hari, Laguna</option>
              <option>Legazpi</option>
            </select>
          </div>
        </div>

        <div class="row-fields">
          <div class="form-group">
            <label>Check In</label>
            <input type="date" required>
          </div>
          <div class="form-group">
            <label>Check Out</label>
            <input type="date" required>
          </div>
        </div>
      </form>
    </div>

    <div class="right-panel">
      <div class="booking-box">
        <h3>Crown Hotel at Legaspi</h3>
        <p class="location">Pasay City, Metro Manila</p>
        <div class="info-box">Guests: Example number</div>
        <div class="info-box">Check-in: Example date</div>
        <div class="info-box">Check-out: Example date</div>
        <div class="info-box">Room Type: Standard Room</div>

        <div class="total-section">
          <p class="total-label">Total Payment<br><span>Included Tax</span></p>
          <p class="price">₱1,200</p>
        </div>
        <button class="pay-button">Pay Now</button>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="footer-content">
      <div class="footer-left">
        <img src="../assets/img/LOGO.png" alt="Crown Hotel Logo" class="footer-logo">
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
