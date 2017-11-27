<!DOCTYPE html>
<html>
<head>
	<title>Replace Device:</title>
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

		$idnumber =$_REQUEST['patient'];
		$devId = $_REQUEST['serialnum'];
		$manuf = $_REQUEST['manufacturer'];

		// Select all the devices that aren't being worn at the moment
		$stmt = $connection->prepare("SELECT serialnum, model FROM Device as d WHERE manufacturer = :manuf and serialnum not in (SELECT snum FROM Wears WHERE manuf = :manuf and timediff(Wears.end, NOW()) > 0)");

		$stmt->bindParam(":manuf", $manuf);

		$result = $stmt->execute();
		if ($result == FALSE) {
			$info = $stmt->errorInfo();
			echo("<p>Error: {$info[2]}</p>");
			exit();
		}
	
		echo("<h3>Devices of manufacturer <i>{$manuf}</i> available for replacement:</h3>");

		$nrows = $stmt->rowCount();
		if ($nrows == 0) {
			echo("<p>No device available at the moment.</p>");
		} else {
			echo("<table border=\"1\" cellspacing=\"5\">");
			echo("<tr><td><strong>Serial Number</strong></td><td><strong>Model</strong></td></tr>");
			foreach($stmt as $row) {
				echo("<tr><td>");
				echo($row['serialnum']);
				echo("</td><td>");
				echo($row['model']);
				echo("</td><td>");
				//BUTTON THAT UPDATES THE WEARS VALUE
				echo("<a href=\"updateDevice.php?patient=");
				echo($idnumber . '&snumOld=');
				echo($devId . '&manuf=');
				echo($manuf . '&snumNew=');
				echo($row['serialnum']);
				echo("\"><button type='button'>Change</button></a>");
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