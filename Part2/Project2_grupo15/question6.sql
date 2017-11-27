delimiter $$

drop function if exists checkCoordinates;

create function checkCoordinates(Ax1 FLOAT(4, 3), Ax2 FLOAT(4, 3), Ay1 FLOAT(4,3), Ay2
FLOAT(4,3), Bx1 FLOAT(4, 3), Bx2 FLOAT(4, 3), By1 FLOAT(4,3), By2 FLOAT(4,3))
returns BOOLEAN

begin
	declare Aux FLOAT(4,3);
-- Conditions used to guarantee that x1 < x2 and y1 < y2
	if Ax1 > Ax2 then set Aux = Ax1; set Ax1 = Ax2; set Ax2 = Aux; end if;
	if Bx1 > Bx2 then set Aux = Bx1; set Bx1 = Bx2; set Bx2 = Aux; end if;
	if By1 > By2 then set Aux = By1; set By1 = By2; set By2 = Aux; end if;
	if Ay1 > Ay2 then set Aux = Ay1; set Ay1 = Ay2; set Ay2 = Aux; end if;
/* checking if the coordinates overlap */
/* we check the cases that they don’t overlap instead of the ones they do, because there are
less conditions. */
/*returns false if they don’t overlap and true otherwise*/
	if Bx1 >= Ax2 or Bx2 <= Ax1 or By1 >= Ay2 or By2 <= Ay1 then
		return FALSE;
	else
	return TRUE;
	end if;
end $$


drop function if exists region_overlaps_element;

create function region_overlaps_element(idA int unsigned, indexA int unsigned, x1B FLOAT(4,
3), y1B FLOAT(4, 3), x2B FLOAT(4, 3), y2B FLOAT(4, 3))
returns BOOLEAN

begin
-- returns true if there is overlapping or false if there isn’t
	return exists (select r.series_id, r.elem_index
		from Region as r
		where r.series_id = idA and r.elem_index = indexA and checkCoordinates(r.x1, r.x2, r.y1,
		r.y2, x1B, x2B, y1B, y2B));-- checkCoordinates is a functions that tests if x1 < x2 and y1 < y2 and if the coordinates overlaps
end$$
delimiter ;

-- some testes
--> overlap
select region_overlaps_element(4001,2,0.1,0.5,0.6,0.7);
select region_overlaps_element(4001,2,0.6,0.5,0.1,0.7);
--> dont overlap
select region_overlaps_element(4001,2,0.5,0.5,0.7,0.7);
select region_overlaps_element(4001,2,0.4,0.1,0.2,0.3);
