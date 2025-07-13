<!DOCTYPE html>
<html>
<head>
<title>Function-Based Array Operations</title>
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
// Define the user-defined function
function calculateOperations($a, $b, $c) {
$sum = $a + $b + $c;
$difference = $a - $b - $c;
$product = $a * $b * $c;
$quotient = ($b != 0 && $c != 0) ? $a / $b / $c : "Undefined (division by zero)";

return [
'sum' => $sum,
'difference' => $difference,
'product' => $product,
'quotient' => $quotient
];
}

// Example input values
$num1 = 25;
$num2 = 13;
$num3 = 6;

// Call the function with three parameters
$results = calculateOperations($num1, $num2, $num3);

echo "<h2>Operations on 3 Parameters Using Function</h2>";
echo "<p><strong>Input values:</strong> $num1, $num2, $num3</p>";

echo "<table>";
echo "<tr><th>Operation</th><th>Result</th></tr>";
echo "<tr><td>Sum</td><td>{$results['sum']}</td></tr>";
echo "<tr><td>Difference</td><td>{$results['difference']}</td></tr>";
echo "<tr><td>Product</td><td>{$results['product']}</td></tr>";
echo "<tr><td>Quotient</td><td>{$results['quotient']}</td></tr>";
echo "</table>";
?>

</body>
</html>