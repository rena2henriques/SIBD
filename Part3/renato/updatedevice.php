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

			$patientid =$_REQUEST['patient'];
			$snumOld =$_REQUEST['snumOld'];
			$manufOld =$_REQUEST['manufOld'];
			$snumNew =$_REQUEST['snumNew'];
			$manufNew =$_REQUEST['manufNew'];

			$sql = "SELECT end FROM Wears WHERE patient = $patientid and snum = '$snumOld' and manuf = '$manufOld'";
			$result = $connection->query($sql);
			if ($result == FALSE) {
				$info = $connection->errorInfo();
				echo("<p>Error query:{$info[2]}</p>");
				exit();
			}

			foreach($result as $row){
				$auxEnd = $row['end'];
			}

			// UPDATING THE END DATE OF THE OLD DEVICE
			$stmt1 = $connection->prepare("UPDATE Wears SET end = NOW() WHERE patient = :patientid and snum = :snumOld and manuf = :manufOld");

			$stmt1->bindParam(':patientid', $patientid);
			$stmt1->bindParam(':snumOld', $snumOld);
			$stmt1->bindParam(':manufOld', $manufOld);

			$stmt1->execute();

			// INSERTING THE NEW WEARABLE DATA
			$stmt2 = $connection->prepare("INSERT INTO Wears (start, end, patient, snum, manuf) VALUES (NOW(),:endDate,:patientid, :snumNew, :manufNew)");

			$stmt2->bindParam(':patientid', $patientid);
			$stmt2->bindParam(':snumNew', $snumNew);
			$stmt2->bindParam(':manufNew', $manufNew);
			$stmt2->bindParam(':endDate', $auxEnd);

			$stmt2->execute();

			// QUANDO CLICAR NO BOTÃO DE TROCAR PARA UM CERTO DEVICE
			// TEM QUE DAR UPDATE DO END DATE DO ATUAL PARA A DATA NO MOMENTO

			header('Location: devices2.php?number=' . $patientid);

			$connection = null;
		?>
</body>
</html>