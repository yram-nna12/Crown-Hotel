<!DOCTYPE html>
<html>
<head>
<title>Array Operations</title>
<style>
body {
font-family: Arial, sans-serif;
background: #f4f4f4;
padding: 20px;
}
table {
border-collapse: collapse;
width: 50%;
margin: auto;
background: white;
box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
th, td {
border: 1px solid #ddd;
padding: 12px;
text-align: center;
}
th {
background-color: #2196F3;
color: white;
}
h2, p {
text-align: center;
}
</style>
</head>
<body>

<?php
$numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

// Sum
$sum = array_sum($numbers);

// Difference (left-to-right)
$difference = $numbers[0];
for ($i = 1; $i < count($numbers); $i++) {
$difference -= $numbers[$i];
}

// Product
$product = 1;
foreach ($numbers as $num) {
$product *= $num;
}

// Quotient (left-to-right)
$quotient = $numbers[0];
for ($i = 1; $i < count($numbers); $i++) {
if ($numbers[$i] == 0) {
$quotient = "Undefined (division by zero)";
break;
}
$quotient /= $numbers[$i];
}

echo "<h2>Operations on Array Values</h2>";

echo "<table>";
echo "<tr><th colspan='2'> Array list: ".implode(",", $numbers)."</th></tr>";;
echo "<tr><td>Sum</td><td>$sum</td></tr>";
echo "<tr><td>Difference</td><td>$difference</td></tr>";
echo "<tr><td>Product</td><td>$product</td></tr>";
echo "<tr><td>Quotient</td><td>$quotient</td></tr>";
echo "</table>";
?>

</body>
</html>