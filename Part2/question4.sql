select name 
from Patient as p 
where not exists ( select serial_number 
	from Study as d where manufacturer='Medtronic' 
	and serial_number not in ( select serial_number
		from Study as s, Request as r, Patient as p2 
		where s.request_number=r.number and p2.number=r.patient_id and p.number=p2.number and manufacturer='Medtronic'
		and YEAR(current_date)-YEAR(s.date)=1));