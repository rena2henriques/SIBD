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

		$result = $stmt->execute();

		if ($result == 0){
			$info = $stmt->errorInfo();
			echo("<p> Error, the insertion was not successful: {$info[2]}</p>");
		} else {
			echo("<p> New patient was inserted successfully. </p>");

			echo("<table border=\"1\" cellspacing=\"5\">");
			echo("<tr><td><strong>Name</strong></td><td><strong>ID number</strong></td><td><strong>Birthday</strong></td><td><strong>Address</strong></td></tr>");
			echo("<tr>");
			foreach($_REQUEST as $value) {
				echo("<td>");
				echo($value);
				echo("</td>");
			}
			echo("</tr>");
			echo("</table><br>");
		}
		
		echo("<form action='checkPatient.html' method='post'>");
		echo("<input type='submit' value='Home'/></form>");
		
		$connection = null;
	?>