<?php
include "../config.php";

if (isset($_POST['create_admin'])) {
    $username = 'admin';
    $email = 'admin@gmail.com';
    $password = md5('admin123');
    $userlevel = 'admin';
    $status = 'active';
    $image = '';

    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='admin'");
    if (mysqli_num_rows($check) === 0) {
        mysqli_query($conn, "INSERT INTO users (email, username, password, userlevel, status, image) 
            VALUES ('$email', '$username', '$password', '$userlevel', '$status', '$image')");
        echo "<script>alert('✅ Admin account created. Username: admin, Password: admin123'); window.location='account.php';</script>";
    } else {
        echo "<script>alert('⚠️ Admin already exists.'); window.location='account.php';</script>";
    }
}
