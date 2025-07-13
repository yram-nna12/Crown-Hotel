<!DOCTYPE html>
<html>
<head>
<title>People Information Table</title>
<link rel="stylesheet" href="./style.css">
</head>
<body>

<?php
$people = [
["name" => "Upin", "image" => "./assets/images/upin.png", "age" => 5, "birthday" => "2003-09-16", "Contact" => "00000000001"],
["name" => "Ipin", "image" => "./assets/images/ipin.png", "age" => 5, "birthday" => "2003-09-16", "Contact" => "00000000002"],
["name" => "Kas Ros", "image" => "./assets/images/ros.png", "age" => 17, "birthday" => "Not officially stated", "Contact" => "00000000003"],
["name" => "Opah", "image" => "./assets/images/Opah.png", "age" => 68, "birthday" => "Not officially stated", "Contact" => "00000000004"],
["name" => "Tok Dalang", "image" => "./assets/images/Tok.png", "age" => 55, "birthday" => "Not officially stated", "Contact" => "00000000005"],
["name" => "Mei mei", "image" => "./assets/images/mei.png", "age" => 5, "birthday" => "2003-05-05", "Contact" => "00000000006"],
["name" => "Jarjit Singh", "image" => "./assets/images/jarjit.png", "age" => 5, "birthday" => "2003-01-04", "Contact" => "0000000007"],
["name" => "Ehsan", "image" => "./assets/images/ehsan.png", "age" => 5, "birthday" => "2003-01-15", "Contact" => "00000000008"],
["name" => "Fizi", "image" => "./assets/images/fizi.png", "age" => 5, "birthday" => "2003-12-01", "Contact" => "00000000009"],
["name" => "Miss Jasmin", "image" => "./assets/images/miss_jasmin.png", "age" => 42, "birthday" => "1966-04-29", "Contact" => "000000000010"]

];
// Sort alphabetically by name
usort($people, function($a, $b) {
return strcmp($a["name"], $b["name"]);
});

echo "<h2>Upin and Ipin Characters</h2>";
echo "<table>";
echo "<tr><th>No.</th><th>Name</th><th>Image</th><th>Age</th><th>Birthday</th><th>Conatact</th></tr>";

$counter = 1;
foreach ($people as $person) {
echo "<tr>";
echo "<td>{$counter}</td>";
echo "<td>{$person['name']}</td>";
echo "<td><img src='{$person['image']}' alt='{$person['name']}'></td>";
echo "<td>{$person['age']}</td>";
echo "<td>{$person['birthday']}</td>";
echo "<td>{$person['Contact']}</td>";
echo "</tr>";
$counter++;
}

echo "</table>";
?>

</body>
</html>
