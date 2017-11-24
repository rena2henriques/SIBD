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

				$x1_last = $row['x1'];
				$y1_last = $row['y1'];
				$x2_last = $row['x2'];
				$y2_last = $row['y2'];
				

				if($x1_last > $x2_last){
					$aux = $x1_last;
					$x1_last = $x2_last;
					$x2_last = $aux; 
				}

				if($y1_last > $y2_last){
					$aux = $y1_last;
					$y1_last = $y2_last;
					$y2_last = $aux; 
				}

				if ($x1_last >= $x2 || $x2_last <= $x1 || $y1_last >= $y2 || $y2_last <= $y1) {
					continue;
					// keeps searching
				} else {
					// prints and break, there's overlap
				}

			}

			// if we got to the end and there was no overlap, then we print that message

			$connection = null;
		?>

</body>
</html>