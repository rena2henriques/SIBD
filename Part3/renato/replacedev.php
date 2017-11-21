<!DOCTYPE html>
<html>
<head>
	<title>Replace Device:</title>
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

		$idnumber =$_REQUEST['patient'];
		$devId = $_REQUEST['serialnum'];
		$manuf = $_REQUEST['manufacturer'];

		// Select all the devices that aren't being worn at the moment
		// I DONT THINK THIS IS DONE IN THE MOST CORRECT WAY

		$stmt = $connection->prepare("SELECT * FROM Device as d WHERE manufacturer = :manuf and serialnum not in (SELECT snum FROM Wears WHERE datediff(Wears.end, NOW()) > 0)");

		// TESTAR SE ISTO ESTÁ A FUNCIONAR, A DATABASE NÃO TEM DEVICES DA MESMA MARCA SUFICIENTES PARA TESTAR

		$stmt->bindParam(":manuf", $manuf);

		$stmt->execute();
		if ($stmt == FALSE) {
			$info = $connection->errorInfo();
			echo("<p>Error: {$info[2]}</p>");
			exit();
		}
	
		echo("<h3>Devices available for replacement:</h3>");

		$nrows = $stmt->rowCount();
		if ($nrows == 0) {
			echo("<p>No device available at the moment.</p>");
			// think what we want to do here -> add a device?
		} else {
			echo("<table border=\"1\" cellspacing=\"5\">");
			echo("<tr><td><strong>Serial Number</strong></td><td><strong>Manufacturer</strong></td><td><strong>Model</strong></td></tr>");
			foreach($stmt as $row) {
				echo("<tr><td>");
				echo($row['serialnum']);
				echo("</td><td>");
				echo($row['manufacturer']);
				echo("</td><td>");
				echo($row['model']);
				echo("</td><td>");
				//BUTTON THAT UPDATES DE WEARS VALUE
				echo("<a href=\"updatedevice.php?patient=");
				echo($idnumber . '&snumOld=');
				echo($devId . '&manufOld=');
				echo($manuf . '&snumNew=');
				echo($row['serialnum'] . '&manufNew=');
				echo($row['manufacturer']);
				echo("\">Change</a>");
				echo("</td></tr>");
			}
			echo("</table>");
		}

		$connection = null;
	?>

</body>
</html>