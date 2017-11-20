<!DOCTYPE html>
<html>
<head>
	<title>Register New Patient</title>
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

		$name =$_REQUEST['Name'];
		$number =$_REQUEST['Number'];
		$birthday =$_REQUEST['Birthday'];
		$address =$_REQUEST['Address'];

		$stmt = $connection->prepare("INSERT INTO Patient VALUES (:number, :name, :birthday, :address)");

		$stmt->bindParam(':number', $number);
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':birthday', $birthday);
		$stmt->bindParam(':address', $address);

		$nrows = $stmt->execute();

		header('Location: question1.php');

		$connection = null;
	?>
</body>
</html>