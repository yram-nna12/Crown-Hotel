
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Crown Tower Hotel</title>
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
      <a href="../user_hotel_list/index.html" class="btn booking" style="color: #0a2240;">Booking</a>
      <a href="./index.php" class="btn home">Home</a>
      <a href="../AccountDetails/account.php" class="btn home">Account</a>
    </nav>
  </header>

  <section class="hero">
    <div class="hero-content">
    
      <h1 class="tagline">“Where Elegance is for Everyone”</h1>
      <p>
        Crown Hotel is your destination for a refined and relaxing stay. We combine modern comfort, elegant design, and exceptional service to create a welcoming experience for every guest. Whether for business or leisure, you'll feel right at home.
      </p>

      <form action="../hotel list/index.html" method="GET" class="search-bar">
        <div class="field">
          <label class="form-label">Location</label>
          <input type="text" name="location" placeholder="Where are you staying" required />
        </div>
        <div class="field">
          <label class="form-label">Check In</label>
          <input type="date" name="checkin" required />
        </div>
        <div class="field">
          <label class="form-label">Check Out</label>
          <input type="date" name="checkout" required />
        </div>
        <div class="field">
          <label class="form-label">Guest</label>
          <input type="number" name="guests" placeholder="How many are you" min="1" required />
        </div>
        <button type="submit" class="search-btn" title="Search">
          <img src="../assets/img/SEARCH.png" alt="Search" />
        </button>
      </form>
    </div>
  </section>

  <section class="recent-bookings">
    <h2 class="section-title">Recent Books</h2>

    <!-- Booking Card 1 -->
    <div class="booking-card">
      <div class="booking-details">
        <img src="../hotel list/assets/img/28.png" alt="Room Image" class="room-image" />
        <div class="booking-info">
          <h3 class="hotel-name">Crown Hotel at <span>Legaspi</span> <span class="book-date">Date Booked</span></h3>
          <p class="room-type">Deluxe Room</p>
          <div class="stars">★★★★☆</div>
          <p class="occupancy">2-4 persons</p>
          <ul class="amenities">
            <li>✔ Bed</li>
            <li>✔ Free Breakfast</li>
            <li>✔ Free Wifi</li>
            <li>✔ Free Use of Amenities</li>
            <li>✔ Free Parking</li>
          </ul>
        </div>
      </div>
      <div class="booking-status">
        <div class="price">₱ 1,350</div>
        <small>Included Tax</small>
        <button class="pay-now">Pay Now</button>
      </div>
    </div>

    <!-- Booking Card 2 -->
    <div class="booking-card">
      <div class="booking-details">
        <img src="../hotel list/assets/img/antipolo.png" alt="Room Image" class="room-image" />
        <div class="booking-info">
          <h3 class="hotel-name">Crown Hotel at <span>San Roque, Rizal</span> <span class="book-date">Date Booked</span></h3>
          <p class="room-type">Deluxe Room</p>
          <div class="stars">★★★☆☆</div>
          <p class="occupancy">2-4 persons</p>
          <ul class="amenities">
            <li>✔ Bed</li>
            <li>✔ Free Breakfast</li>
            <li>✔ Free Wifi</li>
            <li>✔ Free Use of Amenities</li>
            <li>✔ Free Parking</li>
          </ul>
        </div>
      </div>
      <div class="booking-status">
        <div class="price">₱ 1,650</div>
        <small>Included Tax</small>
        <span class="paid-label">PAID</span>
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
