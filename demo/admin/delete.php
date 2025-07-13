<?php
include "../config.php";
session_start();

if ($_SESSION['userlevel'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$getQuery = "SELECT image FROM videos WHERE id = $id";
$getResult = mysqli_query($conn, $getQuery);
$row = mysqli_fetch_assoc($getResult);
$image_name = $row['image'];

if (!empty($image_name) && file_exists("uploads/" . $image_name)) {
    unlink("uploads/" . $image_name);
}

$query = "DELETE FROM videos WHERE id=$id";
mysqli_query($conn, $query);

header("Location: index.php");
exit();
