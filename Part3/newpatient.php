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

		$name =$_REQUEST['name'];
		$number =$_REQUEST['number'];
		$birthday =$_REQUEST['birthday'];
		$address =$_REQUEST['address'];

		$stmt = $connection->prepare("INSERT INTO Patient VALUES (:number, :name, :birthday, :address)");

		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':number', $number);
		$stmt->bindParam(':birthday', $birthday);
		$stmt->bindParam(':address', $address);

		$nrows = $stmt->execute();

		if ($stmt ==  FALSE) {
			$info = $stmt->errorInfo();
			echo("<p>Error: {$info[2]}</p>");
			exit();
		}

		if ($nrows == 0){
			echo("<p> Error, the insertion was not successful. </p>");
		} else {
			echo("<p> New patient was inserted successfully. </p>");
		}
		
		echo("<form action='checkPatient.html' method='post'>");
		echo("<input type='submit' value='Home'/></form>");
		
		$connection = null;
	?>