<?php
include "../config.php";
session_start();

if ($_SESSION['userlevel'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $year = $_POST['year'];
    $available = isset($_POST['available']) ? 1 : 0;

    $getQuery = "SELECT image FROM videos WHERE id = $id";
    $getResult = mysqli_query($conn, $getQuery);
    $existing = mysqli_fetch_assoc($getResult);
    $image_name = $existing['image'];

    if (!empty($_FILES['image']['name'])) {
        $new_image = $_FILES['image']['name'];
        $tmp_image = $_FILES['image']['tmp_name'];
        $upload_dir = "uploads/";

        if (!empty($image_name) && file_exists($upload_dir . $image_name)) {
            unlink($upload_dir . $image_name);
        }

        move_uploaded_file($tmp_image, $upload_dir . $new_image);
        $image_name = $new_image;
    }

    $query = "UPDATE videos SET title='$title', genre='$genre', year=$year, available=$available, image='$image_name' WHERE id=$id";
    mysqli_query($conn, $query);
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM videos WHERE id=$id";
$result = mysqli_query($conn, $query);
$video = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="./assets/images/logo (3).png" alt="Video Rental Logo" class="logo-img">
        </div>
        <a href="dashboard.php">Dashboard</a>
        <a href="index.php" class="active">Videos</a>
        <a href="viewusers.php">Users</a>
        <a href="#" onclick="toggleAccount()">Account</a>
        <a href="../logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <h2>Edit Video</h2>
        <form method="POST" enctype="multipart/form-data" class="edit-form">
            <input type="hidden" name="id" value="<?php echo $video['id']; ?>">

            <label>Title:</label>
            <input type="text" name="title" value="<?php echo $video['title']; ?>" required>

            <label>Genre:</label>
            <input type="text" name="genre" value="<?php echo $video['genre']; ?>" required>

            <label>Year:</label>
            <input type="number" name="year" value="<?php echo $video['year']; ?>" required>

            <label>
                <input type="checkbox" name="available" <?php if ($video['available']) echo "checked"; ?>>
                Available
            </label>

            <label>Current Image:</label>
            <?php if (!empty($video['image'])): ?>
                <img src="uploads/<?php echo $video['image']; ?>" width="150" class="preview-image"><br>
            <?php endif; ?>

            <label>Upload New Image (optional):</label>
            <input type="file" name="image" accept="image/*">

            <input type="submit" value="Update Video" class="btn">
        </form>
    </div>
</div>


<style>
    body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #ffe0c2, #ffb6f0);
    color: #6b004d;
}

.container {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    background-color: #ffd84c;
    width: 180px;
    padding-top: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 2px 0 8px rgba(0,0,0,0.1);
}

.logo-img {
    max-width: 120px;
    height: auto;
    margin-bottom: 20px;
}

.sidebar a {
    width: 100%;
    text-align: center;
    padding: 12px;
    color: #6b004d;
    font-weight: bold;
    text-decoration: none;
    transition: background 0.2s;
}


.main {
    flex: 1;
    padding: 40px;
}

h2 {
    font-size: 28px;
    margin-bottom: 30px;
}

.edit-form {
    max-width: 500px;
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.edit-form label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}

.edit-form input[type="text"],
.edit-form input[type="number"],
.edit-form input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-sizing: border-box;
}

.edit-form input[type="checkbox"] {
    margin-right: 10px;
}

.edit-form .preview-image {
    margin-top: 10px;
    border-radius: 10px;
}

.btn {
    margin-top: 20px;
    background-color: #6b004d;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.2s;
}

.btn:hover {
    background-color: #a5005f;
}

</style>
</body>
</html>
