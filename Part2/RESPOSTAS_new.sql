VER OS NOMES, O PROF DISSE QUE PODIAMOS POR "END" QUANDO NOS QUISESSEMOS REFERIR A END

PERGUNTA 3:

select name
	from Patient as p, Wears as w natural join Sensor as s natural join Reading as r
	where p.number=w.patient and s.units="LDL cholesterol in mg/dL" and r.value>200 and datediff(current_date,datetime)<=90
	group by number
	having count(datetime) >=all (select count(datetime) from Patient as p, Wears as w natural join Sensor as s natural join Reading as r
	where p.number=w.patient and s.units="LDL cholesterol in mg/dL" and r.value>200 and datediff(current_date,datetime)<=90
			group by number);

VER SE É MELHOR COM WHERE OU COM NATURAL JOIN..
-- tem que ser com group by number e não group by name porque pode haver várias pessoas com o mesmo nome


PERGUNTA 4:

select name 
from Patient as p 
where not exists ( select serial_number 
	from Study where manufacturer='Medtronic' 
	and serial_number not in ( select serial_number
		from Study as s, Request as r, Patient as p2 
		where s.request_number=r.number and p2.number=r.patient_id and p.number=p2.number and manufacturer='Medtronic'
		and YEAR(current_date)-YEAR(s.date)=1));

Pergunta 5:

PARA TESTAR:
insert into Request values(721, 7465, 1996, '2010-10-23');
insert into Study values(721,'Echography right arm','2010-10-30',1996,'Samsung',"31");

delimiter $$

create trigger check_doctor_insert before insert on Study
for each row
begin
	if new.doctor_id in (
	select doctor_id 
	from Request 
	where Request.number=new.request_number) then
		call error_doctor_not_allowed();
	end if;

end$$

delimiter ;

delimiter $$

create trigger check_doctor_update before update on Study
for each row
begin
	if new.doctor_id in (
	select doctor_id 
	from Request 
	where Request.number=old.request_number) then
		call error_doctor_not_allowed();
	end if;

end$$

delimiter ;

<---É PRECISO PORMOS NOMES DIFERENTES??

PARA TESTAR::
insert into Wears values('2014-02-15 11:22:00','2999-12-31 00:00:00',9256926,"2000",'Siemens');
insert into Wears values('2014-02-15 11:22:00','2999-12-31 00:00:00',1,"2000",'Siemens');

ASSUMIMOS QUE UM DEVICE NÃO PODE SER USADO POR MAIS DO QUE UM PACIENTE NO MESMO PERIODO DE TEMPO
E QUE UM PACIENTE PODE USAR VÁRIOS DEVICES NO MESMO PERIODO DE TEMPO

ESTE TRIGGER GARANTE QUE O UTILIZADOR NÃO INSERE VÁRIAS VEZES O MESMO DEVICE EM PERIODOS SOBREPOSTOS,
TAL COMO GARANTE QUE SE UM DEVICE ESTIVER A SER UTILIZADO POR UM CERTO PACIENTE, ESTE NÃO PODE SER ATRIBUIDO
A UM OUTRO PACIENTE NO MESMO PERIODO DE TEMPO.

Para evitar escrever todos os casos de sobreposição entre periodos de tempo, decidiu-se testar antes os casos em que 
não há sobreposição e depois negar esta condição. Não pode haver sobreposição quando a data de inicio de um periodo 
é posterior à data de fim de outro, nem quando a data de fim de um periodo é anterior à data de inicio de outro. 
Se existir alguma data de inicio e de fim para um certo Device que se sobreponha com o periodo de tempo da nova inserção,
então é feita uma chamada a erro impedindo que ocorra a inserção (ou atualização).

delimiter $$

create trigger check_overlapping_periods before insert on Wears
for each row
begin 
	if exists (
	select start, end
	from Wears 
	where Wears.snum=new.snum and Wears.manuf=new.manuf 
	and not (datediff(new.start,Wears.end)>=0 or datediff(new.end,Wears.start)<0)) then
		signal sqlstate '45000'	set message_text =	'Overlapping Periods';
	end if;

end$$

delimiter;

delimiter $$

create trigger check_overlapping_periods_update before update on Wears
for each row
begin 
	if exists (
	select start_date,end_date
	from Wears 
	where Wears.snum=new.snum and Wears.manuf=new.manuf 
	and not (datediff(new.start_date,Wears.end_date)>=0 or datediff(new.end_date,Wears.start_date)<0)) then
		signal sqlstate '45000'	set message_text =	'Overlapping Periods';
	end if;

end$$

delimiter;

PERGUNTA 6:


NOPE, NAO FUNCIONA
DELIMITER $$

create function region_overlaps_element(idA int unsigned,indexA int unsigned,x1B FLOAT(4,3),y1B FLOAT(4,3),x2B FLOAT(4,3),y2B FLOAT(4,3))
returns BOOLEAN
begin	

	if x1B>x2B then set x1B=x1B^x2B; set x2B=x1B^x2B; set x1B=x1B^x2B; end if;
	if y1B>y2B then set y1B=y1B^y2B; set y2B=y1B^y2B; set y1B=y1B^y2B; end if;

	return exists(select rA.series_id, rA.elem_index
		from Region as rA
		where rA.series_id=idA and rA.elem_index=indexA
		and if x1>x2 then set x1=x1^x2; set x2=x1^x2; set x1=x1^x2; end if;
		and if y1>y2 then set y1=y1^y2; set y2=y1^y2; set y1=y1^y2; end if;
		and not (rA.x2<x1B or rA.x1>x2B or rA.y2<y1B or rA.y1>y2B));
end$$
DELIMITER ;


create function region_overlaps_element(idA int unsigned,indexA int unsigned,x1B FLOAT(4,3),y1B FLOAT(4,3),x2B FLOAT(4,3),y2B FLOAT(4,3))
returns BOOLEAN
begin	

	if x1B>x2B then set x1B=x1B^x2B; set x2B=x1B^x2B; set x1B=x1B^x2B; end if;
	if y1B>y2B then set y1B=y1B^y2B; set y2B=y1B^y2B; set y1B=y1B^y2B; end if;

	return exists(select rA.series_id, rA.elem_index
		from Region as rA
		where rA.series_id=idA and rA.elem_index=indexA
		and not (rA.x2>rA.x1 and  (rA.x2<x1B or rA.x1>x2B))
		and not(rA.y2>rA.y1 and (rA.y2<y1B or rA.y1>y2B))
		and not (rA.x1>rA.x2  and (rA.x1<x1B or rA.x2>x2B)) 
		and not(rA.y1>rA.y2 and( rA.y1<y1B or rA.y2>y2B)));
end$$
DELIMITER ;

Testes:
0.5 a 0.7
e 0.1 a 0.3
select region_overlaps_element(4000,1,0.1,0.5,0.3,0.7)

FUNCIONA:
delimiter $$

drop function if exists region_overlaps_element;

create function region_overlaps_element(idA int unsigned,indexA int unsigned,x1B FLOAT(4,3),y1B FLOAT(4,3),x2B FLOAT(4,3),y2B FLOAT(4,3))
returns BOOL
begin	

	if x1B>x2B then set x1B=x1B+x2B; set x2B=x1B-x2B; set x1B=x1B-x2B; end if;
	if y1B>y2B then set y1B=y1B+y2B; set y2B=y1B-y2B; set y1B=y1B-y2B; end if;

	return exists(select rA.series_id, rA.elem_index
		from Region as rA
		where rA.series_id=idA and rA.elem_index=indexA 
		and ((rA.x2>=rA.x1 and not (rA.x2<=x1B or rA.x1>=x2B)) or (rA.x1>rA.x2 and not(rA.x1<=x1B or rA.x2>=x2B)))
		and ((rA.y2>=rA.y1 and not (rA.y2<=y1B or rA.y1>=y2B)) or (rA.y1>rA.y2 and not ( rA.y1<=y1B or rA.y2>=y2B))));
end$$
delimiter ;


/*and ((rA.x2>=rA.x1 and not (rA.x2<=x1B or rA.x1>=x2B)) or (rA.x1>rA.x2  and  not(rA.x1<=x1B or rA.x2>=x2B)))
		and ((rA.y2>=rA.y1 and not (rA.y2<=y1B or rA.y1>=y2B))or (rA.y1>rA.y2 and not ( rA.y1<=y1B or rA.y2>=y2B))));*/