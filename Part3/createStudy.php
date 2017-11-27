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

			 	if($stmt1->rowCount() > 0 && $stmt2->rowCount() > 0){
					echo("<p>Success, study created.</p>");
				} else {
					echo("<p>Error, study not created.</p>");
				}

			} else {
				$connection->rollBack();
			  	echo 'Error executing statement: ' . $stmt1->errorInfo()[2] . ' ' . $stmt2->errorInfo()[2] ;
			}
			
			$connection = null;
		?>
</body>
</html