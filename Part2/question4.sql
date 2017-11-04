
/*Write	 a	 query	 to	 retrieve	 the	 name(s)	 of	 the	 patient(s)	 	 who	 have	 been	 subject	 of	
studies	with	all	devices	of	manufacturer	“Medtronic”	in	the	past	calendar	year.	 */

/* checks the wearable devices */
select name, w.snum, w.manuf from Patient as p, Wears as w where p.patient_id=w.patient and w.manuf="Medtronic";

/* checks the non wearable devices */
select distinct serialnum, manufacturer
from Device where serialnum, manufacturer not in 
(select w.snum, w.manuf from Patient as p, Wears as w where p.patient_id=w.patient and w.manuf="Medtronic");