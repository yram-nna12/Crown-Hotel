<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "crown_db";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = trim($_POST['email'] ?? '');
    $input_password = $_POST['password'] ?? ''; 

    if (empty($email) || empty($input_password)) {
        $_SESSION['login_error'] = "Please enter both email and password.";
        header("Location: index.php"); 
        exit;
    }

    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result(); 

    if ($stmt->num_rows === 0) {
        $_SESSION['login_error'] = "Account not found. Please sign up first.";
        header("Location: ../Signup_page/index.php");
        exit;
    }

    $stmt->bind_result($user_id, $db_email, $hashed_password_from_db);
    $stmt->fetch();

    if (password_verify($input_password, $hashed_password_from_db)) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $db_email;
        $_SESSION['logged_in'] = true;

        setcookie("user_email", $db_email, time() + (86400 * 7), "/");
        setcookie("login_time", date("Y-m-d H:i:s"), time() + (86400 * 7), "/");

        $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $updateStmt->bind_param("i", $user_id);
        $updateStmt->execute();
        $updateStmt->close();

        unset($_SESSION['login_error']);

        header("Location: dashboard.php");
        exit;

    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: index.php");
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit;
}


