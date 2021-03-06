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

		// USELESS, JUST TO BE PRETTY. Decide if worth it
		/*$stmt = $connection->prepare("SELECT name FROM Patient where number=:idnumber");
		$stmt->bindParam(':idnumber', $idnumber);
		$stmt->execute();

		if ($stmt == FALSE) {
			$info = $connection->errorInfo();
			echo("<p>Error: {$info[2]}</p>");
			exit();
		}
	
		foreach ($stmt as $row) {
			echo("<h3>List of devices worn by ");
			echo($row['name']);
			echo (":</h3>");
		}*/

		echo("<h3>List of devices worn by Patient nº" . $idnumber . ":");
		
		// FALTA TESTAR SE ESTÃO ORDENADOS, NÃO ME APETECEU ADICIONAR DEVICES À BASE DE DADOS
		// É preciso imprimir o modelo???

		$stmt = $connection->prepare("SELECT snum, manuf, start, end FROM Wears where patient = :idnumber order by end desc ");

		$stmt->bindParam(':idnumber', $idnumber);

		$stmt->execute();

		if ($stmt == FALSE) {
			$info = $connection->errorInfo();
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
				
				if( strcmp($row['end'], date("Y-m-d H:i",time())) > 0){
					//echo(date("Y-m-d H:i",time()) . "\n");
					echo("<tr><td><strong>");
					echo($row['snum']);
					echo("</strong></td><td>");
					echo($row['manuf']);
					echo("</td><td>");
					echo($row['start']);
					echo("</td><td>");
					echo($row['end']);
					echo("</td>");
					echo("<td><a href=\"replacedev.php?patient=");
					echo($idnumber . "&serialnum=");
					echo($row['snum'] . "&manufacturer=");
					echo($row['manuf']);
					echo("\">Replace</a></td>");
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