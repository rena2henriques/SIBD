
	
select name
from Patient as p, Wears as w natural join Sensor as s natural join Reading as r
where p.number=w.patient and r.datetime between w.start and w.end and s.units="LDL cholesterol in mg/dL" and r.value>200 and datediff(current_date,datetime)<=90
group by number
having count(datetime) >=all (select count(datetime) from Patient as p, Wears as w natural join Sensor as s natural join Reading as r
	where p.number=w.patient and  r.datetime between w.start and w.end and s.units="LDL cholesterol in mg/dL" and r.value>200 and datediff(current_date,datetime)<=90
	group by number);