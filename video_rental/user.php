<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['userlevel'] != 'user') {
    header("Location: login.php");
    exit();
}
include 'db.php';

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    include 'upload_image.php';
    $image = uploadImage($_FILES['image']);
    $sql = "UPDATE users SET image='$image' WHERE id={$user['id']}";
    $conn->query($sql);
    $_SESSION['user']['image'] = $image;
    $user['image'] = $image;
    echo "Image updated!";
}
?>

<h2>User Dashboard</h2>
<p>Email: <?php echo $user['email']; ?></p>
<p>Username: <?php echo $user['username']; ?></p>
<p>Status: <?php echo $user['status']; ?></p>
<p>Image: <img src="uploads/<?php echo $user['image']; ?>" width="100"></p>

<form method="post" enctype="multipart/form-data">
    Change Image: <input type="file" name="image"><br/>
    <input type="submit" value="Upload">
</form>
<a href="logout.php">Logout</a>