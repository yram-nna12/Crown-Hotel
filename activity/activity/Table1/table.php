<!DOCTYPE html>
<html>
<head>
<title>People Information Table</title>
<link rel="stylesheet" href="./style.css">
</head>
<body>

<?php
$people = [
["name" => "Aviona", "image" => "images/wang.jpg", "age" => 19, "birthday" => "2005-07-20", "contact" => "09773963058"],
["name" => "Kate", "image" => "images/wung.jpg", "age" => 18, "birthday" => "2006-11-25", "contact" => "09090900090"],
["name" => "Mary", "image" => "images/hing.jpg", "age" => 21, "birthday" => "2004-05-12", "contact" => "09090900091"],
["name" => "Rona", "image" => "images/wing.jpg", "age" => 21, "birthday" => "2003-10-11", "contact" => "09090900092"],
["name" => "Marielle", "image" => "images/hung.jpg", "age" => 19, "birthday" => "2005-12-13", "contact" => "09090900093"],
["name" => "Nicolle", "image" => "images/wong.jpg", "age" => 19, "birthday" => "2005-09-29", "contact" => "09090900094"],
["name" => "Valerie", "image" => "images/hong.jpg", "age" => 21, "birthday" => "2004-01-24", "contact" => "09090900095"],
["name" => "Bianca", "image" => "images/hang.jpg", "age" => 19, "birthday" => "2005-07-20", "contact" => "09090900096"],
["name" => "Farah", "image" => "images/heng.jpg", "age" => 19, "birthday" => "2005-03-21", "contact" => "09090900097"],
["name" => "Godwin", "image" => "images/weng.jpg", "age" => 21, "birthday" => "2003-12-05", "contact" => "090909000908"]
];

// Sort alphabetically by name
usort($people, function($a, $b) {
return strcmp($a["name"], $b["name"]);
});

echo "<h2>People Info Table </h2>";
echo "<table>";
echo "<tr><th>No.</th><th>Name</th><th>Image</th><th>Age</th><th>Birthday</th><th>Contact</th></tr>";

$counter = 1;
foreach ($people as $person) {
echo "<tr>";
echo "<td>{$counter}</td>";
echo "<td>{$person['name']}</td>";
echo "<td><img src='{$person['image']}' alt='{$person['name']}'></td>";
echo "<td>{$person['age']}</td>";
echo "<td>{$person['birthday']}</td>";
echo "<td>{$person['contact']}</td>";
echo "</tr>";
$counter++;
}

echo "</table>";
?>

</body>
</html>
