<!DOCTYPE html>
<html>
<head>
	<title>Results Question 1:</title>
	<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
</head>
<body>
	<h1 style="font-family: 'Indie Flower', cursive;">Clinic Database:</h1>

	<?php
		ini_set('display_errors', 'On');
		error_reporting(E_ALL);

		$host = "db.tecnico.ulisboa.pt";
		$user = "ist181588";
		$pass = "gjzf1955";
		$dsn = "mysql:host=$host;dbname=$user";

		try {
			$connection = new PDO($dsn, $user, $pass);
		} catch(PDOException $exception) {
			echo("<p>Error: ");
			echo($exception->getMessage());
			echo("</p>");
			exit();
		}

		$name =$_REQUEST['Name'];

		// SQL Injection Prevention
		$stmt = $connection->prepare("SELECT * FROM Patient where name like CONCAT('%', :portion_name, '%')");	

		$stmt->bindParam(':portion_name', $name);
		$result = $stmt->execute();

		if ($result == FALSE) {
			$info = $stmt->errorInfo();
			echo("<p>Error: {$info[2]}</p>");
			exit();
		}
		
		$nrows = $stmt->rowCount();

		// in case of not finding a patient with that name
		// can register a new patient
		if ($nrows == 0) {

			echo("<p>Sorry, patient wasn't found on the database.</p>");

			echo("<form action='newpatient.php' method='post'>");
			echo("<fieldset> <legend><strong>Insert new patient</strong></legend>");
			echo("<p>name: <input type='text' name='name' /></p>");
			echo("<p>number: <input type='text' name='number' required/></p>");
			echo("<p>birthday: <input type='text' name='birthday'/></p>");
			echo("<p>address: <input type='text' name='address'/></p>");
			echo("<p><input type='submit' value='Submit'/></p>");
			echo("</fieldset>");
			echo("</form>");

		// prints a table with the results
		} else {
			echo "<h3>Results Found:</h3>";
			echo("<table border=\"1\" cellspacing=\"5\">");
			echo("<tr><td><strong>Name</strong></td><td><strong>ID number</strong></td><td><strong>Birthday</strong></td><td><strong>Address</strong></td><td><strong>Study</strong></td><td><strong>Region</strong></td></tr>");

			foreach($stmt as $row) {
				echo("<tr><td>");
				echo("<a href=\"listDevices.php?number=");
				echo($row['number']);
				echo("\">{$row['name']}</a>");
				echo("</td><td>");
				echo($row['number']);
				echo("</td><td>");
				echo($row['birthday']);
				echo("</td><td>");
				echo($row['address']);
				echo("</td><td>");
				echo("<a href=\"formStudy.php?number=");
				echo($row['number']);
				echo("\"><button type='button'>Register</button></a>");
				echo("</td><td>");
				echo("<a href=\"formRegions.php?number=");
				echo($row['number']);
				echo("\"><button type='button'>Insert</button></a>");
				echo("</td></tr>");
			}

			echo("</table>");
		}

		// Button to go to home page
		echo("<br><form action='checkPatient.html' method='post'>");
		echo("<input type='submit' value='Home'/></form>");

		$connection = null;
	?>
</body>
</html>