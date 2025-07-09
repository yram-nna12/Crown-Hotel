<?php
session_start();
include '../config.php'; // Uses your existing config.php for DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $input_password = $_POST['password'] ?? '';

    if (empty($email) || empty($input_password)) {
        $_SESSION['login_error'] = "Please enter both email and password.";
        header("Location: index.php");
        exit;
    }

    // 1. Check in admin_accounts
    $stmt = $conn->prepare("SELECT id, email, password FROM admin_accounts WHERE email = ?");
    if (!$stmt) {
        die("Admin stmt failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Found in admin_accounts
        $stmt->bind_result($admin_id, $admin_email, $admin_hashed_password);
        $stmt->fetch();

        if (password_verify($input_password, $admin_hashed_password)) {
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_email'] = $admin_email;
            $_SESSION['is_admin'] = true;

            header("Location: ../../../../Crown-Hotel/Dashboard/index.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Invalid email or password.";
            header("Location: index.php");
            exit;
        }
    }

    $stmt->close(); // Continue to check users

    // 2. Check in users table
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    if (!$stmt) {
        die("User stmt failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $_SESSION['login_error'] = "Account not found. Please sign up first.";
        header("Location: ../Signup_page/index.php");
        exit;
    }

    $stmt->bind_result($user_id, $user_email, $user_hashed_password);
    $stmt->fetch();

    if (password_verify($input_password, $user_hashed_password)) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $user_email;
        $_SESSION['logged_in'] = true;

        setcookie("user_email", $user_email, time() + (86400 * 7), "/");
        setcookie("login_time", date("Y-m-d H:i:s"), time() + (86400 * 7), "/");

        $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $updateStmt->bind_param("i", $user_id);
        $updateStmt->execute();
        $updateStmt->close();

        unset($_SESSION['login_error']);
        header("Location: ../../../../Crown-Hotel/user_landing_page/index.php");
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
