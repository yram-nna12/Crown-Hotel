<?php
session_start();
include "../config.php";

if ($_SESSION['userlevel'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['set'])) {
    $video_id = intval($_GET['id']);
    $new_status = ($_GET['set'] == 1) ? 1 : 0;

    $query = "UPDATE videos SET available = $new_status WHERE id = $video_id";
    mysqli_query($conn, $query);
}

// Redirect back to admin
header("Location: admin.php");
exit();
