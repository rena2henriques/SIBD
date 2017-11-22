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

			// get the region info
			$series_id =$_REQUEST['seriesid'];
			$elem_index =$_REQUEST['elem_index'];
			$x1 =$_REQUEST['x1'];
			$y1 =$_REQUEST['y1'];
			$x2 =$_REQUEST['x2'];
			$y2 =$_REQUEST['y2'];

			// Inserir new region -> acho que é para inserir sempre

			$stmt = $connection->prepare("INSERT INTO Region VALUES (:series_id,:elem_index,:x1,:y1,:x2,:y2)");

			$stmt->bindParam(':series_id', $series_id);
			$stmt->bindParam(':elem_index', $elem_index);
			$stmt->bindParam(':x1', $x1);
			$stmt->bindParam(':y1', $y1);
			$stmt->bindParam(':x2', $x2);
			$stmt->bindParam(':y2', $y2);

			$stmt->execute();

			// QUERY -> get all regions of an element from the last study of the patient (if there was one)

			// if the new region doesnt overlap with one of these, then print a message saying "new clinical evidence"
			
			$connection = null;
		?>

</body>
</html>