<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['userlevel'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Mark rental as returned
if (isset($_GET['return']) && is_numeric($_GET['return'])) {
    $rental_id = $_GET['return'];
    $conn->query("UPDATE rentals SET status = 'returned', return_date = NOW() WHERE id = $rental_id");
    // Increase movie stock
    $conn->query("UPDATE movies SET stock_quantity = stock_quantity + 1 WHERE id = (SELECT movie_id FROM rentals WHERE id = $rental_id)");
    header("Location: admin_rentals.php?success=returned");
    exit();
}

// Get all rentals
$rentals = $conn->query("
    SELECT r.*, u.username, m.title, m.image as movie_image 
    FROM rentals r 
    JOIN users u ON r.user_id = u.id 
    JOIN movies m ON r.movie_id = m.id 
    ORDER BY r.rental_date DESC
");
?>
<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Manage Rentals - Video Rental Admin</title>
    <link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css\" rel=\"stylesheet\">
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
        .alert { padding: 1rem; border-radius: 10px; margin-bottom: 2rem; }
        .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .rentals-table { width: 100%; border-collapse: collapse; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .rentals-table th, .rentals-table td { padding: 1rem; text-align: left; border-bottom: 1px solid #f0f0f0; }
        .rentals-table th { background: #f8f9fa; color: #667eea; font-weight: 600; }
        .rentals-table tr:last-child td { border-bottom: none; }
        .movie-thumb { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; background: #f0f0f0; }
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
        .status-active { background: #e8f5e8; color: #2d5a2d; }
        .status-returned { background: #e8eaf6; color: #3949ab; }
        .status-overdue { background: #ffe8e8; color: #a52a2a; }
        .action-btn { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer; transition: all 0.3s; text-decoration: none; font-size: 0.9rem; }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3); }
        @media (max-width: 900px) { .rentals-table th, .rentals-table td { padding: 0.5rem; font-size: 0.95rem; } }
        @media (max-width: 600px) { .container { padding: 0.5rem; } .header-section { flex-direction: column; gap: 1rem; text-align: center; } .rentals-table th, .rentals-table td { font-size: 0.85rem; } }
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
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>
    <div class="container">
        <div class="header-section">
            <h1><i class="fas fa-clipboard-list"></i> Manage Rentals</h1>
        </div>
        <?php if (isset($_GET['success']) && $_GET['success'] == 'returned'): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> Rental marked as returned!</div>
        <?php endif; ?>
        <table class="rentals-table">
            <thead>
                <tr>
                    <th>Movie</th>
                    <th>User</th>
                    <th>Rented On</th>
                    <th>Due Date</th>
                    <th>Returned</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rentals->num_rows > 0): ?>
                    <?php while ($rental = $rentals->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php if ($rental['movie_image']): ?>
                                    <img src="uploads/<?php echo $rental['movie_image']; ?>" class="movie-thumb" alt="Movie">
                                <?php else: ?>
                                    <i class="fas fa-film movie-thumb"></i>
                                <?php endif; ?>
                                <br><?php echo htmlspecialchars($rental['title']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($rental['username']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($rental['rental_date'])); ?></td>
                            <td><?php echo $rental['due_date'] ? date('M j, Y', strtotime($rental['due_date'])) : '-'; ?></td>
                            <td><?php echo $rental['return_date'] ? date('M j, Y', strtotime($rental['return_date'])) : '-'; ?></td>
                            <td><span class="status-badge status-<?php echo $rental['status']; ?>"><?php echo ucfirst($rental['status']); ?></span></td>
                            <td>
                                <?php if ($rental['status'] == 'active'): ?>
                                    <a href="admin_rentals.php?return=<?php echo $rental['id']; ?>" class="action-btn" onclick="return confirm('Mark as returned?')"><i class="fas fa-check"></i> Mark Returned</a>
                                <?php else: ?>
                                    <span style="color:#aaa;">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align:center; color:#888;">No rentals found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html> 