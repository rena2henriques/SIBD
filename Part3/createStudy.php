<!DOCTYPE html>
<html>
<head>
	<title>Update Study:</title>
</head>
<body>
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

			$requestnumber =$_REQUEST['requestnumber'];
			$description =$_REQUEST['description'];
			$date =$_REQUEST['date'];
			$doctorid =$_REQUEST['doctorid'];
			$manufacturer =$_REQUEST['manufacturer'];
			$serialnumber =$_REQUEST['serialnumber'];
			$seriesid =$_REQUEST['seriesid'];
			$seriesname =$_REQUEST['seriesname'];

			$base_url = 'http://web.tecnico.ulisboa.pt/' . $user . '/series/' . $seriesid;

			$connection->beginTransaction();

			$stmt1 = $connection->prepare("INSERT INTO Study VALUES (:requestnumber,:description,:date, :doctorid, :manufacturer,:serialnumber)");

			$stmt1->bindParam(':requestnumber', $requestnumber);
			$stmt1->bindParam(':description', $description);
			$stmt1->bindParam(':date', $date);
			$stmt1->bindParam(':doctorid', $doctorid);
			$stmt1->bindParam(':manufacturer', $manufacturer);
			$stmt1->bindParam(':serialnumber', $serialnumber);

			$stmt2 = $connection->prepare("INSERT INTO Series VALUES (:seriesid, :name, :base_url,:requestnumber,:description)");

			// series id is unique!!
			$stmt2->bindParam(':seriesid', $seriesid); 
			$stmt2->bindParam(':name', $seriesname);
			$stmt2->bindParam(':base_url', $base_url);
			$stmt2->bindParam(':requestnumber', $requestnumber);
			$stmt2->bindParam(':description', $description);

			if ($stmt1->execute() && $stmt2->execute()){
				$connection->commit();

					echo("<p>Success, study created.</p>");

					echo("<p><strong>Study values inserted:</strong></p>");
					echo("<table border=\"1\" cellspacing=\"5\">");
					echo("<tr><td><strong>Request Number</strong></td><td><strong>Description</strong></td><td><strong>Date</strong></td><td><strong>Doctor ID</strong></td><td><strong>Manufacturer</strong></td></td><td><strong>Serial Number</strong></td></tr>");
					echo("<tr>");
					echo("<td> $requestnumber </td>");
					echo("<td> $description </td>");
					echo("<td> $date </td>");
					echo("<td> $doctorid </td>");
					echo("<td> $manufacturer </td>");
					echo("<td> $serialnumber </td>");
					echo("</tr>");
					echo("</table><br>");

					echo("<p><strong>Series values inserted:</strong></p>");
					echo("<table border=\"1\" cellspacing=\"5\">");
					echo("<tr><td><strong>ID</strong></td><td><strong>Name</strong></td></tr>");
					echo("<tr>");
					echo("<td>$seriesid</td>");
					echo("<td> $seriesname</td>");
					echo("</tr>");
					echo("</table><br>");

			} else {
				$connection->rollBack();
				echo("<p>Study not created.</p>");
			  	echo 'Error executing statement: ' . $stmt1->errorInfo()[2] . ' ' . $stmt2->errorInfo()[2] ;
			}
			
			// Button to go to home page
			echo("<br><form action='checkPatient.html' method='post'>");
			echo("<input type='submit' value='Home'/></form>");

			$connection = null;
		?>
</body>
</html