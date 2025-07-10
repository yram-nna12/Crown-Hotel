Hotel Reservation System
A web-based application developed using PHP for managing hotel reservations. This system provides comprehensive functionalities for both administrators and general users (clients) to handle room bookings, user accounts, and hotel details.

            Table of Contents
            Description

            Features

            Installation

            Usage

            File Structure

            Requirements

            Contributing

            License

Description
This project is a complete Hotel Reservation System built with PHP. It allows users to browse available rooms, make reservations, and manage their bookings. Administrators can manage rooms, user accounts, and view all reservations. The system emphasizes CRUD (Create, Read, Update, Delete) operations for all core processes and features, ensuring robust data management. It is designed to be hosted online with a MySQL database backend for information storage.

Features
The application includes the following key functionalities:

User Management:

        Client User Account creation and management.

        Admin Account (Hotel Concierge) for system administration.

Room Management:

        CRUD operations for hotel rooms (add, view, update, delete).

Reservation System:

        Create, Read, Update, and Delete reservations.

Transaction ID Generation: Automatically generated based on:

        TH - First 2 letters from Customer Name

        FEB - Month (day of reservation)

        10-day - Day (added to the system)

        0222 - Year & Month of reservation schedule (e.g., February 22 from 2022)

        FIC - Type of Room (3 letters)

        00001 - Count of reservations

Example: THFEB100222-FIC00001

Online Hosting: Designed to be hosted online for accessibility.

Installation
        To set up the Hotel Reservation System locally, follow these steps:

        Clone the repository:

        git clone <repository_url>
        cd CROWN-HOTEL

        (Replace <repository_url> with the actual URL of your repository)

        Set up a Web Server:
        Ensure you have a web server (e.g., Apache, Nginx) with PHP and MySQL installed. XAMPP or WAMP are popular choices for local development.

Create Database:

        Access your MySQL database (e.g., via phpMyAdmin).

        Create a new database. You can name it hotel_reservation_db or similar.

        Import the provided SQL dump file (if available) to set up the necessary tables and initial data. If not, you will need to create the tables manually based on the application's data models.

Configure Database Connection:

        Navigate to the hotel list/assets/ directory.

        Open config.php and update the database connection details (database name, username, password, host) to match your local setup.

<?php
// Example config.php content
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "hotel_reservation_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

Place Project in Web Server Root:

        Move the CROWN-HOTEL directory into your web server's document root (e.g., htdocs for Apache/XAMPP, www for WAMP).

        Usage
        Start your Web Server and MySQL:
        Ensure Apache/Nginx and MySQL services are running.

        Access the Application:
        Open your web browser and navigate to the project's URL. If placed in htdocs directly, it might be http://localhost/CROWN-HOTEL/. The main landing page is likely user_landing_page/index.php or AboutUs/index.php.

Login/Register:

        If you are a new user, register for a new account via the Signup_page.

        If you have an existing account (or an admin account), log in via the LoginPage.

Explore Features:

        Clients: Browse hotels, view room details, book rooms, manage their bookings (user_booking).

Admins: Access the Dashboard (Dashboard/index.php) to manage rooms, view all bookings, and manage user accounts.

File Structure
The project follows a modular structure, organizing files by functionality.

CROWN-HOTEL/
├── .github/                     # GitHub Actions workflows
│   └── workflows/
│       └── static.yml
├── .qodo/                       # Potentially related to development environment/tools
├── AboutUs/                     # About Us section
│   ├── assets/
│   └── index.php
├── AccountDetails/              # User account details management
│   ├── img/
│   └── assets/
├── BookNow/                     # Booking functionality
│   ├── assets/
│   ├── booknow.php
│   └── process_booking.php
├── Dashboard/                   # Admin Dashboard
│   ├── assets/
│   ├── Bookings/                # Booking management within Dashboard
│   │   └── index.php
│   ├── Rooms/                   # Room management within Dashboard
│   │   ├── assets/
│   │   └── index.php
│   └── Users/                   # User management within Dashboard
│       ├── assets/
│       └── index.php
├── hotel details/               # General hotel details (might be public-facing)
│   ├── assets/
│   ├── book.php
│   ├── get_rooms.php
│   └── hotel-details.html
├── hotel list/                  # List of hotels/rooms
│   ├── assets/
│   │   └── config.php           # Database configuration
│   ├── index.html
│   └── login.html
├── LoginPage/                   # User login interface
│   ├── assets/
│   ├── index.php
│   ├── login.php
│   └── style.css
├── payment/                     # Payment processing
│   ├── assets/
│   ├── js/
│   │   └── payment.js
│   └── payment.php
├── Signup_page/                 # User registration interface
│   ├── assest/                  # Typo: should be 'assets'
│   │   └── css/
│   │   └── img/
│   ├── index.php
│   └── register.php
├── user_booking/                # User's personal booking management
│   ├── book.php
│   ├── get_rooms.php
│   └── index.php
├── user_hotel_list/             # User-facing hotel/room listing
│   ├── assets/
│   ├── index.html
│   └── style.css
├── user_hotelDetails/           # User-facing detailed hotel/room view
│   ├── assets/
│   │   └── img/                 # Room images
│   │       ├── 28.png
│   │       ├── 31.png
│   │       ├── 32.png
│   │       ├── 34.png
│   │       ├── deluxe.png
│   │       ├── espino.png
│   │       ├── room-standard.png
│   │       ├── superior.png
│   │       ├── superior1.png
│   │       ├── west (1).png
│   │       └── west (2).png
│   ├── book.php
│   ├── get_rooms.php
│   ├── hotel-details.html
│   └── style.css
└── user_landing_page/           # Main landing page for users
    ├── config.php
    ├── hash.php
    ├── index.php
    ├── reviews.js
    ├── style.css
    └── typeroom.js

Requirements
To run this project, you will need:

Web Server: Apache or Nginx

PHP: Version 7.x or higher (with MySQLi extension enabled)

Database: MySQL

Browser: A modern web browser (Chrome, Firefox, Edge, Safari)

Contributing
Contributions are welcome! If you'd like to contribute, please follow these steps:

Fork the repository.

Create a new branch (git checkout -b feature/your-feature-name).

Make your changes and commit them (git commit -m 'Add new feature').

Push to the branch (git push origin feature/your-feature-name).

Create a Pull Request.

License
This project is licensed under the MIT License - see the LICENSE file for details.