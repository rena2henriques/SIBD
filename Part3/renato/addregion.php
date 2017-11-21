<!DOCTYPE html>
<html>
<head>
	<title>Insertion of new region</title>
	<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
</head>
<body>
	<h1 style="font-family: 'Indie Flower', cursive;">Paulo's Clinic:</h1>

	<?php
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

			// Inserir new region -> acho que é para inserir sempre

			// QUERY -> get all regions of an element from the last study of the patient (if there was one)

			// if the new region doesnt overlap with one of these, then print a message saying "new clinical evidence"
			
			$connection = null;
		?>

</body>
</html>