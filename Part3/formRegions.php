<!DOCTYPE html>
<html>
<head>
	<title>Insert Regions:</title>
	<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
</head>
<body>
	<h1 style="font-family: 'Indie Flower', cursive;">Clinic Database:</h1>
	
	<form method="post" action="insertRegion.php" autocomplete="off">
		<fieldset style="width: 40%;">
		<legend><strong>Add a new region:</strong></legend>
			<p><strong>Series ID:</strong>

				<select name="seriesid">
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


				$stmt = $connection->prepare("select series_id from Series as s, Request as r where s.request_number = r.number and r.patient_id = :patient_id order by series_id asc;");

				$stmt->bindParam(':patient_id', $_REQUEST['number']);

				$result = $stmt->execute();

				if ($result == 0){
					$info = $stmt->errorInfo();
					echo("<p> Query Error: {$info[2]}</p>");
					exit();
				} else {
					foreach($stmt as $row)
					{
						$seriesid = $row['series_id'];
						echo("<option value=\"$seriesid\">$seriesid</option>");
					}					
				}
				$connection = null;
?>
				</select>
			</p>
			<input type="text" name="seriesid" autofocus style="width: 60%;"  maxlength="30" placeholder="Series ID" required /><br></p>
			<p><strong>Element Index:</strong>
			<input type="text" name="elem_index" autofocus style="width: 50%;" maxlength="30" placeholder="Element Index" required /><br></p>
			<p><strong>X1:</strong>
			<input type="text" name="x1" autofocus style="width: 10%;" maxlength="10" placeholder="x1" required /><br></p>
			<p><strong>Y1:</strong>
			<input type="text" name="y1" autofocus style="width: 10%;" maxlength="10" placeholder="y1" required /><br></p>
			<p><strong>X2:</strong>
			<input type="text" name="x2" autofocus style="width: 10%;" maxlength="10" placeholder="x2" required /><br></p>
			<p><strong>Y2:</strong>
			<input type="text" name="y2" autofocus style="width: 10%;" maxlength="10" placeholder="y2" required /><br></p>
			<p><input type="hidden" name="patient_id" value="<?=$_REQUEST['number']?>"/></p>
			<p><input type="submit" value="Submit"/></p>
		</fieldset>
	</form>

	<!-- Button to go to home page -->
	<br>
	<form action='checkPatient.html' method='post'>
		<input type='submit' value='Home'/>
	</form>

</body>
</html>