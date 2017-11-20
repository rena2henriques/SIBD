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
		$sql = "SELECT * FROM Device as d WHERE serialnum not in (SELECT snum FROM Wears WHERE datediff(Wears.end, '2999-12-31 00:00:00') = 0)";

		$result = $connection->query($sql);
		if ($result == FALSE) {
			$info = $connection->errorInfo();
			echo("<p>Error: {$info[2]}</p>");
			exit();
		}
	
		echo("<h3>Devices available for replacement:</h3>");

		$nrows = $result->rowCount();
		if ($nrows == 0) {
			echo("<p>No device available at the moment.</p>");
			// think what we want to do here -> add a device?
		} else {
			echo("<table border=\"1\" cellspacing=\"5\">");
			echo("<tr><td><strong>Serial Number</strong></td><td><strong>Manufacturer</strong></td><td><strong>Model</strong></td></tr>");
			foreach($result as $row) {
				echo("<tr><td>");
				echo($row['serialnum']);
				echo("</td><td>");
				echo($row['manufacturer']);
				echo("</td><td>");
				echo($row['model']);
				echo("</td><td>");
				echo("<a href=\"changedev.php?patient=");
				echo($idnumber);
				echo("\">Change</a>");
				echo("</td></tr>");

				// PUT BUTTON THAT UPDATES DE WEARS VALUE
			}
			echo("</table>");
		}
		
		// QUANDO CLICAR NO BOTÃO DE TROCAR PARA UM CERTO DEVICE
		// TEM QUE DAR UPDATE DO END DATE DO ATUAL PARA A DATA NO MOMENTO

		$connection = null;
	?>

</body>
</html>