
/* Write	 a	 function,	 region_overlaps_element(),	 that,	 given	 the	 (series_id,	 index)	 of	 an	
Element	A,	and	the	coordinates	(x1,	y1,	x2,	y2)	of	a	Region	B,	returns	true	if	any	region	
of	the	element	A	overlaps	with	Region	B,	and	false	otherwise.	*/

create function region_overlaps_element(series_id varchar(255), index int unsigned, x1 FLOAT(4, 3), x2 FLOAT(4, 3), y1 FLOAT(4, 3), y2 FLOAT(4, 3))
returns BOOLEAN
begin
	if exists (select r.series_id, r.elem_index 
		from Region as r 
		/* considerando que x1<x2 e y1 < y2 */
		where r.series_id = series_id and r.elem_index = index and not (x1 < r1.x2 or y1 > r1.y2 or x2 < r.x1 or y2 > r.y1)) then
		return TRUE;
 	else
 		return FALSE;
 	end if;
end	