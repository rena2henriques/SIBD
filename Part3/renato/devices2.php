<!DOCTYPE html>
<html>
<head>
	<title>Device of patient</title>
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

		$idnumber =$_REQUEST['number'];

		$sql = "SELECT name FROM Patient where number=$idnumber";
		$result = $connection->query($sql);
		if ($result == FALSE) {
			$info = $connection->errorInfo();
			echo("<p>Error: {$info[2]}</p>");
			exit();
		}
	
		foreach ($result as $row) {
			echo("<h3>List of devices worn by ");
			echo($row['name']);
			echo (":</h3>");
		}
		
		// FALTA TESTAR SE ESTÃO ORDENADOS, NÃO ME APETECEU ADICIONAR DEVICES À BASE DE DADOS

		$sql = "SELECT serialnum, manufacturer, model, start, end FROM Wears as w, Device as d where w.patient = $idnumber and d.serialnum = w.snum and d.manufacturer = w.manuf order by w.end asc ";
		$result = $connection->query($sql);
		if ($result == FALSE) {
			$info = $connection->errorInfo();
			echo("<p>Error: {$info[2]}</p>");
			exit();
		}

		$nrows = $result->rowCount();
		if ($nrows == 0) {
			echo("<p>Patient isn't wearing any device.</p>");
			// think what we want to do here -> add a device?

		} else {
			echo("<table border=\"1\" cellspacing=\"5\">");
			echo("<tr><td><strong>Serial Number</strong></td><td><strong>Manufacturer</strong></td><td><strong>Model</strong></td><td><strong>Start Date</strong></td><td><strong>End Date</strong></td></tr>");
			foreach($result as $row) {
				echo("<tr><td>");
				// FALTA METER SERIAL NUM EM HIGHLIGHT QUANDO O ANO END FOR 2999
				$auxserialnum = $row['serialnum'];
				echo($auxserialnum);
				echo("</td><td>");
				$auxmanuf = $row['manufacturer'];
				echo($auxmanuf);
				echo("</td><td>");
				echo($row['model']);
				echo("</td><td>");
				echo($row['start']);
				echo("</td><td>");
				$auxdate = $row['end'];
				echo($auxdate);
				echo("</td>");
				if( strstr($auxdate, '2999') != FALSE){
					// NOT SURE IF THIS IS THE CORRECT WAY TO DO THIS
					echo("<td><a href=\"replacedev.php?patient=");
					echo($idnumber);
					echo("&serialnum=");
					echo($auxserialnum);
					echo("&manufacturer=");
					echo($auxmanuf);
					echo("\">Replace</a></td>");
				}
				echo("</tr>");
			}
			echo("</table>");
		}

		$connection = null;
	?>




</body>
</html>