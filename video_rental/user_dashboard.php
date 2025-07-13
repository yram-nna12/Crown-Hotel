<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['userlevel'] != 'user') {
    header("Location: login.php");
    exit();
}
include 'db.php';

$user = $_SESSION['user'];

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    include 'upload_image.php';
    $image = uploadImage($_FILES['image']);
    $sql = "UPDATE users SET image='$image' WHERE id={$user['id']}";
    $conn->query($sql);
    $_SESSION['user']['image'] = $image;
    $user['image'] = $image;
    $success_message = "Profile image updated successfully!";
}

// Get user's active rentals
$user_rentals = $conn->query("
    SELECT r.*, m.title, m.image as movie_image, m.price 
    FROM rentals r 
    JOIN movies m ON r.movie_id = m.id 
    WHERE r.user_id = {$user['id']} AND r.status = 'active'
    ORDER BY r.rental_date DESC
");

// Get available movies
$available_movies = $conn->query("
    SELECT * FROM movies 
    WHERE stock_quantity > 0 AND status = 'available'
    ORDER BY title ASC
");

// Handle movie rental
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rent_movie'])) {
    $movie_id = $_POST['movie_id'];
    $due_date = date('Y-m-d H:i:s', strtotime('+7 days'));
    
    $stmt = $conn->prepare("INSERT INTO rentals (user_id, movie_id, due_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user['id'], $movie_id, $due_date);
    
    if ($stmt->execute()) {
        // Update movie stock
        $conn->query("UPDATE movies SET stock_quantity = stock_quantity - 1 WHERE id = $movie_id");
        $success_message = "Movie rented successfully!";
        // Refresh the page to show updated data
        header("Location: user_dashboard.php");
        exit();
    } else {
        $error_message = "Failed to rent movie.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Video Rental</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .navbar-nav {
            display: flex;
            gap: 2rem;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .welcome-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: bold;
        }

        .user-info h1 {
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .user-info p {
            color: #666;
            margin-bottom: 0.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .section h2 {
            color: #333;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .movies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .movie-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .movie-card:hover {
            transform: translateY(-5px);
        }

        .movie-image {
            width: 100%;
            height: 200px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 3rem;
        }

        .movie-info {
            padding: 1.5rem;
        }

        .movie-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .movie-details {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .movie-price {
            color: #667eea;
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .rent-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .rent-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .rental-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .rental-item:last-child {
            border-bottom: none;
        }

        .rental-image {
            width: 60px;
            height: 60px;
            background: #f0f0f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
        }

        .rental-info {
            flex: 1;
        }

        .rental-title {
            font-weight: bold;
            color: #333;
        }

        .rental-details {
            color: #666;
            font-size: 0.9rem;
        }

        .due-date {
            color: #dc3545;
            font-weight: bold;
        }

        .profile-form {
            max-width: 400px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
        }

        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-film"></i> Video Rental
        </div>
        <div class="navbar-nav">
            <a href="user_dashboard.php" class="nav-link">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="user_profile.php" class="nav-link">
                <i class="fas fa-user"></i> Profile
            </a>
            <a href="user_history.php" class="nav-link">
                <i class="fas fa-history"></i> History
            </a>
            <a href="logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <div class="container">
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="welcome-section">
            <div class="user-avatar">
                <?php if ($user['image']): ?>
                    <img src="uploads/<?php echo $user['image']; ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                <?php else: ?>
                    <i class="fas fa-user"></i>
                <?php endif; ?>
            </div>
            <div class="user-info">
                <h1>Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</h1>
                <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><i class="fas fa-circle" style="color: #28a745;"></i> Status: <?php echo ucfirst($user['status']); ?></p>
            </div>
        </div>

        <div class="section">
            <h2><i class="fas fa-clipboard-list"></i> My Active Rentals</h2>
            <?php if ($user_rentals->num_rows > 0): ?>
                <?php while ($rental = $user_rentals->fetch_assoc()): ?>
                    <div class="rental-item">
                        <div class="rental-image">
                            <?php if ($rental['movie_image']): ?>
                                <img src="uploads/<?php echo $rental['movie_image']; ?>" alt="Movie" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                            <?php else: ?>
                                <i class="fas fa-film"></i>
                            <?php endif; ?>
                        </div>
                        <div class="rental-info">
                            <div class="rental-title"><?php echo htmlspecialchars($rental['title']); ?></div>
                            <div class="rental-details">
                                Rented on <?php echo date('M j, Y', strtotime($rental['rental_date'])); ?>
                                <br>
                                <span class="due-date">Due: <?php echo date('M j, Y', strtotime($rental['due_date'])); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: #666; padding: 2rem;">No active rentals. Browse movies below to get started!</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2><i class="fas fa-film"></i> Available Movies</h2>
            <div class="movies-grid">
                <?php while ($movie = $available_movies->fetch_assoc()): ?>
                    <div class="movie-card">
                        <div class="movie-image">
                            <?php if ($movie['image']): ?>
                                <img src="uploads/<?php echo $movie['image']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <i class="fas fa-film"></i>
                            <?php endif; ?>
                        </div>
                        <div class="movie-info">
                            <div class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></div>
                            <div class="movie-details">
                                <div><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?></div>
                                <div><strong>Year:</strong> <?php echo $movie['release_year']; ?></div>
                                <div><strong>Director:</strong> <?php echo htmlspecialchars($movie['director']); ?></div>
                                <div><strong>Duration:</strong> <?php echo $movie['duration']; ?> min</div>
                            </div>
                            <div class="movie-price">$<?php echo number_format($movie['price'], 2); ?></div>
                            <form method="post">
                                <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
                                <button type="submit" name="rent_movie" class="rent-btn">
                                    <i class="fas fa-play"></i> Rent Now
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html> 