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

			$requestnumber =$_REQUEST['requestnumber'];
			$description =$_REQUEST['description'];
			$date =$_REQUEST['date'];
			$doctorid =$_REQUEST['doctorid'];
			$manufacturer =$_REQUEST['manufacturer'];
			$serialnumber =$_REQUEST['serialnumber'];

			
			$connection->beginTransaction();

			$stmt1 = $connection->prepare("INSERT INTO Study VALUES (:requestnumber,:description,:date, :doctorid, :manufacturer,:serialnumber)");

			$stmt1->bindParam(':requestnumber', $requestnumber);
			$stmt1->bindParam(':description', $description);
			$stmt1->bindParam(':date', $date);
			$stmt1->bindParam(':doctorid', $doctorid);
			$stmt1->bindParam(':manufacturer', $manufacturer);
			$stmt1->bindParam(':serialnumber', $serialnumber);

			if ($stmt1->execute()){
				$connection->commit();
			 	echo("<p>Success, Study was created.</p>");
			} else {
				$connection->rollBack();
			  	 echo 'error executing statement: ' . $stmt->error;
			}
			
			$connection = null;
		?>
</body>
</html>