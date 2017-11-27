<!DOCTYPE html>
<html>
<head>
	<title>Insertion of new region</title>
	<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
</head>
<body>
	<h1 style="font-family: 'Indie Flower', cursive;" href="checkPatient.html">Clinic Database:</h1>

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
		
			// get the region info
			$series_id =$_REQUEST['seriesid'];
			$elem_index =$_REQUEST['elem_index'];
			$patient_id =$_REQUEST['patient_id'];
			$x1 =$_REQUEST['x1'];
			$y1 =$_REQUEST['y1'];
			$x2 =$_REQUEST['x2'];
			$y2 =$_REQUEST['y2'];

			// Assumimos que se insere sempre

			$stmt = $connection->prepare("INSERT INTO Region VALUES (:series_id,:elem_index,:x1,:y1,:x2,:y2)");

			$stmt->bindParam(':series_id', $series_id);
			$stmt->bindParam(':elem_index', $elem_index);
			$stmt->bindParam(':x1', $x1);
			$stmt->bindParam(':y1', $y1);
			$stmt->bindParam(':x2', $x2);
			$stmt->bindParam(':y2', $y2);

			$stmt->execute();

			if ($stmt == FALSE) {
				$info = $stmt->errorInfo();
				echo("<p>Error: {$info[2]}</p>");
				exit();
			}
			
			if($stmt->rowCount() > 0){
				echo("<p>Row successfully inserted </p>");	
			}
			else {
				echo("<p> Error inserting new Region </p>");
				exit();
			}
			
			// QUERY -> get all regions of an element from the last study of the patient (if there was one)
			$stmt = $connection->prepare("select x1,y1,x2,y2 from Region as r, Series as s, Study as st, Request as rq where r.series_id=s.series_id and s.request_number=st.request_number and s.description=st.description and st.request_number=rq.number and rq.patient_id= :patient_id and st.date >=all (select st1.date from Study as st1, Request as rq1 where st1.request_number=rq1.number and rq1.patient_id= :patient_id );");
			
			$stmt->bindParam(':patient_id', $patient_id);
			$stmt->execute();
			
			if ($stmt == FALSE) {
				$info = $stmt->errorInfo();
				echo("<p>Error: {$info[2]}</p>");
				exit();
			}
			// if the new region doesnt overlap with one of these, then print a message saying "new clinical evidence"
			if($x1 > $x2) {
				$aux = $x1;
				$x1 = $x2;
				$x2 = $aux; 
			}

			if($y1 > $y2) {
				$aux = $y1;
				$y1 = $y2;
				$y2 = $aux; 
			}
			
			//se não houver nenhum study acho que o gajo não entra no foreach, e como esta flag continua a 0, dá new region of interest, não sei se é isso o suposto
			$flag_overlap = 0;
			foreach($stmt as $row){

				if($x1 == $row['x1'] and $x2 == $row['x2'] and $y1 == $row['y1'] and $y2 == $row['y2'] ) {
					continue; //do not compare the newly inserted region with itself, goto next row
				}

				if($row['x1'] > $row['x2']){
					$aux = $row['x1'];
					$row['x1'] = $row['x2'];
					$row['x2'] = $aux; 
				}

				if($y1_last > $y2_last){
					$aux = $y1_last;
					$y1_last = $y2_last;
					$y2_last = $aux; 
				}

				if ($x1_last >= $x2 || $x2_last <= $x1 || $y1_last >= $y2 || $y2_last <= $y1) {
					continue;
					// keeps searching
				} else {
					// prints and break, there's overlap
				}
				
				if($x1 >= $row['x2'] or $x2 <= $row['x1'] or $y1 >= $row['y2'] or $y2 <= $row['y1']) { //there is no overlapping for this region
					continue; //next region if exists
				} else {
					$flag_overlap = 1;
					break;	//first overlapping, no need to continue
				}
			}
			// if we got to the end and there was no overlap, then we print that message
			if($flag_overlap == 0) {
				echo("<p>There is a new region of interest</p>");
			}
			else {
				echo("<p>No new region of interest </p>");
			}

			// Button to go to home page
			echo("<br><form action='checkPatient.html' method='post'>");
			echo("<input type='submit' value='Home'/></form>");

			$connection = null;
		?>

</body>
</html>

