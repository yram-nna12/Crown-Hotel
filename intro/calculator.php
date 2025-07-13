<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title> Event Registration Fee Calculation</title>
	</head>
	<body>
		<h1>Event Registration Fee Calculation</h1>
	
		<?php
		//check if the form is summited
		
		if($_SERVER["REQUEST_METHOD"]=="POST") {
		// assign variables from POST data
		$age = isset($_POST['age'])? intval($POST['age']) : 0;
		$is_student = isset ($_POST['is_student'])? true : false;
		
		//determine the base price based on age
		if ($age >= 18) {
			$base_price = 100; // Adult pricing
		} elseif ($age >= 13) {
			$base_price = 50; // Teenager pricing
		} else {
			$base_price = 0; // Children for free
		}
		
		// Apply student discount if applicable
		if ($is_student) {
			$discount = $base_price * 0.20;  // Calculate 20% discount
			$final_price = $base_price - $discount;
		} else {
			$final_price = $base_price;
		}
		
		
		// Output the final price
		echo "The registration fee for the attendee is: $" . number_format($final_price, 2);
	}
		?>
		
		
		<!---html form--->
		<form action ="" method= "post">
			<label for="age"> Age: </label>
			<input type="number" id="age" name="age" required>
			<label for="is_student"> Are you a student?</label>
			<input type="checkbox" id="is_student" name="is_student">
			<button type="submit"> Calculate fee</button>
		</form>
	</body>
</html>