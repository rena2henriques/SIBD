delimiter $$

drop trigger if exists check_doctor_insert;

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


drop trigger if exists check_doctor_update;

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