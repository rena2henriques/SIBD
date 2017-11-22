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

		foreach ($_REQUEST as $name => $value) {
			echo "<p>$name = $value</p>";
		}
		
		//header('Location: listPatients.php');

		$connection = null;
	?>