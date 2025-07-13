<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['userlevel'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $userlevel = $_POST['userlevel'];
    $status = $_POST['status'];
    $image = '';

    if ($_FILES['image']['name']) {
        include 'upload_image.php';
        $image = uploadImage($_FILES['image']);
    }

    $sql = "INSERT INTO users (email, username, password, userlevel, status, image) VALUES ('$email', '$username', '$password', '$userlevel', '$status', '$image')";
    if ($conn->query($sql)) {
        echo "User added!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="post" enctype="multipart/form-data">
    Email: <input type="email" name="email" required><br/>
    Username: <input type="text" name="username" required><br/>
    Password: <input type="password" name="password" required><br/>
    Userlevel: 
    <select name="userlevel">
        <option value="admin">Admin</option>
        <option value="user">User</option>
    </select><br/>
    Status: <input type="text" name="status" value="active" required><br/>
    Image: <input type="file" name="image"><br/>
    <input type="submit" value="Add User">
</form>