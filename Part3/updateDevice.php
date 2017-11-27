<!DOCTYPE html>
<html>
<head>
	<title>Update Device:</title>
</head>
<body>
	<?php
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

			$patientid =$_REQUEST['patient'];
			$snumOld =$_REQUEST['snumOld'];
			$manuf =$_REQUEST['manuf'];
			$snumNew =$_REQUEST['snumNew'];

			$stmt = $connection->prepare("SELECT start, end FROM Wears WHERE patient = :patientid and snum = :snumOld and manuf = :manuf and timediff(end, NOW()) > 0");

			$stmt->bindParam(':patientid', $patientid);
			$stmt->bindParam(':snumOld', $snumOld);
			$stmt->bindParam(':manuf', $manuf);

			$result = $stmt->execute();

			if ($result == FALSE) {
				$info = $stmt->errorInfo();
				echo("<p>Error query dates:{$info[2]}</p>");
				exit();
			}

			$row = $stmt->fetch();
			$start_date = $row['start'];
			$end_date = $row['end'];

			// We want that the update and the insertion to be done simultaneously
			$connection->beginTransaction();

			// Insert two new periods in the Period table, one for the old device and for the new
			// we use this variable because the function time() can give two different results
			$current_date = date("Y-m-d H:i:s");
			$sql = "Insert into Period values ('$start_date', '$current_date'); Insert into Period values('$current_date', '$end_date');";
			$result = $connection->exec($sql);

			if ($result == FALSE) {
				$info = $connection->errorInfo();
				echo("<p>Error: {$info[2]}</p>");
				exit();
			}

			// UPDATING THE END DATE OF THE OLD DEVICE
			$stmt1 = $connection->prepare("UPDATE Wears SET end = :now_date WHERE patient = :patientid and snum = :snumOld and manuf = :manuf and start = :start_date and end = :end_date");

			$stmt1->bindParam(':now_date', $current_date);
			$stmt1->bindParam(':patientid', $patientid);
			$stmt1->bindParam(':snumOld', $snumOld);
			$stmt1->bindParam(':manuf', $manuf);
			$stmt1->bindParam(':start_date', $start_date);
			$stmt1->bindParam(':end_date', $end_date);

			// INSERTING THE NEW WEARABLE DATA
			$stmt2 = $connection->prepare("INSERT INTO Wears VALUES (:now_date,:endDate,:patientid,:snumNew, :manuf)");

			$stmt2->bindParam(':now_date', $current_date);
			$stmt2->bindParam(':endDate', $end_date);
			$stmt2->bindParam(':patientid', $patientid);
			$stmt2->bindParam(':snumNew', $snumNew);
			$stmt2->bindParam(':manuf', $manuf);

			if ($stmt1->execute() && $stmt2->execute()){
				$connection->commit();

				echo("<p>Success, replacement was successful.</p>"); 

			} else {
				$connection->rollBack();
				echo("<p>Replacement wasn't successful.</p>");
			  	echo 'Error executing statement: ' . $stmt1->errorInfo()[2] . ' ' . $stmt2->errorInfo()	[2] ;
			}			

			// Button to go to home page
			echo("<br><form action='checkPatient.html' method='post'>");
			echo("<input type='submit' value='Home'/></form>");

			$connection = null;
		?>
</body>
</html>