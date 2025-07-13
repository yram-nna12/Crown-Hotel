<?php
session_start();
include "../config.php";

// Validate session
if (!isset($_SESSION['username']) || $_SESSION['userlevel'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // recursively create dir
    }

    $target = $upload_dir . basename($image);
    move_uploaded_file($tmp, $target);

    // Update admin image in database
    $query = "UPDATE users SET image='$image' WHERE username='$username'";
    mysqli_query($conn, $query);

    header("Location: account.php");
    exit();
}
?>
