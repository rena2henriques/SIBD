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