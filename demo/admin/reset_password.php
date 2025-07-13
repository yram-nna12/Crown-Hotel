<?php
session_start();
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $old = md5($_POST['old_password']);
    $new = md5($_POST['new_password']);
    $confirm = md5($_POST['confirm_password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if ($old !== $user['password']) {
        echo "<script>alert('Current password is incorrect'); window.location='account.php';</script>";
    } elseif ($new !== $confirm) {
        echo "<script>alert('New passwords do not match'); window.location='account.php';</script>";
    } else {
        mysqli_query($conn, "UPDATE users SET password='$new' WHERE username='$username'");
        echo "<script>alert('Password updated successfully'); window.location='account.php';</script>";
    }
}
