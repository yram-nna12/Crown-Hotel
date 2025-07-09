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
            <a href="#">Home</a>
            <a href="./AboutUs/index.php">About Us</a>
            <a href="./LoginPage/index.php" class="btn login" style="color: #0a2240;">Log in</a>
            <a href="./Signup_page/index.php" class="btn signup" >Sign up</a>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1 class="tagline">“Where Elegance is for Everyone”</h1>
            <p>
                Crown Hotel is your destination for a refined and relaxing stay. We combine modern comfort, elegant design, and exceptional service to create a welcoming experience for every guest. Whether for business or leisure, you'll feel right at home.
            </p>

            <form action="./hotel list/index.html" method="GET" class="search-bar">
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
                    <img src="./assets/img/SEARCH.png" alt="Search" />
                </button>
            </form>
        </div>
    </section>
        </div>
    </section>

    <section class="about-section">
        <h2 class="about-title">
            We Value Your <span class="highlight-gold">Stay</span>, 
            Your <span class="highlight-gold">Comfort</span>, 
            Your <span class="highlight-gold">Experience</span>
        </h2>
        <p class="about-desc">
            At Crown Hotel, we’re dedicated to making every stay memorableoffering elegant rooms, thoughtful service, and a warm atmosphere that feels like home. Whether you're here to relax, explore, or celebrate, we ensure every detail is designed around your comfort and satisfaction.
        </p>
    </section>

    <section class="roomtype">
        <h2 class="section-title">Explore Room <span>Types</span></h2>
        <p class="section-subtitle">
            Explore our range of beautifully designed rooms, each crafted for comfort, style,<br>and a memorable stay
        </p>

        <div class="room-cards">
            <div class="room-card">
                <img src="./assets/img/SUITE_ROOM.png" alt="Suite Room">
                <div class="card-body">
                    <div class="stars">★★★★☆</div>
                    <h3>Suite Room</h3>
                    <p class="persons">3–7 persons</p>
                    <p class="desc"> A luxurious option with separate living and sleeping areas for an elevated stay.</p>
                </div>
            </div>

            <div class="room-card">
                <img src="./assets/img/SUPERIOR_ROOM.png" alt="Superior Room">
                <div class="card-body">
                    <div class="stars">★★★★☆</div>
                    <h3>Superior Room</h3>
                    <p class="persons">2–4 persons</p>
                    <p class="desc">A refined upgrade with enhanced features and elegant design.</p>
                </div>
            </div>

            <div class="room-card">
                <img src="./assets/img/STANDARD_ROOM.png" alt="Standard Room">
                <div class="card-body">
                    <div class="stars">★★★★☆</div>
                    <h3>Standard Room</h3>
                    <p class="persons">1–3 persons</p>
                    <p class="desc">A cozy, well-appointed space perfect for solo travelers or couples.</p>
                </div>
            </div>

            <div class="room-card">
                <img src="./assets/img/DELUXE_ROOM_TYPE.png" alt="Deluxe Room Type">
                <div class="card-body">
                    <div class="stars">★★★★☆</div>
                    <h3>Deluxe Room Type</h3>
                    <p class="persons">2–4 persons</p>
                    <p class="desc">Offers more space and added comfort with stylish furnishings</p>
                </div>
            </div>
        </div>

        <div class="pagination-dots">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot active"></span>
            <span class="dot"></span>
        </div>
    </section>

    <section class="exceptional-comforts">
        <h2 class="exceptional-title">Exceptional <span class="highlight-gold">Comforts</span> Await You</h2> <div class="comfort-features">
            <div class="comfort-item">
                <img src="./assets/img/ELEGANT_ROOMS.png" alt="">
                <h3>Elegant Rooms</h3>
                <p>Relax in tastefully designed rooms with modern finishes, plush bedding, and calming ambiance for a truly restful stay.</p>
            </div>
            <div class="comfort-item">
                <img src="./assets/img/ROOFTOP_LOUNGE.png" alt="Rooftop Lounge Icon">
                <h3>Rooftop Lounge</h3>
                <p>Unwind above the city with stunning views, refreshing drinks, and a perfect space to socialize or reflect.</p>
            </div>
            <div class="comfort-item">
                <img src="./assets/img/ALL_DAY_DINING.png" alt="All-Day Dining Icon">
                <h3>All-Day Dining</h3>
                <p>Enjoy locally inspired dishes and international favorites served fresh from our in-house restaurant.</p>
            </div>
            <div class="comfort-item-wellness">
                <img src="./assets/img/WELLNESS_CORNER.png" alt="">
                <h3>Wellness Corner</h3>
                <p>Recharge with our gym, spa, or peaceful outdoor spaces crafted to elevate your mind and body.</p>
            </div>
        </div>
        <div class="section-divider"></div>
    </section>


<section class="reviews">
  <h2 class="reviews-title">
    <span class="highlight-gold">Reviews</span> From Customers
  </h2>

  <div class="review-slider">
    <div class="review-cards" id="reviewCards">
      <div class="review-card">
        <div class="stars">★★★★★</div>
        <div class="name">Customer Name</div>
        <div class="text">Review will appear here.</div>
      </div>
      <div class="review-card">
        <div class="stars">★★★★★</div>
        <div class="name">Customer Name</div>
        <div class="text">Review will appear here.</div>
      </div>
      <div class="review-card">
        <div class="stars">★★★★★</div>
        <div class="name">Customer Name</div>
        <div class="text">Review will appear here.</div>
      </div>
      <div class="review-card">
        <div class="stars">★★★★★</div>
        <div class="name">Customer Name</div>
        <div class="text">Review will appear here.</div>
      </div>
      <div class="review-card">
        <div class="stars">★★★★☆</div>
        <div class="name">Customer Name</div>
        <div class="text">Review will appear here.</div>
      </div>
      <div class="review-card">
        <div class="stars">★★★★☆</div>
        <div class="name">Customer Name</div>
        <div class="text">Review will appear here.</div>
      </div>
    </div>
  </div>

  <!-- Move dots OUTSIDE the slider -->
<div id="reviewDots" class="pagination-dots">
  <span class="dot"></span>
  <span class="dot"></span>
  <span class="dot"></span>
  <span class="dot"></span>
</div>

</section>

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




    <script src="typeroom.js"></script>
    <script src="reviews.js"></script>

</body>
</html>