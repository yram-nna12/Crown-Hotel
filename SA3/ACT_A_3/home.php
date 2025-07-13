<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <style>
         body {
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, rgb(231, 231, 231), rgb(17, 75, 102), rgb(6, 38, 41)) no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: white;
            padding: 60px 80px;
            position: relative;
        }

        .container {
            max-width: 800px;
        }

        h2 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color:rgb(9, 68, 97)
        }

        hr {
            border: none;
            height: 3px;
            background-color:  rgb(97, 199, 224);
            width: 100rem;
            margin: 10px 0 30px 0;
        }

        p, li {
            font-size: 20px;
            margin: 5px 0;
            color:rgb(9, 68, 97);
            gap: 10px;
            
        }

        form {
            margin-top: 30px;
        }

        input[type="password"] {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            background-color: rgb(97, 199, 224);
            color: white;
            border: none;
            border-radius: 6px;
            margin-top: 10px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: rgb(97, 199, 224);
        }

        .message {
            margin-top: 20px;
            font-weight: bold;
            color: yellow;
        }

        a {
            position: absolute;
            bottom: 30px;
            right: 30px;
            display: inline-block;
            padding: 12px 24px;
            background-color:  rgb(97, 199, 224);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
        }

        a:hover {
            background-color:  rgb(59, 212, 250);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>WELCOME, <?php echo $_SESSION['user']; ?>!</h2>
        <hr>
        <p>This is the homepage. Only logged-in users can see this.</p>
    </div>
    <a href= "logout.php">Logout</a>
</body>
</html>
