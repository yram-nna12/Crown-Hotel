<?php
session_start();

// Retrieve session data
$email = $_SESSION['email'] ?? 'Not set';
$first_name = $_SESSION['first_name'] ?? '';
$last_name = $_SESSION['last_name'] ?? '';
$contact = $_SESSION['contact'] ?? '';
$check_in = $_SESSION['check_in'] ?? '';
$check_out = $_SESSION['check_out'] ?? '';
$room_type = $_SESSION['room_type'] ?? 'Standard Room';

$full_name = trim($first_name . ' ' . $last_name);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment Page</title>
  <link rel="stylesheet" href="./assets/css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <header class="topbar">
    <div class="logo">
      <img src="../assets/img/LOGO.png" alt="Crown Tower Logo">
    </div>
    <nav class="nav-links">
      <a href="../user_hotel_list/index.html" class="btn booking" style="color: #0a2240;">Booking</a>
      <a href="../user_landing_page/index.php" class="btn home" style="color: #d9a441">Home</a>
      <a href="../AccountDetails/account.php" class="btn home" style="color: #d9a441">Account</a>
    </nav>
  </header>

  <div class="payment">
    <div class="left-panel">
      <div class="payment-method-container">
        <h3>Payment Method</h3>
        <h5 class="cancellation-policy">Cancellations are subject to a penalty: 20% of the room rate if cancelled within 2 days of check-in, 15% if cancelled 4 days prior, and 10% if cancelled 5 days or more in advance (based on 24-hour format).</h5>

        <p class="method-label">E-Wallet / E-Money</p>
        <div class="payment-buttons">
          <button class="gcash">Gcash</button>
          <button class="paymaya">Paymaya</button>
          <button class="gotyme">GoTyme</button>
        </div>

        <p class="method-label">Credit / Debit Card</p>
        <div class="payment-buttons">
          <button class="bdo">BDO</button>
          <button class="bpi">BPI</button>
          <button class="metrobank">Metrobank</button>
        </div>
      </div>
    </div>

<div class="right-panel">
  <div class="booking-box">
    <h3>Crown Hotel at Legaspi</h3>
    <p class="location">Pasay City, Metro Manila</p>
    
<div class="info-box">Transaction ID: <?= htmlspecialchars($_SESSION['transaction_id'] ?? 'Unavailable') ?></div>
<div class="info-box">Name: <?= htmlspecialchars($full_name) ?></div>
<div class="info-box">Email: <?= htmlspecialchars($email) ?></div>
<div class="info-box">Contact: <?= htmlspecialchars($contact) ?></div>
<div class="info-box">Check-in: <?= htmlspecialchars($check_in) ?></div>
<div class="info-box">Check-out: <?= htmlspecialchars($check_out) ?></div>
<div class="info-box">Room Type: <?= htmlspecialchars($room_type) ?></div>


    <div class="total-section">
      <p class="total-label">Total Payment<br><span>Included Tax</span></p>
      <p class="price">₱1,200</p> <!-- Optional: Make this dynamic -->
    </div>
    
    <button class="pay-button" id="open-popup">Pay Now</button>
  </div>
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
    <div class="footer-line"></div>
    <div class="footer-bottom">
      <span>@2025 CrownHotel All Rights Reserved</span>
      <div class="footer-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms & Condition</a>
      </div>
    </div>
  </footer>

  <!-- POPUP PAYMENT FORM -->
  <div id="payment-popup" class="popup-overlay">
    <div class="popup-content">
      <span id="close-popup" class="close-btn">&times;</span>
      <h2>AMOUNT TO BE PAID</h2>
      <p class="payment-note">Total Payment<br><strong>₱1,200</strong></p>
      <input type="text" placeholder="Number">
      <input type="text" placeholder="Full Name">
      <p class="secure-msg">Your payment is 100% secure. All processes are encrypted.</p>
      <button class="confirm-btn">CONFIRM PAYMENT</button>
    </div>
  </div>

<!-- CARD POPUP FOR BANK PAYMENTS -->
<div class="popup-overlay" id="card-popup">
  <div class="popup-content">
    <span class="close-btn" id="close-card-popup">&times;</span>
    <h2>AMOUNT TO BE PAID</h2>
    <p class="payment-note">Total Payment<br><strong>₱ 1,200</strong></p>

    <input type="text" placeholder="Card Number" maxlength="19" />
    <div style="display: flex; gap: 10px;">
      <input type="text" placeholder="Expiry MM/YY" maxlength="5" />
      <input type="text" placeholder="CVV" maxlength="3" />
    </div>
    <input type="text" placeholder="Full Name" />

    <p class="secure-msg">
      Your payment is 100% secure. All process will be conducted through encrypted network.
      Please call our customer service if there's problem.
    </p>

    <button class="confirm-btn">CONFIRM PAYMENT</button>
  </div>
</div>


  <script src="payment.js"></script>
</body>
</html>