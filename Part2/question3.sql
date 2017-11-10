

/* Write	 a	 query	 to	 retrieve	 the	 name(s)	 of	 the	 patient(s)	 with	 the	 highest	 number	 of	
readings	of		units	of	“LDL	cholesterol	in	mg/dL”	above	200	in	the	past	90	days.	*/ 

select p.name 
from Patient as p, Wears as w, Sensor as s, Reading as r 
where p.patient_id=w.patient and w.snum = s.snum and w.manuf=s.manuf and s.units="LDL cholesterol in mg/dL" and r.snum=s.snum and r.manuf=s.manuf and datediff(current_date, date_of_reading) <= 90 and r.value > 200 
group by p.patient_id having count(r.snum) >= all(
	select count(r.snum) 
	from Patient as p, Wears as w, Sensor as s, Reading as r 
	where p.patient_id=w.patient and w.snum = s.snum and w.manuf=s.manuf and s.units="LDL cholesterol in mg/dL" and r.snum=s.snum and r.manuf=s.manuf and datediff(current_date, date_of_reading) <= 90 and r.value > 200 
	group by p.patient_id
);