
/*Write	 a	 query	 to	 retrieve	 the	 name(s)	 of	 the	 patient(s)	 	 who	 have	 been	 subject	 of	
studies	with	all	devices	of	manufacturer	“Medtronic”	in	the	past	calendar	year.	 */

/* checks the wearable devices */
select name, w.snum, w.manuf from Patient as p, Wears as w where p.patient_id=w.patient and w.manuf="Medtronic";

/* checks the non wearable devices */
select distinct serialnum, manufacturer 
from Device 
where manufacturer="Medtronic" and serialnum not in 
(select w.snum from Wears as w where w.manuf="Medtronic");

/* checks the devices used in a certain patient study */

select name, count(s.serial_number)
from Patient as p, Request as r, Study as s 
where p.patient_id=r.patient_id and r.request_number=s.request_number and s.manufacturer="Medtronic"
group by s.serial_number;


/* without date */

select name
from Patient as p1
where not exists (
	select d.serialnum
	from Device as d
	where manufacturer="Medtronic" and d.serialnum not in (
		select snum 
		from Wears 
		where manuf="Medtronic") 
	and d.serialnum not in (
		select s.serial_number
		from Patient as p2, Study as s, Request as r
		where p1.patient_id=p2.patient_id and p2.patient_id=r.patient_id and r.request_number=s.request_number and YEAR(current_date)-YEAR(s.date_of_study) = 1 and s.manufacturer="Medtronic"));

select name
from Patient as p1
where not exists (
select d.serialnum
from Device as d
where manufacturer="Medtronic"  and d.serialnum not in (
select w.snum 
from Wears as w 
where w.manuf="Medtronic") 
and d.serialnum not in (
select s.serial_number
from Patient as p2, Study as s, Request as r
where p1.patient_id=p2.patient_id and p2.patient_id=r.patient_id and r.request_number=s.request_number 
and (YEAR(current_date)-YEAR(s.date_of_study)) = 1 /* datediff(current_date, s.date_of_study) < 365*/ and s.manufacturer="Medtronic"));
