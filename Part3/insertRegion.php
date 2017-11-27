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


			// QUERY -> get all regions of an element from the last study of the patient (if there was one)
			$stmt1 = $connection->prepare("select x1,y1,x2,y2 from Region as r, Series as s, Study as st, Request as rq where r.series_id=s.series_id and s.request_number=st.request_number and s.description=st.description and st.request_number=rq.number and rq.patient_id= :patient_id and st.date >=all (select st1.date from Study as st1, Request as rq1 where st1.request_number=rq1.number and rq1.patient_id= :patient_id );");
			
			$stmt1->bindParam(':patient_id', $patient_id);
			$result1 = $stmt1->execute(); 	

			$stmt = $connection->prepare("INSERT INTO Region VALUES (:series_id,:elem_index,:x1,:y1,:x2,:y2)");

			$stmt->bindParam(':series_id', $series_id);
			$stmt->bindParam(':elem_index', $elem_index);
			$stmt->bindParam(':x1', $x1);
			$stmt->bindParam(':y1', $y1);
			$stmt->bindParam(':x2', $x2);
			$stmt->bindParam(':y2', $y2);

			$result = $stmt->execute();

			if ($result == FALSE) {
				$info = $stmt->errorInfo();
				echo("<p> Error inserting new Region:</p>");
				echo("<p>{$info[2]}</p>");
				exit();
			}
			else {
				echo("<p>Region successfully inserted </p>");	

				// we only check if there is overlapping when the region is inserted

				if ($result1 == FALSE) {
					$info = $stmt1->errorInfo();
					echo("<p>Error while obtaining the other regions of the last study of this patient: {$info[2]}</p>");
					exit();
				}

				$nrows = $stmt1->rowCount();

				// in case of not finding any regions for this patient in his last study or not finding any study
				if ($nrows == 0) {
					echo("<p>The pacient hasn't been subject to a study or there aren't regions associated with the patient's last study</p>");
				}

				else{

					//checking if x1>y1 and x2>y2, if this condition is not met we change the coordinates
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

					$flag_overlap = 0;

					foreach($stmt1 as $row){

						//checking if x1>y1 and x2>y2, if this condition is not met we change the coordinates
						if($row['x1'] > $row['x2']){
							$aux = $row['x1'];
							$row['x1'] = $row['x2'];
							$row['x2'] = $aux; 
						}

						if($row['y1'] > $row['y2']){
							$aux = $row['y1'];
							$row['y1'] = $row['y2'];
							$row['y2'] = $aux; 
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
						echo("<p>There is new clinical evidence for this patient </p>");
					}
					else {
						echo("<p>No new clinical evidence - the region inserted overlaps at least with one of the regions of the last study of the patient </p>");
					}

				}
			}
			
			
			// Button to go to home page
			echo("<br><form action='checkPatient.html' method='post'>");
			echo("<input type='submit' value='Home'/></form>");

			$connection = null;
		?>

</body>
</html>

