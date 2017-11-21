<!DOCTYPE html>
<html>
<head>
	<title>Create a new study:</title>
	<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
</head>
<body>
	<h1 style="font-family: 'Indie Flower', cursive;">Paulo's Clinic:</h1>

	<form method="post" action="newstudy.php" autocomplete="off">
		<fieldset style="width: 50%;">
		<legend><strong>Register a new Study:</strong></legend>
			<p><strong>Request Number:</strong>
			<input type="text" name="requestnumber" autofocus style="width: 50%;"  maxlength="30" placeholder="Request Number" /><br></p>
			<p><strong>Description:</strong>
			<input type="text" name="description" autofocus style="width: 60%;" maxlength="255" placeholder="Description"/><br></p>
			<p><strong>Date:</strong>
			<input type="text" name="date" autofocus style="width: 40%;" maxlength="20" placeholder="YEAR-MONTH-DAY HH:MM"/><br></p>
			<p><strong>Doctor ID:</strong> 	
			<input type="text" name="doctorid" autofocus style="width: 40%;" maxlength="30" placeholder="Doctor ID" /></p>
			<p><strong>Manufacturer of the Device:</strong> 	
			<input type="text" name="manufacturer" autofocus style="width: 50%;" maxlength="255" placeholder="Manufacturer" /></p>
			<p><strong>Serial Number of the Device:</strong> 	
			<input type="text" name="serialnumber" autofocus style="width: 40%;" maxlength="255" placeholder="Serial Number" /></p>		
			<p><input type="submit" value="Submit"/></p>
		</fieldset>
	</form>
</body>
</html>