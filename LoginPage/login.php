<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "crown_db";

    // Create a new database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = trim($_POST['email'] ?? '');
    $input_password = $_POST['password'] ?? ''; 

    // --- Basic Input Validation ---
    if (empty($email) || empty($input_password)) {
        $_SESSION['login_error'] = "Please enter both email and password.";
        header("Location: index.html");
        exit;
    }

    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result(); 

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_email, $hashed_password_from_db);
        $stmt->fetch();

        // --- Verify Password ---
        if (password_verify($input_password, $hashed_password_from_db)) {
            // Password is correct! User is authenticated.

            // Set session variables to mark the user as logged in
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_email'] = $db_email;
            $_SESSION['logged_in'] = true;

            // Store email and login time in cookies (valid for 7 days)
            setcookie("user_email", $db_email, time() + (86400 * 7), "/");
            setcookie("login_time", date("Y-m-d H:i:s"), time() + (86400 * 7), "/");

            // Optional: update last_login in database
            $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $updateStmt->bind_param("i", $user_id);
            $updateStmt->execute();
            $updateStmt->close();

            // Clear any previous login error
            unset($_SESSION['login_error']);

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Invalid email or password.";
            header("Location: index.html");
            exit;
        }
    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: index.html");
        exit;
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: index.html");
    exit;
}
?>
