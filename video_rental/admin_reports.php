<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['userlevel'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Stats
$total_users = $conn->query("SELECT COUNT(*) as total FROM users WHERE userlevel = 'user'")->fetch_assoc()['total'];
$total_movies = $conn->query("SELECT COUNT(*) as total FROM movies")->fetch_assoc()['total'];
$total_rentals = $conn->query("SELECT COUNT(*) as total FROM rentals")->fetch_assoc()['total'];
$active_rentals = $conn->query("SELECT COUNT(*) as total FROM rentals WHERE status = 'active'")->fetch_assoc()['total'];
$overdue_rentals = $conn->query("SELECT COUNT(*) as total FROM rentals WHERE status = 'overdue'")->fetch_assoc()['total'];

// Most rented movies
$most_rented_movies = $conn->query("
    SELECT m.title, COUNT(r.id) as rental_count
    FROM movies m
    JOIN rentals r ON m.id = r.movie_id
    GROUP BY m.id
    ORDER BY rental_count DESC
    LIMIT 5
");

// Top users
$top_users = $conn->query("
    SELECT u.username, COUNT(r.id) as rental_count
    FROM users u
    JOIN rentals r ON u.id = r.user_id
    GROUP BY u.id
    ORDER BY rental_count DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Video Rental Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; color: #333; }
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .navbar-brand { font-size: 1.5rem; font-weight: bold; }
        .navbar-nav { display: flex; gap: 2rem; }
        .nav-link { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; transition: background 0.3s; }
        .nav-link:hover { background: rgba(255,255,255,0.1); }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .header-section { background: white; border-radius: 15px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.08); display: flex; justify-content: space-between; align-items: center; }
        .header-section h1 { color: #667eea; display: flex; align-items: center; gap: 0.5rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 5px 15px rgba(0,0,0,0.08); text-align: center; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon { font-size: 2.5rem; margin-bottom: 1rem; }
        .stat-number { font-size: 2rem; font-weight: bold; color: #667eea; margin-bottom: 0.5rem; }
        .stat-label { color: #666; font-size: 0.9rem; }
        .section { background: white; border-radius: 15px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .section h2 { color: #333; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .list-table { width: 100%; border-collapse: collapse; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .list-table th, .list-table td { padding: 1rem; text-align: left; border-bottom: 1px solid #f0f0f0; }
        .list-table th { background: #f8f9fa; color: #667eea; font-weight: 600; }
        .list-table tr:last-child td { border-bottom: none; }
        @media (max-width: 900px) { .list-table th, .list-table td { padding: 0.5rem; font-size: 0.95rem; } }
        @media (max-width: 600px) { .container { padding: 0.5rem; } .header-section { flex-direction: column; gap: 1rem; text-align: center; } .list-table th, .list-table td { font-size: 0.85rem; } }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand"><i class="fas fa-film"></i> Video Rental Admin</div>
        <div class="navbar-nav">
            <a href="admin_dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="admin_users.php" class="nav-link"><i class="fas fa-users"></i> Users</a>
            <a href="admin_movies.php" class="nav-link"><i class="fas fa-film"></i> Movies</a>
            <a href="admin_rentals.php" class="nav-link"><i class="fas fa-clipboard-list"></i> Rentals</a>
            <a href="admin_reports.php" class="nav-link"><i class="fas fa-chart-bar"></i> Reports</a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>
    <div class="container">
        <div class="header-section">
            <h1><i class="fas fa-chart-bar"></i> Reports & Analytics</h1>
        </div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users" style="color: #667eea;"></i></div>
                <div class="stat-number"><?php echo $total_users; ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-film" style="color: #28a745;"></i></div>
                <div class="stat-number"><?php echo $total_movies; ?></div>
                <div class="stat-label">Total Movies</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clipboard-list" style="color: #ffc107;"></i></div>
                <div class="stat-number"><?php echo $total_rentals; ?></div>
                <div class="stat-label">Total Rentals</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clipboard-check" style="color: #007bff;"></i></div>
                <div class="stat-number"><?php echo $active_rentals; ?></div>
                <div class="stat-label">Active Rentals</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i></div>
                <div class="stat-number"><?php echo $overdue_rentals; ?></div>
                <div class="stat-label">Overdue Rentals</div>
            </div>
        </div>
        <div class="section">
            <h2><i class="fas fa-film"></i> Most Rented Movies</h2>
            <table class="list-table">
                <thead>
                    <tr><th>Movie Title</th><th>Rental Count</th></tr>
                </thead>
                <tbody>
                    <?php if ($most_rented_movies->num_rows > 0): ?>
                        <?php while ($row = $most_rented_movies->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo $row['rental_count']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="2" style="text-align:center; color:#888;">No data available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="section">
            <h2><i class="fas fa-user"></i> Top Users</h2>
            <table class="list-table">
                <thead>
                    <tr><th>Username</th><th>Rental Count</th></tr>
                </thead>
                <tbody>
                    <?php if ($top_users->num_rows > 0): ?>
                        <?php while ($row = $top_users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo $row['rental_count']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="2" style="text-align:center; color:#888;">No data available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html> 