<!DOCTYPE html>
<html>
<head>
	<title>Insertion of new region</title>
	<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
</head>
<body>
	<h1 style="font-family: 'Indie Flower', cursive;">Clinic Database:</h1>

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

			// Assumimos que se insere sempre

			$stmt = $connection->prepare("INSERT INTO Region VALUES (:series_id,:elem_index,:x1,:y1,:x2,:y2)");

			$stmt->bindParam(':series_id', $series_id);
			$stmt->bindParam(':elem_index', $elem_index);
			$stmt->bindParam(':x1', $x1);
			$stmt->bindParam(':y1', $y1);
			$stmt->bindParam(':x2', $x2);
			$stmt->bindParam(':y2', $y2);

			$stmt->execute();

			if ($stmt == FALSE) {
				$info = $stmt->errorInfo();
				echo("<p>Error: {$info[2]}</p>");
				exit();
			}

			// QUERY -> get all regions of an element from the last study of the patient (if there was one)

			// if the new region doesnt overlap with one of these, then print a message saying "new clinical evidence"

			if($x1 > $x2) {
				$aux = $x1;
				$x1 = $x2;
				$x2 = $aux; 
			}

			if($y1 > $y2) {
				$aux = $y1;
				$y1 = $y2;
				$y2 = $aux; 
			}

			foreach($stmt as $row){

				if($row['x1'] > $row['x2']){
					$aux = $row['x1'];
					$row['x1'] = $row['x2'];
					$row['x2'] = $aux; 
				}

				if($row['y1'] > $row['y2']){
					$aux = $row['y1'];
					$row['y1'] = $row['y2'];
					$row['y2'] = $aux; 
				}

			}

			$connection = null;
		?>

</body>
</html>