<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['userlevel'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Handle movie deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $movie_id = $_GET['delete'];
    $conn->query("DELETE FROM movies WHERE id = $movie_id");
    header("Location: admin_movies.php?success=deleted");
    exit();
}

// Get all movies with rental count
$movies = $conn->query("
    SELECT m.*, COUNT(r.id) as rental_count 
    FROM movies m 
    LEFT JOIN rentals r ON m.id = r.movie_id 
    GROUP BY m.id 
    ORDER BY m.title ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Movies - Video Rental Admin</title>
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

        .header-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-section h1 {
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-warning {
            background: #ffc107;
            color: #333;
        }

        .btn-warning:hover {
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
        }

        .movies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .movie-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
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
            position: relative;
        }

        .movie-status {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status-available {
            background: #d4edda;
            color: #155724;
        }

        .status-rented {
            background: #f8d7da;
            color: #721c24;
        }

        .movie-info {
            padding: 1.5rem;
        }

        .movie-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .movie-details {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .movie-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
        }

        .movie-actions {
            display: flex;
            gap: 0.5rem;
        }

        .movie-actions .btn {
            flex: 1;
            padding: 0.5rem;
            font-size: 0.9rem;
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

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .movies-grid {
                grid-template-columns: 1fr;
            }
            
            .header-section {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-film"></i> Video Rental Admin
        </div>
        <div class="navbar-nav">
            <a href="admin_dashboard.php" class="nav-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="admin_users.php" class="nav-link">
                <i class="fas fa-users"></i> Users
            </a>
            <a href="admin_movies.php" class="nav-link">
                <i class="fas fa-film"></i> Movies
            </a>
            <a href="admin_rentals.php" class="nav-link">
                <i class="fas fa-clipboard-list"></i> Rentals
            </a>
            <a href="logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <div class="container">
        <?php if (isset($_GET['success']) && $_GET['success'] == 'deleted'): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Movie deleted successfully!
            </div>
        <?php endif; ?>

        <div class="header-section">
            <h1><i class="fas fa-film"></i> Manage Movies</h1>
            <a href="admin_add_movie.php" class="btn">
                <i class="fas fa-plus"></i> Add New Movie
            </a>
        </div>

        <?php if ($movies->num_rows > 0): ?>
            <div class="movies-grid">
                <?php while ($movie = $movies->fetch_assoc()): ?>
                    <div class="movie-card">
                        <div class="movie-image">
                            <?php if ($movie['image']): ?>
                                <img src="uploads/<?php echo $movie['image']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <i class="fas fa-film"></i>
                            <?php endif; ?>
                            <div class="movie-status status-<?php echo $movie['status']; ?>">
                                <?php echo ucfirst($movie['status']); ?>
                            </div>
                        </div>
                        <div class="movie-info">
                            <div class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></div>
                            <div class="movie-details">
                                <div><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?></div>
                                <div><strong>Year:</strong> <?php echo $movie['release_year']; ?></div>
                                <div><strong>Director:</strong> <?php echo htmlspecialchars($movie['director']); ?></div>
                                <div><strong>Duration:</strong> <?php echo $movie['duration']; ?> min</div>
                            </div>
                            
                            <div class="movie-stats">
                                <div class="stat-item">
                                    <div class="stat-value">$<?php echo number_format($movie['price'], 2); ?></div>
                                    <div class="stat-label">Price</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo $movie['stock_quantity']; ?></div>
                                    <div class="stat-label">Stock</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo $movie['rental_count']; ?></div>
                                    <div class="stat-label">Rentals</div>
                                </div>
                            </div>

                            <div class="movie-actions">
                                <a href="admin_edit_movie.php?id=<?php echo $movie['id']; ?>" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="admin_movies.php?delete=<?php echo $movie['id']; ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this movie?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-film"></i>
                <h2>No Movies Found</h2>
                <p>Get started by adding your first movie to the collection.</p>
                <a href="admin_add_movie.php" class="btn" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i> Add First Movie
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 