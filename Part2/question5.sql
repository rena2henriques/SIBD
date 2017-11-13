
/*Write	 triggers	 to:	 */

/*i) ensure	 that	 a	 doctor	 who	 prescribes	 an	 exam	 may	 not	 perform	
that	same	exam*/

delimiter $$

create trigger check_doctor before insert on Study
for each row
begin
	if new.doctor_id in (select doctor_id from Request where Request.request_number = new.request_number)  then
		signal sqlstate '45000' set message_text = 'A doctor who prescribes an exam may not perform that same exam';
	end if;
end $$ 

delimiter;

/*	ii)	prevent	someone	from	trying	to	associate	a	device	to	a	patient	in	
overlapping	 periods.	 Additionally,	 fire	 an	 error	 message	 with	 text	 “Overlapping	
Periods”	when	this	event	occurs.*/

delimiter $$

create trigger check_device before insert on Wears
for each row
begin
	if new.snum, new.snum in (select snum, manuf from Wears as w 
		where (datediff(new.start_date, w.start_date) >= 0 and datediff(new.end_date, w.end_date) <= 0) or 
		(datediff(new.start_date, w.start_date) <= 0 and datediff(new.end_date, w.end_date) >= 0) or
		(datediff(new.start_date, w.start_date) <= 0 and datediff(new.end_date, w.start_date) > 0 and datediff(new.end_date, w.end_date) <= 0) or
		(datediff(new.start_date, w.start_date) >= 0 and datediff(new.start_date, w.end_date) < 0 and datediff(new.end_date, w.end_date) >= 0)
		 then
		signal sqlstate '45000' set message_text = 'overlapping Periods”';
	end if;
end $$ 

delimiter;


/* better way */


delimiter $$

create trigger check_device before insert on Wears
for each row
begin
	if exists ( 
		select start_date, end_date
		from Wears
		where Wears.snum = new.snum and Wears.manuf = new.manuf
		and not ( datediff(new.start_date, Wears.end_date) >= 0 or datediff(new.end_date, Wears.start_date) <= 0)) then
		signal sqlstate '45000' set message_text = 'overlapping Periods”';
	end if;
end $$ 

delimiter;


delimiter $$

create trigger check_device before update on Wears
for each row
begin
	if exists ( 
		select start_date, end_date
		from Wears
		where Wears.snum = new.snum and Wears.manuf = new.manuf and Wears.start_date != old.start_date and Wears.end_date != old.end_date
		and not ( datediff(new.start_date, Wears.end_date) >= 0 or datediff(new.end_date, Wears.start_date) <= 0)) then
		signal sqlstate '45000' set message_text = 'overlapping Periods”';
	end if;
end $$ 

delimiter;


