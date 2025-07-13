<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['userlevel'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Handle user status toggle
if (isset($_GET['toggle_status']) && is_numeric($_GET['toggle_status'])) {
    $user_id = $_GET['toggle_status'];
    $conn->query("UPDATE users SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE id = $user_id");
    header("Location: admin_users.php?success=status_updated");
    exit();
}

// Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = $_GET['delete'];
    // Don't allow admin to delete themselves
    if ($user_id != $_SESSION['user']['id']) {
        $conn->query("DELETE FROM users WHERE id = $user_id");
        header("Location: admin_users.php?success=deleted");
        exit();
    } else {
        header("Location: admin_users.php?error=cannot_delete_self");
        exit();
    }
}

// Get all users with rental count
$users = $conn->query("
    SELECT u.*, COUNT(r.id) as rental_count 
    FROM users u 
    LEFT JOIN rentals r ON u.id = r.user_id 
    GROUP BY u.id 
    ORDER BY u.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Video Rental Admin</title>
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

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        .user-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }

        .user-card:hover {
            transform: translateY(-5px);
        }

        .user-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .user-info h3 {
            margin-bottom: 0.25rem;
        }

        .user-email {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .user-body {
            padding: 1.5rem;
        }

        .user-details {
            margin-bottom: 1rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
        }

        .user-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            display: inline-block;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .user-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .user-actions .btn {
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

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
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

        .user-level-badge {
            background: #667eea;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .users-grid {
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
        <?php if (isset($_GET['success'])): ?>
            <?php if ($_GET['success'] == 'deleted'): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> User deleted successfully!
                </div>
            <?php elseif ($_GET['success'] == 'status_updated'): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> User status updated successfully!
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'cannot_delete_self'): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i> You cannot delete your own account!
            </div>
        <?php endif; ?>

        <div class="header-section">
            <h1><i class="fas fa-users"></i> Manage Users</h1>
            <a href="admin_add_user.php" class="btn">
                <i class="fas fa-user-plus"></i> Add New User
            </a>
        </div>

        <?php if ($users->num_rows > 0): ?>
            <div class="users-grid">
                <?php while ($user = $users->fetch_assoc()): ?>
                    <div class="user-card">
                        <div class="user-header">
                            <div class="user-avatar">
                                <?php if ($user['image']): ?>
                                    <img src="uploads/<?php echo $user['image']; ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <i class="fas fa-user"></i>
                                <?php endif; ?>
                            </div>
                            <div class="user-info">
                                <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                                <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                            </div>
                        </div>
                        <div class="user-body">
                            <div class="user-details">
                                <div class="detail-item">
                                    <span class="detail-label">User Level:</span>
                                    <span class="user-level-badge"><?php echo ucfirst($user['userlevel']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Status:</span>
                                    <span class="user-status status-<?php echo $user['status']; ?>">
                                        <?php echo ucfirst($user['status']); ?>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Total Rentals:</span>
                                    <span class="detail-value"><?php echo $user['rental_count']; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Joined:</span>
                                    <span class="detail-value"><?php echo date('M j, Y', strtotime($user['created_at'])); ?></span>
                                </div>
                            </div>

                            <div class="user-actions">
                                <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                                    <a href="admin_users.php?toggle_status=<?php echo $user['id']; ?>" 
                                       class="btn <?php echo $user['status'] == 'active' ? 'btn-warning' : 'btn-success'; ?>"
                                       onclick="return confirm('Are you sure you want to <?php echo $user['status'] == 'active' ? 'deactivate' : 'activate'; ?> this user?')">
                                        <i class="fas fa-<?php echo $user['status'] == 'active' ? 'ban' : 'check'; ?>"></i>
                                        <?php echo $user['status'] == 'active' ? 'Deactivate' : 'Activate'; ?>
                                    </a>
                                    <a href="admin_users.php?delete=<?php echo $user['id']; ?>" 
                                       class="btn btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                <?php else: ?>
                                    <span class="btn btn-secondary" style="flex: 1; cursor: default; opacity: 0.7;">
                                        <i class="fas fa-user"></i> Current User
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h2>No Users Found</h2>
                <p>Get started by adding your first user to the system.</p>
                <a href="admin_add_user.php" class="btn" style="margin-top: 1rem;">
                    <i class="fas fa-user-plus"></i> Add First User
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 