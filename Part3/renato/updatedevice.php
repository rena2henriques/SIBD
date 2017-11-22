<!DOCTYPE html>
<html>
<head>
	<title>Update Device:</title>
</head>
<body>
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

			// METER PREPARE AQUI!!!!!!!!!!!!!!!!!!!!!!!

			$patientid =$_REQUEST['patient'];
			$snumOld =$_REQUEST['snumOld'];
			$manufOld =$_REQUEST['manufOld'];
			$snumNew =$_REQUEST['snumNew'];
			$manufNew =$_REQUEST['manufNew'];

			$sql = "SELECT start, end FROM Wears WHERE patient = $patientid and snum = '$snumOld' and manuf = '$manufOld' and datediff(Wears.end, NOW()) > 0";
			$result = $connection->query($sql);
			if ($result == FALSE) {
				$info = $connection->errorInfo();
				echo("<p>Error query:{$info[2]}</p>");
				exit();
			}

			foreach($result as $row){
				$start_date = $row['start'];
				$end_date = $row['end'];
			}

			// PERGUNTAR AO PROF SE É PRECISO METER TRANSACTION
			$connection->beginTransaction();

			// Insert two new periods in the Period table, one for the old device and for the new
			$current_date = date("Y-m-d h:i:s");
			$sql = "Insert into Period values ('$start_date', '$current_date'); Insert into Period values('$current_date', '$end_date');";
			$result = $connection->exec($sql);

			if ($result == FALSE) {
				$info = $connection->errorInfo();
				echo("<p>Error: {$info[2]}</p>");
				exit();
			}

			// UPDATING THE END DATE OF THE OLD DEVICE
			$stmt1 = $connection->prepare("UPDATE Wears SET end = $current_date WHERE patient = :patientid and snum = :snumOld and manuf = :manufOld and start = '$start_date' and end = '$end_date'");

			$stmt1->bindParam(':patientid', $patientid);
			$stmt1->bindParam(':snumOld', $snumOld);
			$stmt1->bindParam(':manufOld', $manufOld);

			// INSERTING THE NEW WEARABLE DATA
			$stmt2 = $connection->prepare("INSERT INTO Wears VALUES ('$current_date',:endDate,:patientid, :snumNew, :manufNew)");

			$stmt2->bindParam(':patientid', $patientid);
			$stmt2->bindParam(':snumNew', $snumNew);
			$stmt2->bindParam(':manufNew', $manufNew);
			$stmt2->bindParam(':endDate', $end_date);

			if ($stmt1->execute() && $stmt2->execute()){
				$connection->commit();
			 	echo("<p>Success, Study was created.</p>");
			} else {
				$connection->rollBack();
			  	 echo 'error executing statement: ' . $stmt->error;
			}			

			// QUANDO CLICAR NO BOTÃO DE TROCAR PARA UM CERTO DEVICE
			// TEM QUE DAR UPDATE DO END DATE DO ATUAL PARA A DATA NO MOMENTO

			header('Location: devices2.php?number=' . $patientid);

			$connection = null;
		?>
</body>
</html>