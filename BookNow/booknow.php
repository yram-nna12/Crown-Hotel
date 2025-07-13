<?php
// booknow.php
$hotel_name = $_GET['hotel'] ?? 'Crown Hotel';
$room_type = $_GET['room'] ?? 'Standard Room';
$room_price = $_GET['price'] ?? 1200;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Book Now</title>
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
      <a href="" class="btn booking" style="color:  #0a2240; background-color: #ffffff; font-weight: 600;">Booking</a>
      <a href="../user_landing_page/index.php" style="color:  #d9a441; font-weight: 600;" class="btn home">Home</a>
      <a href="../" style="color:  #d9a441; font-weight: 600;" class="btn home">Account</a>
    </nav>
  </header>

  <main class="payment">
    <div class="left-panel">
      <h2 class="booking-title">Input Your Details</h2>
      <p class="note cancellation-policy">
        Cancellations are subject to a penalty: 20% of the room rate if cancelled within 2 days of check-in, 15% if cancelled 4 days prior, and 10% if cancelled 5 days or more in advance (based on 24-hour format).
      </p>

      <form method="POST" action="./process_booking.php">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <div class="name-fields">
          <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" placeholder="First Name" required>
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" placeholder="Last Name" required>
          </div>
        </div>

        <label>Contact Number</label>
        <input type="tel" name="contact" placeholder="Mobile or phone number" required>

        <div class="row-fields">
          <div class="form-group">
            <label>Check In</label>
            <input type="date" name="check_in" required>
          </div>
          <div class="form-group">
            <label>Check Out</label>
            <input type="date" name="check_out" required>
          </div>
        </div>

        <!-- ✅ Hidden inputs for dynamic data -->
        <input type="hidden" name="hotel_name" value="<?= htmlspecialchars($hotel_name) ?>">
        <input type="hidden" name="room_type" value="<?= htmlspecialchars($room_type) ?>">
        <input type="hidden" name="room_price" value="<?= htmlspecialchars($room_price) ?>">

        <button type="submit" class="btn submit">Proceed</button>
      </form>
    </div>

    <div class="right-panel">
      <!-- Optional: Booking summary or image -->
    </div>
  </main>

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
</body>
</html>
