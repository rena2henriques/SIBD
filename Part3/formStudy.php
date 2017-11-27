
<html>
<head>
	<title>Create a new study:</title>
	<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
</head>
<body>
	<h1 style="font-family: 'Indie Flower', cursive;">Clinic Database:</h1>

	<form method="post" action="createStudy.php">
		<fieldset style="width: 50%;">
		<legend><strong>Register a new Study:</strong></legend>
			<p><strong>Request Number:</strong>
			<select name="requestnumber">
<?php
			$host = "db.ist.utl.pt";
			$user = "ist181588";
			$pass = "gjzf1955";
			$dsn = "mysql:host=$host;dbname=$user";
			try
			{
				$connection = new PDO($dsn, $user, $pass);
			}
			catch(PDOException $exception)
			{
				echo("<p>Error: ");
				echo($exception->getMessage());
				echo("</p>");
				exit();
			}


			$stmt = $connection->prepare("SELECT number FROM Request where patient_id=:patient_id ORDER BY number");
			$stmt->bindParam(':patient_id', $_REQUEST['number']);

			$result = $stmt->execute();
			if ($result == 0){
					$info = $stmt->errorInfo();
					echo("<p> Query Error: {$info[2]}</p>");
					exit();
			}
			foreach($stmt as $row)
			{
				$requestnumber = $row['number'];
				echo("<option value=\"$requestnumber\">$requestnumber</option>");
			}
			$connection = null;
?>
			</select>
			</p>
			<p><strong>Description:</strong>
			<input type="text" name="description" autofocus style="width: 60%;" maxlength="255" placeholder="Description" required /><br></p>
			<p><strong>Date:</strong>
			<input type="text" name="date" autofocus style="width: 40%;" maxlength="20" placeholder="YEAR-MONTH-DAY HH:MM"/><br></p>
			<p><strong>Doctor ID:</strong> 	
			<input type="text" name="doctorid" autofocus style="width: 40%;" maxlength="30" placeholder="Doctor ID" /></p>
			<p><strong>Manufacturer of Device:</strong> 	
			<input type="text" name="manufacturer" autofocus style="width: 50%;" maxlength="255" placeholder="Manufacturer" /></p>
			<p><strong>Serial Number of Device:</strong> 	
			<input type="text" name="serialnumber" autofocus style="width: 40%;" maxlength="255" placeholder="Serial Number" /></p>
			<p><strong>Series ID:</strong> 	
			<input type="text" name="seriesid" autofocus style="width: 20%;" maxlength="30" placeholder="Series ID" required  /><p>
			<p><strong>Series Name:</strong> 	
			<input type="text" name="seriesname" autofocus style="width: 20%;" maxlength="30" placeholder="Series Name" /><p>
			<input type="submit" value="Submit"/></p>
		</fieldset>
	</form>

	<!-- Button to go to home page -->
	<br><form action='checkPatient.html' method='post'>
	<input type='submit' value='Home'/></form>
</body>
</html>