<!DOCTYPE html>
<html>
<head>
	<title>New Patient 1:</title>
	<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
</head>
<body>
	<h1 style="font-family: 'Indie Flower', cursive;">Paulo's Clinic:</h1>
	<p>Sorry, patient wasn't found on the database.</p>
	<form method="post" action="handleNewPatient.php" autocomplete="off">
		<fieldset style="width: 50%;">
		<legend><strong>Register a new patient:</strong></legend>
			<p><strong>Name:</strong>
			<input type="text" name="Name" autofocus style="width: 60%;"  maxlength="255" placeholder="Name" /><br></p>
			<p><strong>ID Number:</strong>
			<input type="text" name="Number" autofocus style="width: 40%;" maxlength="30" placeholder="ID Number"/><br></p>
			<p><strong>Birthday Date:</strong>
			<input type="text" name="Birthday" autofocus style="width: 40%;" maxlength="11" placeholder="YEAR-MONTH-DAY"/><br></p>
			<p><strong>Address:</strong> 	
			<input type="text" name="Address" autofocus style="width: 70%;" maxlength="255" placeholder="Address" /></p>
			<p><input type="submit" value="Submit"/></p>
		</fieldset>
	</form>
</body>
</html>