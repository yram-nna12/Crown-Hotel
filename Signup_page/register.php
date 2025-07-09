<?php
session_start();
require_once __DIR__ . '/../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Store previous inputs except passwords
    $_SESSION['old_input'] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone' => $phone
    ];

    if (!$first_name || !$last_name || !$email || !$phone || !$password || !$confirm_password) {
        $_SESSION['register_error'] = "All fields are required.";
        header("Location: index.php");
        exit;
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $first_name . $last_name)) {
        $_SESSION['register_error'] = "Please use letters only in name fields.";
        header("Location: index.php");
        exit;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = "Invalid email format.";
        header("Location: index.php");
        exit;
    } elseif (!preg_match("/^[0-9]{11}$/", $phone)) {
        $_SESSION['register_error'] = "Contact number should have exactly 11 digits.";
        header("Location: index.php");
        exit;
    } elseif ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Passwords do not match.";
        header("Location: index.php");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $hashed_password);

    if ($stmt->execute()) {
        // Registration successful — no message, just redirect
        unset($_SESSION['old_input']);
        header("Location: ../LoginPage/index.php");
        exit;
    } else {
        $_SESSION['register_error'] = "Something went wrong. Please try again.";
        header("Location: index.php");
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit;
}
