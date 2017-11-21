	<!DOCTYPE html>
<html>
<head>
	<title>Results Question 1:</title>
	<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
</head>
<body>
	<h1 style="font-family: 'Indie Flower', cursive;">Paulo's Clinic:</h1>

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

		$stmt = $connection->prepare("SELECT * FROM Patient where name like CONCAT('%', :portion_name, '%')");	

		$stmt->bindParam(':portion_name', $name);

		$stmt->execute();

		if ($stmt == FALSE) {
			$info = $connection->errorInfo();
			echo("<p>Error: {$info[2]}</p>");
			exit();
		}
		
		$nrows = $stmt->rowCount();
		if ($nrows == 0) {
			header('Location: newpatient.php');
		} else {
			echo "<h3>Results Found:</h3>";
			echo("<table border=\"1\" cellspacing=\"5\">");
			echo("<tr><td><strong>Name</strong></td><td><strong>ID number</strong></td><td><strong>Birthday</strong></td><td><strong>Address</strong></td></tr>");

			foreach($stmt as $row) {
				echo("<tr><td>");
				echo("<a href=\"devices2.php?number=");
				echo($row['number']);
				echo("\">{$row['name']}</a>");
				echo("</td><td>");
				echo($row['number']);
				echo("</td><td>");
				echo($row['birthday']);
				echo("</td><td>");
				echo($row['address']);
				echo("</td></tr>");

			}
			echo("</table>");
		}

		$connection = null;
	?>
</body>
</html>