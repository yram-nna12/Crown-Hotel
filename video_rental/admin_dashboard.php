<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['userlevel'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'db.php';

$user = $_SESSION['user'];

// Get statistics
$stats = [];
$result = $conn->query("SELECT COUNT(*) as total FROM users WHERE userlevel = 'user'");
$stats['users'] = $result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(*) as total FROM movies");
$stats['movies'] = $result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(*) as total FROM rentals WHERE status = 'active'");
$stats['active_rentals'] = $result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(*) as total FROM rentals WHERE status = 'overdue'");
$stats['overdue_rentals'] = $result->fetch_assoc()['total'];

// Get recent rentals
$recent_rentals = $conn->query("
    SELECT r.*, u.username, m.title 
    FROM rentals r 
    JOIN users u ON r.user_id = u.id 
    JOIN movies m ON r.movie_id = m.id 
    ORDER BY r.rental_date DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Video Rental</title>
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
        }

        .welcome-section h1 {
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .action-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .action-btn i {
            font-size: 1.5rem;
        }

        .recent-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .recent-section h2 {
            color: #333;
            margin-bottom: 1.5rem;
        }

        .rental-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .rental-item:last-child {
            border-bottom: none;
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

        .rental-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status-active {
            background: #e8f5e8;
            color: #2d5a2d;
        }

        .status-overdue {
            background: #ffe8e8;
            color: #a52a2a;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
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
        <div class="welcome-section">
            <h1>Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</h1>
            <p>Here's what's happening with your video rental system today.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users" style="color: #667eea;"></i>
                </div>
                <div class="stat-number"><?php echo $stats['users']; ?></div>
                <div class="stat-label">Total Users</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-film" style="color: #28a745;"></i>
                </div>
                <div class="stat-number"><?php echo $stats['movies']; ?></div>
                <div class="stat-label">Total Movies</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-check" style="color: #ffc107;"></i>
                </div>
                <div class="stat-number"><?php echo $stats['active_rentals']; ?></div>
                <div class="stat-label">Active Rentals</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i>
                </div>
                <div class="stat-number"><?php echo $stats['overdue_rentals']; ?></div>
                <div class="stat-label">Overdue Rentals</div>
            </div>
        </div>

        <div class="actions-grid">
            <a href="admin_add_user.php" class="action-btn">
                <i class="fas fa-user-plus"></i>
                <span>Add User</span>
            </a>
            <a href="admin_add_movie.php" class="action-btn">
                <i class="fas fa-plus"></i>
                <span>Add Movie</span>
            </a>
            <a href="admin_rentals.php" class="action-btn">
                <i class="fas fa-clipboard-list"></i>
                <span>Manage Rentals</span>
            </a>
            <a href="admin_reports.php" class="action-btn">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
        </div>

        <div class="recent-section">
            <h2><i class="fas fa-clock"></i> Recent Rentals</h2>
            <?php if ($recent_rentals->num_rows > 0): ?>
                <?php while ($rental = $recent_rentals->fetch_assoc()): ?>
                    <div class="rental-item">
                        <div class="rental-info">
                            <div class="rental-title"><?php echo htmlspecialchars($rental['title']); ?></div>
                            <div class="rental-details">
                                Rented by <?php echo htmlspecialchars($rental['username']); ?> on 
                                <?php echo date('M j, Y', strtotime($rental['rental_date'])); ?>
                            </div>
                        </div>
                        <div class="rental-status status-<?php echo $rental['status']; ?>">
                            <?php echo ucfirst($rental['status']); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: #666; padding: 2rem;">No recent rentals found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 