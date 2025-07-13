<?php
session_start();
include "../config.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // make uploads directory if not exists
    }

    $target = $upload_dir . basename($image);
    move_uploaded_file($tmp, $target);

    // Update user's image in the database
    $query = "UPDATE users SET image='$image' WHERE username='$username'";
    mysqli_query($conn, $query);

    // Redirect back to account page
    header("Location: account.php");
    exit();
}
?>
