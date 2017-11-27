<!DOCTYPE html>
<html>
<head>
	<title>Device of patient</title>
	<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
</head>
<body>
	<h1 style="font-family: 'Indie Flower', cursive;">Clinic Database:</h1>

	<?php
		ini_set('display_errors', 'On');
		error_reporting(E_ALL);

		$host = "db.tecnico.ulisboa.pt";
	$user = "ist181607";
			$pass = "laed3426";
		$dsn = "mysql:host=$host;dbname=$user";

		try {
			$connection = new PDO($dsn, $user, $pass);
		} catch(PDOException $exception) {
			echo("<p>Error: ");
			echo($exception->getMessage());
			echo("</p>");
			exit();
		}

		$idnumber =$_REQUEST['number'];

		echo("<h3>List of devices worn by Patient nr. " . $idnumber . ":</h3>");
		
		$stmt = $connection->prepare("SELECT snum, manuf, start, end FROM Wears where patient = :idnumber order by end desc ");

		$stmt->bindParam(':idnumber', $idnumber);
		$stmt->execute();

		if ($stmt == FALSE) {
			$info = $stmt->errorInfo();
			echo("<p>Error: {$info[2]}</p>");
			exit();
		}

		$nrows = $stmt->rowCount();
		if ($nrows == 0) {
			echo("<p>Patient haven't worn any device.</p>");
		} else {
			echo("<table border=\"1\" cellspacing=\"5\">");
			echo("<tr><td><strong>Serial Number</strong></td><td><strong>Manufacturer</strong></td><td><strong>Start Date</strong></td><td><strong>End Date</strong></td></tr>");
			foreach($stmt as $row) {
				
				if( strcmp($row['end'], date("Y-m-d H:i:s",time())) > 0){
					echo("<tr><td><strong>");
					echo($row['snum']);
					echo("</strong></td><td><strong>");
					echo($row['manuf']);
					echo("</strong></td><td>");
					echo($row['start']);
					echo("</td><td>");
					echo($row['end']);
					echo("</td>");
					echo("<td><a href=\"listReplacements.php?patient=");
					echo($idnumber . "&serialnum=");
					echo($row['snum'] . "&manufacturer=");
					echo($row['manuf']);
					echo("\"><button type='button'>Replace</button></a></td>");
				} else {
					echo("<tr><td>");
					echo($row['snum']);
					echo("</td><td>");
					echo($row['manuf']);
					echo("</td><td>");
					echo($row['start']);
					echo("</td><td>");
					echo($row['end']);
					echo("</td>");
				}
				echo("</tr>");
			}
			echo("</table>");
		}

		$connection = null;
	?>

</body>
</html>