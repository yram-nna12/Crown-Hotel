<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "video_rental";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create tables if they don't exist
$create_users_table = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    userlevel ENUM('admin', 'user') DEFAULT 'user',
    status ENUM('active', 'inactive') DEFAULT 'active',
    image VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$create_movies_table = "CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    genre VARCHAR(100),
    release_year INT,
    director VARCHAR(255),
    duration INT,
    price DECIMAL(10,2) DEFAULT 0.00,
    stock_quantity INT DEFAULT 1,
    image VARCHAR(255) DEFAULT '',
    status ENUM('available', 'rented') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$create_rentals_table = "CREATE TABLE IF NOT EXISTS rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    movie_id INT,
    rental_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    return_date TIMESTAMP NULL,
    due_date TIMESTAMP NULL,
    status ENUM('active', 'returned', 'overdue') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (movie_id) REFERENCES movies(id)
)";

$conn->query($create_users_table);
$conn->query($create_movies_table);
$conn->query($create_rentals_table);

// Insert default admin user if not exists
$check_admin = "SELECT * FROM users WHERE userlevel = 'admin' LIMIT 1";
$result = $conn->query($check_admin);
if ($result->num_rows == 0) {
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $insert_admin = "INSERT INTO users (email, username, password, userlevel, status) 
                     VALUES ('admin@videorental.com', 'admin', '$admin_password', 'admin', 'active')";
    $conn->query($insert_admin);
}

// Insert sample movies if not exists
$check_movies = "SELECT * FROM movies LIMIT 1";
$result = $conn->query($check_movies);
if ($result->num_rows == 0) {
    $sample_movies = [
        "INSERT INTO movies (title, description, genre, release_year, director, duration, price, stock_quantity) 
         VALUES ('The Shawshank Redemption', 'Two imprisoned men bond over a number of years...', 'Drama', 1994, 'Frank Darabont', 142, 2.99, 3)",
        "INSERT INTO movies (title, description, genre, release_year, director, duration, price, stock_quantity) 
         VALUES ('The Godfather', 'The aging patriarch of an organized crime dynasty...', 'Crime', 1994, 'Francis Ford Coppola', 175, 3.99, 2)",
        "INSERT INTO movies (title, description, genre, release_year, director, duration, price, stock_quantity) 
         VALUES ('Pulp Fiction', 'The lives of two mob hitmen, a boxer, a gangster...', 'Crime', 1994, 'Quentin Tarantino', 154, 2.49, 4)",
        "INSERT INTO movies (title, description, genre, release_year, director, duration, price, stock_quantity) 
         VALUES ('The Dark Knight', 'When the menace known as the Joker wreaks havoc...', 'Action', 2008, 'Christopher Nolan', 152, 3.49, 3)",
        "INSERT INTO movies (title, description, genre, release_year, director, duration, price, stock_quantity) 
         VALUES ('Fight Club', 'An insomniac office worker and a devil-may-care soapmaker...', 'Drama', 1999, 'David Fincher', 139, 2.99, 2)"
    ];
    
    foreach ($sample_movies as $movie) {
        $conn->query($movie);
    }
}
?>