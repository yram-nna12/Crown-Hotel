<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['userlevel'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'db.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $genre = trim($_POST['genre']);
    $release_year = $_POST['release_year'];
    $director = trim($_POST['director']);
    $duration = $_POST['duration'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $image = '';

    // Validation
    if (empty($title) || empty($genre) || empty($release_year) || empty($director) || empty($duration) || empty($price)) {
        $error_message = "Please fill in all required fields.";
    } elseif ($release_year < 1900 || $release_year > date('Y') + 1) {
        $error_message = "Please enter a valid release year.";
    } elseif ($duration < 1 || $duration > 999) {
        $error_message = "Please enter a valid duration.";
    } elseif ($price < 0) {
        $error_message = "Price cannot be negative.";
    } elseif ($stock_quantity < 0) {
        $error_message = "Stock quantity cannot be negative.";
    } else {
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['name']) {
            include 'upload_image.php';
            $image = uploadImage($_FILES['image']);
        }

        // Insert movie
        $stmt = $conn->prepare("INSERT INTO movies (title, description, genre, release_year, director, duration, price, stock_quantity, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisddss", $title, $description, $genre, $release_year, $director, $duration, $price, $stock_quantity, $image);
        
        if ($stmt->execute()) {
            $success_message = "Movie added successfully!";
            // Clear form data
            $_POST = array();
        } else {
            $error_message = "Error adding movie: " . $conn->error;
        }
    }
}

// Get current year for max attribute
$current_year = date('Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie - Video Rental Admin</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .form-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .form-section h1 {
            color: #667eea;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
        }

        .form-group label.required::after {
            content: " *";
            color: #dc3545;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1rem;
            font-weight: 500;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: block;
            padding: 0.75rem;
            border: 2px dashed #e1e5e9;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-input-label:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }

        .file-input-label i {
            font-size: 1.5rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .genre-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .genre-tag {
            background: #e9ecef;
            color: #495057;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .genre-tag:hover {
            background: #667eea;
            color: white;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
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
        <div class="form-section">
            <h1><i class="fas fa-plus"></i> Add New Movie</h1>

            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="title" class="required">Movie Title</label>
                        <input type="text" id="title" name="title" class="form-control" 
                               value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="genre" class="required">Genre</label>
                        <input type="text" id="genre" name="genre" class="form-control" 
                               value="<?php echo isset($_POST['genre']) ? htmlspecialchars($_POST['genre']) : ''; ?>" 
                               required>
                        <div class="genre-suggestions">
                            <span class="genre-tag" onclick="setGenre('Action')">Action</span>
                            <span class="genre-tag" onclick="setGenre('Comedy')">Comedy</span>
                            <span class="genre-tag" onclick="setGenre('Drama')">Drama</span>
                            <span class="genre-tag" onclick="setGenre('Horror')">Horror</span>
                            <span class="genre-tag" onclick="setGenre('Romance')">Romance</span>
                            <span class="genre-tag" onclick="setGenre('Sci-Fi')">Sci-Fi</span>
                            <span class="genre-tag" onclick="setGenre('Thriller')">Thriller</span>
                            <span class="genre-tag" onclick="setGenre('Documentary')">Documentary</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="release_year" class="required">Release Year</label>
                        <input type="number" id="release_year" name="release_year" class="form-control" 
                               value="<?php echo isset($_POST['release_year']) ? $_POST['release_year'] : ''; ?>" 
                               min="1900" max="<?php echo $current_year + 1; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="director" class="required">Director</label>
                        <input type="text" id="director" name="director" class="form-control" 
                               value="<?php echo isset($_POST['director']) ? htmlspecialchars($_POST['director']) : ''; ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="duration" class="required">Duration (minutes)</label>
                        <input type="number" id="duration" name="duration" class="form-control" 
                               value="<?php echo isset($_POST['duration']) ? $_POST['duration'] : ''; ?>" 
                               min="1" max="999" required>
                    </div>

                    <div class="form-group">
                        <label for="price" class="required">Rental Price ($)</label>
                        <input type="number" id="price" name="price" class="form-control" 
                               value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>" 
                               min="0" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="stock_quantity" class="required">Stock Quantity</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" 
                               value="<?php echo isset($_POST['stock_quantity']) ? $_POST['stock_quantity'] : '1'; ?>" 
                               min="0" required>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" 
                                  placeholder="Enter movie description..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="image">Movie Poster</label>
                        <div class="file-input-wrapper">
                            <input type="file" id="image" name="image" class="file-input" accept="image/*">
                            <label for="image" class="file-input-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div>Click to upload movie poster or drag and drop</div>
                                <div style="font-size: 0.8rem; color: #666; margin-top: 0.5rem;">
                                    PNG, JPG, GIF up to 5MB
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="admin_movies.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Movies
                    </a>
                    <button type="submit" class="btn">
                        <i class="fas fa-save"></i> Add Movie
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // File input preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const label = document.querySelector('.file-input-label');
                label.innerHTML = `
                    <i class="fas fa-check-circle" style="color: #28a745;"></i>
                    <div>${file.name}</div>
                    <div style="font-size: 0.8rem; color: #666;">File selected</div>
                `;
            }
        });

        // Genre tag click handler
        function setGenre(genre) {
            document.getElementById('genre').value = genre;
        }
    </script>
</body>
</html> 