delimiter $$

drop trigger if exists check_overlapping_periods_insert;

create trigger check_overlapping_periods_insert before insert on Wears
for each row
begin 
	if exists (
	select start, end
	from Wears 
	where Wears.snum=new.snum and Wears.manuf=new.manuf 
	and timediff(new.start,Wears.end)<0 and timediff(new.end,Wears.start)>0) then
		signal sqlstate '45000'	set message_text =	'Overlapping Periods';
	end if;

end$$

drop trigger if exists check_overlapping_periods_update;

create trigger check_overlapping_periods_update before update on Wears
for each row
begin 
	if exists (
	select start,end
	from Wears 
	where Wears.snum=new.snum and Wears.manuf=new.manuf 
	and Wears.end <> old.end and Wears.start <> old.start
	and timediff(new.start,Wears.end)<0 and timediff(new.end,Wears.start)>0)  then
		signal sqlstate '45000'	set message_text =	'Overlapping Periods';
	end if;

	end$$

delimiter ;

-- some tests


--insert into Period values('2009-03-11 10:11:00','2016-09-21 08:11:00');
--insert into Period values('2009-03-11 10:11:00','2017-09-21 08:11:00');
--> novo
--insert into Wears values('2017-02-02 14:00:00','2999-12-31 00:00:00',7549,"3333",'Medtronic');
-->overlap
--insert into Wears values('2017-03-11 10:11:00','2017-09-21 08:11:00',2,"3333",'Medtronic');
-->overlap
--update Wears set Wears.end='2017-09-21 08:11:00' where Wears.patient=2;
--> nao deu overlap
--update Wears set Wears.end='2016-09-21 08:11:00' where Wears.patient=2;