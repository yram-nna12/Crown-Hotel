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
        $stmt->fetch(); // Fetch the row (there should only be one since email is UNIQUE)

        // --- Verify Password ---
        // Use password_verify() to compare the plain text input password with the stored hashed password
        if (password_verify($input_password, $hashed_password_from_db)) {
            // Password is correct! User is authenticated.

            // Set session variables to mark the user as logged in
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_email'] = $db_email;
            $_SESSION['logged_in'] = true;

            // Clear any previous login error
            unset($_SESSION['login_error']);

            // Redirect the user to a secure dashboard or welcome page
            // You'll need to create a 'dashboard.php' file or a similar page
            header("Location: dashboard.php");
            exit; // Always exit after a header redirect
        } else {
            // Incorrect password
            $_SESSION['login_error'] = "Invalid email or password.";
            header("Location: index.html");
            exit;
        }
    } else {
        // No user found with that email address
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: index.html");
        exit;
    }

    // --- Close Statement and Connection ---
    $stmt->close();
    $conn->close();

} else {
    // If someone tries to access login.php directly without a POST request (e.g., typing the URL)
    header("Location: index.html"); // Redirect them back to the login page
    exit;
}
?>