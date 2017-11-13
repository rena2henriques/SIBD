
/* Write	 a	 function,	 region_overlaps_element(),	 that,	 given	 the	 (series_id,	 index)	 of	 an	
Element	A,	and	the	coordinates	(x1,	y1,	x2,	y2)	of	a	Region	B,	returns	true	if	any	region	
of	the	element	A	overlaps	with	Region	B,	and	false	otherwise.	*/

delimiter$$

drop function if exists checkCoordinates;

create function checkCoordinates(Ax1 FLOAT(4, 3), Ax2 FLOAT(4, 3), Ay1 FLOAT(4,3), Ay2 FLOAT(4,3), Bx1 FLOAT(4, 3), Bx2 FLOAT(4, 3), By1 FLOAT(4,3), By2 FLOAT(4,3))
returns BOOLEAN
begin
	declare Aux FLOAT(4,3);

	-- Conditions used to garantee that x1 < x2 and y1 < y2
	if Ax1 > Ax2 then set Aux = Ax1; set Ax1 = Ax2; set Ax2 = Aux; end if;

	if Bx1 > Bx2 then set Aux = Bx1; set Bx1 = Bx2; set Bx2 = Aux; end if;

	if By1 > By2 then set Aux = By1; set By1 = By2; set By2 = Aux; end if;

	if Ay1 > Ay2 then set Aux = Ay1; set Ay1 = Ay2; set Ay2 = Aux; end if;

	/* checking if the coordinates overlap */
	/* we check the cases that they don't overlap instead of the ones they do, because there are less conditions*/
	if Bx1 >= Ax2 or Bx2 <= Ax1 or By1 >= Ay2 or By2 <= Ay1 then
		return FALSE; -- the regions don't overlap
	else
		return TRUE; -- the regions overlap
	end if;
end $$


drop function if exists region_overlaps_element;

create function region_overlaps_element(idA int unsigned, indexA int unsigned, x1B FLOAT(4, 3), y1B FLOAT(4, 3), x2B FLOAT(4, 3), y2B FLOAT(4, 3))
returns BOOLEAN
begin
	-- returns true if there is overlapping or false if there isn't
	return exists (select r.series_id, r.elem_index 
		from Region as r 
		where r.series_id = idA and r.elem_index = indexA and checkCoordinates(r.x1, r.x2, r.y1, r.y2, x1B, x2B, y1B, y2B));
	-- checkCoordinates is a functions that tests if x1 < x2 and y1 < y2 and if the coordinates overlap
end$$
	
delimiter;	



/*Para construir a função de teste em relação à sobreposição de regiões considerou-se que as coordenadas do ponto 1 (x1, y1) não têm que 
ser necessáriamente inferiores às coordenadas do ponto 2 (x2, y2). Deste modo, foi necessário executar este teste e caso alguma da coordenada
do ponto 2 seja inferior à do ponto 1, então troca as suas posições e só depois é que se realiza o teste de sobreposição.

Como há muitas maneiras de duas regiões estarem sobrepostas, para simplifiar o código, decidiu-se averiguar apenas os casos em que as
regiões não estão sobrepostas. Caso as regiões a testar não estejam nos quatro casos de não sobreposição, então implica que têm que ser
sobrepostas.*/
