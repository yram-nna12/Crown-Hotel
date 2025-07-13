<?php
function uploadImage($file) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB
    $file_type = mime_content_type($file["tmp_name"]);
    $file_size = $file["size"];
    if (!in_array($file_type, $allowed_types)) {
        return '';
    }
    if ($file_size > $max_size) {
        return '';
    }
    $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
    $new_name = uniqid('img_', true) . '.' . $ext;
    $target_file = $target_dir . $new_name;
    move_uploaded_file($file["tmp_name"], $target_file);
    return $new_name;
}
?>