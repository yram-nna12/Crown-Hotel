<?php
session_start();
require_once '../config.php';
// Handle form submission BEFORE any output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['ewallet_submit'])) {
    $number = $_POST['ewallet_number'] ?? '';
    $name = $_POST['ewallet_name'] ?? '';
    if (preg_match('/^\d{11}$/', $number) && preg_match('/^[a-zA-Z\s]+$/', $name)) {
      header("Location: ../user_landing_page/index.php");
      exit();
    } else {
      echo "<script>alert('Invalid E-Wallet details');</script>";
    }
  }

  if (isset($_POST['card_submit'])) {
    $number = $_POST['card_number'] ?? '';
    $expiry = $_POST['expiry'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    $name = $_POST['card_name'] ?? '';
    if (
      preg_match('/^\d{16}$/', $number) &&
      preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry) &&
      preg_match('/^\d{3}$/', $cvv) &&
      preg_match('/^[a-zA-Z\s]+$/', $name)
    ) {
      header("Location: ../user_landing_page/index.php");
      exit();
    } else {
      echo "<script>alert('Invalid Card details');</script>";
    }
  }
}

// Session values
$email = $_SESSION['email'] ?? 'Not set';
$first_name = $_SESSION['first_name'] ?? '';
$last_name = $_SESSION['last_name'] ?? '';
$contact = $_SESSION['contact'] ?? '';
$check_in = $_SESSION['check_in'] ?? '';
$check_out = $_SESSION['check_out'] ?? '';
$room_type = $_SESSION['room_type'] ?? 'Standard Room';
$room_price = $_SESSION['room_price'] ?? 1200;
$total_price = $_SESSION['total_price'] ?? $room_price;
$full_name = trim($first_name . ' ' . $last_name);
$hotel_name = $_SESSION['hotel_name'] ?? 'Crown Hotel at Legaspi';

$locations = [
  'Crown Hotel at Legaspi' => 'Pasay City, Metro Manila',
  'Crown Hotel at Westside City Tambo' => 'Parañaque, Metro Manila',
  'Crown Hotel at General Espino' => 'Taguig, Metro Manila',
  'Crown Hotel at San Roque' => 'San Roque, Antipolo',
  'Crown Hotel at Tatlong Hari' => 'Tatlong Hari, Laguna'
];

$location = $locations[$hotel_name] ?? 'Unknown Location';
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
  <!-- HEADER -->
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

  <!-- PAYMENT SECTION -->
  <div class="payment">
    <div class="left-panel">
      <div class="payment-method-container">
        <h3>Payment Method</h3>
        <h5 class="cancellation-policy">Cancellations are subject to a penalty...</h5>

        <p class="method-label">E-Wallet / E-Money</p>
        <div class="payment-buttons">
          <button class="gcash" onclick="selectPayment('ewallet')">Gcash</button>
          <button class="paymaya" onclick="selectPayment('ewallet')">Paymaya</button>
          <button class="gotyme" onclick="selectPayment('ewallet')">GoTyme</button>
        </div>

        <p class="method-label">Credit / Debit Card</p>
        <div class="payment-buttons">
          <button class="bdo" onclick="selectPayment('card')">BDO</button>
          <button class="bpi" onclick="selectPayment('card')">BPI</button>
          <button class="metrobank" onclick="selectPayment('card')">Metrobank</button>
        </div>
      </div>
    </div>

    <div class="right-panel">
      <div class="booking-box">
        <h3><?= htmlspecialchars($hotel_name) ?></h3>
        <p class="location"><?= htmlspecialchars($location) ?></p>
        <div class="info-box">Transaction ID: <?= htmlspecialchars($_SESSION['transaction_id'] ?? 'Unavailable') ?></div>
        <div class="info-box">Name: <?= htmlspecialchars($full_name) ?></div>
        <div class="info-box">Email: <?= htmlspecialchars($email) ?></div>
        <div class="info-box">Contact: <?= htmlspecialchars($contact) ?></div>
        <div class="info-box">Check-in: <?= htmlspecialchars($check_in) ?></div>
        <div class="info-box">Check-out: <?= htmlspecialchars($check_out) ?></div>
        <div class="info-box">Room Type: <?= htmlspecialchars($room_type) ?></div>
        <div class="total-section">
          <p class="total-label">Total Payment<br><span>Included</span></p>
          <p class="price">₱<?= number_format($total_price, 2) ?></p>
        </div>
        <button class="pay-button" onclick="finalizePayment()">Pay Now</button>
      </div>
    </div>
  </div>

  <!-- POPUPS -->
  <div id="payment-popup" class="popup-overlay">
    <div class="popup-content">
      <span class="close-btn" onclick="closePopup('payment-popup')">&times;</span>
      <h2>AMOUNT TO BE PAID</h2>
      <p class="payment-note">Total Payment<br><strong>₱<?= number_format($total_price, 2) ?></strong></p>
      <input type="text" id="ewallet_number" placeholder="11-digit number" maxlength="11" />
      <input type="text" id="ewallet_name" placeholder="Full Name (letters only)" />
      <p class="secure-msg">Your payment is 100% secure. All processes are encrypted.</p>
    </div>
  </div>

  <div id="card-popup" class="popup-overlay">
    <div class="popup-content">
      <span class="close-btn" onclick="closePopup('card-popup')">&times;</span>
      <h2>AMOUNT TO BE PAID</h2>
      <p class="payment-note">Total Payment<br><strong>₱<?= number_format($total_price, 2) ?></strong></p>
      <input type="text" id="card_number" placeholder="16-digit card number" maxlength="16" />
      <div style="display: flex; gap: 10px;">
        <input type="text" id="expiry" placeholder="MM/YY" maxlength="5" />
        <input type="text" id="cvv" placeholder="CVV" maxlength="3" />
      </div>
      <input type="text" id="card_name" placeholder="Full Name (letters only)" />
      <p class="secure-msg">Your payment is 100% secure. All processes are encrypted.</p>
    </div>
  </div>

  <!-- HIDDEN FORMS -->
  <form id="ewallet-form" method="POST" style="display:none;">
    <input type="hidden" name="ewallet_submit" value="1" />
    <input type="hidden" name="ewallet_number" id="hidden_ewallet_number" />
    <input type="hidden" name="ewallet_name" id="hidden_ewallet_name" />
  </form>

  <form id="card-form" method="POST" style="display:none;">
    <input type="hidden" name="card_submit" value="1" />
    <input type="hidden" name="card_number" id="hidden_card_number" />
    <input type="hidden" name="expiry" id="hidden_expiry" />
    <input type="hidden" name="cvv" id="hidden_cvv" />
    <input type="hidden" name="card_name" id="hidden_card_name" />
  </form>

  <!-- FOOTER -->
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

  <!-- JS -->
  <script>
    let selectedPayment = "";

    function selectPayment(type) {
      selectedPayment = type;
      openPopup(type === 'ewallet' ? 'payment-popup' : 'card-popup');
    }

    function openPopup(id) {
      document.getElementById(id).classList.add("active");
    }

    function closePopup(id) {
      document.getElementById(id).classList.remove("active");
    }

    function finalizePayment() {
      if (selectedPayment === "ewallet") {
        const number = document.getElementById("ewallet_number").value.trim();
        const name = document.getElementById("ewallet_name").value.trim();
        if (!/^\d{11}$/.test(number)) return alert("E-Wallet number must be exactly 11 digits.");
        if (!/^[a-zA-Z\s]+$/.test(name)) return alert("Full name must contain letters only.");
        document.getElementById("hidden_ewallet_number").value = number;
        document.getElementById("hidden_ewallet_name").value = name;
        document.getElementById("ewallet-form").submit();
      } else if (selectedPayment === "card") {
        const number = document.getElementById("card_number").value.trim();
        const expiry = document.getElementById("expiry").value.trim();
        const cvv = document.getElementById("cvv").value.trim();
        const name = document.getElementById("card_name").value.trim();
        if (!/^\d{16}$/.test(number)) return alert("Card number must be exactly 16 digits.");
        if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry)) return alert("Expiry must be in MM/YY format.");
        if (!/^\d{3}$/.test(cvv)) return alert("CVV must be 3 digits.");
        if (!/^[a-zA-Z\s]+$/.test(name)) return alert("Full name must contain letters only.");
        document.getElementById("hidden_card_number").value = number;
        document.getElementById("hidden_expiry").value = expiry;
        document.getElementById("hidden_cvv").value = cvv;
        document.getElementById("hidden_card_name").value = name;
        document.getElementById("card-form").submit();
      } else {
        alert("Please select a payment method first.");
      }
    }
  </script>
</body>
</html>
