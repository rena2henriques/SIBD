insert into Patient values(1,'Ruben', '1995-02-25', 'Av. do Tecnico');
insert into Patient values(6,'Francisco', '1989-12-19', 'Av. da Liberdade');
insert into Patient values(2,'Andre', '1912-04-15', 'Rua oliveirinha');
insert into Patient values(18012,'Mariana', '1999-01-23', 'Valverde');
insert into Patient values(27233,'Fedra', '1987-11-25', 'Cova da Moura');
insert into Patient values(9256926,'Ruben', '1991-03-30', 'Benfica');
insert into Patient values(13,'Diogo', '1996-10-20', 'Sintra');
insert into Patient values(187529,'Rita', '1987-01-25', 'Lourel');
insert into Patient values(7465,'Joaquim', '1998-09-15', 'Beja');
insert into Patient values(7549,'Miguel', '1992-01-20', 'Braga');

/*|number(FK)|ID|*/
insert into Doctor values(6, 8246527);
insert into Doctor values(27233, 76592659);
insert into Doctor values(7465, 1996);
insert into Doctor values(9256926, 4477);
insert into Doctor values(2, 12345);
insert into Doctor values(1, 5555);

/*serial|manuf|model|*/
insert into Device values("3000",'Medtronic','GlucoseReader');
insert into Device values("3333",'Medtronic','GlucoseReader');
insert into Device values("2000",'Siemens','BloodPressureReader');
insert into Device values("4552",'Samsung','PulseMeter');
insert into Device values("1234",'LG','CholesterolMeter');
insert into Device values("3345",'LG','CholesterolMeter');
insert into Device values("20",'Siemens','X-ray1');
insert into Device values("31",'Medtronic','scanner');
insert into Device values("443",'Medtronic','BloodReader');
insert into Device values("31",'Samsung','Echo123');
insert into Device values("443",'Samsung','Echo123');


/*|snum(FK)|manuf(fkey)|units|*/
insert into Sensor values("3000",'Medtronic','glucose in mmol/L');
insert into Sensor values("3333",'Medtronic','glucose in mmol/L');
insert into Sensor values("2000",'Siemens','LDL cholesterol in mg/dL');
insert into Sensor values("4552",'Samsung','LDL cholesterol in mg/dL');
insert into Sensor values("1234",'LG','LDL cholesterol in mg/dL');
insert into Sensor values("3345",'LG','LDL cholesterol in mg/dL');


/*snum(FK)|manuf(FK)|datetime|value|*/
insert into Reading values("3000",'Medtronic','2017-02-16 19:03:00',5);
insert into Reading values("3000",'Medtronic','2017-02-17 11:03:00',6);
insert into Reading values("3000",'Medtronic','2017-02-20 11:03:00',250);
insert into Reading values("3333",'Medtronic','2002-05-11 12:01:10',10);
insert into Reading values("2000",'Siemens','2017-09-16 11:03:10',202);
insert into Reading values("2000",'Siemens','2013-02-16 11:23:01',1);
insert into Reading values("4552",'Samsung','2010-02-16 12:13:06',60);
insert into Reading values("3345",'LG','2017-11-10 18:24:00',250);
insert into Reading values("3345",'LG','2017-10-10 21:22:22',201);
insert into Reading values("3345",'LG','2017-10-11 22:23:02',100);
insert into Reading values("1234",'LG','2017-09-02 12:00:22',300);
insert into Reading values("1234",'LG','2017-10-22 12:00:22',340);
insert into Reading values("4552",'Samsung','2017-10-22 12:00:22',350);


/*Start|END*/
insert into Period values('2012-02-23 11:02:00','2013-02-23 09:10:00');
insert into Period values('2013-12-21 09:10:00','2014-01-19 11:01:00');
insert into Period values('2011-11-26 10:08:00','2015-11-23 07:10:00');
insert into Period values('2009-03-11 10:11:00','2010-03-21 08:11:00');
insert into Period values('2016-02-25 12:02:00','2017-05-19 15:15:00');
insert into Period values('2015-10-03 12:30:00','2017-09-05 09:00:00');
insert into Period values('2016-09-16 08:30:00','2016-12-01 10:05:00');
insert into Period values('2001-01-30 14:00:00','2003-02-16 19:03:00');
insert into Period values('2013-02-15 11:22:00','2999-12-31 00:00:00');	/*until now, according to project1*/ 
insert into Period values('2017-02-02 14:00:00','2999-12-31 00:00:00');	/*until now, according to project1*/
insert into Period values('2017-03-11 10:11:00','2017-09-21 08:11:00');
insert into Period values('2017-10-21 08:11:00', '2017-12-21 08:11:00');

/*|start(FK)|end(FK)|number(FK)|snum(FK)|manuf(FK)|*/
insert into Wears values('2013-02-15 11:22:00','2999-12-31 00:00:00',9256926,"2000",'Siemens');
insert into Wears values('2016-02-25 12:02:00','2017-05-19 15:15:00',6,"3000",'Medtronic');
insert into Wears values('2001-01-30 14:00:00','2003-02-16 19:03:00',2,"3333",'Medtronic');
insert into Wears values('2009-03-11 10:11:00','2010-03-21 08:11:00',7465,"4552",'Samsung');
insert into Wears values('2017-03-11 10:11:00','2017-09-21 08:11:00',27233,"1234",'LG');
insert into Wears values('2017-02-02 14:00:00','2999-12-31 00:00:00',1,"3345",'LG');
insert into Wears values('2017-10-21 08:11:00', '2017-12-21 08:11:00',27233,"4552",'Samsung');

/*Conformidade entre a request e quem segue o patient*/
/*|number|patientID(FK)|doctorID(FK)|date|*/
insert into Request values(874, 2, 12345,'2002-02-11');
insert into Request values(86351, 9256926, 4477, '2014-10-23');
insert into Request values(126, 6, 8246527,'2016-02-11');
insert into Request values(9769, 7465, 1996, '2009-10-23');
insert into Request values(111,1,5555,'2016-05-05');
insert into Request values(112,1,5555,'2017-08-05');
insert into Request values(128,6,8246527,'2016-02-23');
insert into Request values(1000,7465,1996,'2010-10-01');

/*Study dates >= request dates*/
/*Doc que receitou o exame não o pode performar*/
/*|request_numb|descprition|date|docID(FK)|manuf(FK)|serial(FK)|*/
insert into Study values(86351,'X-ray left foot','2014-10-24',8246527,'Siemens',"20");
insert into Study values(126,'MRI scan', '2016-02-15',1996,'Medtronic',"31");
insert into Study values(874,'Blood analysis','2002-03-01',1996,'Medtronic',"443");
insert into Study values(9769,'Echography right arm','2009-10-27',76592659,'Samsung',"31");
insert into Study values(1000,'Echography right arm','2010-10-27',76592659,'Samsung',"443");
insert into Study values(111,'Blood analysis', '2016-05-20',1996,'Medtronic',"443");
insert into Study values(112,'MRI scan','2017-08-22',8246527,'Medtronic','31');
insert into Study values(128,'Blood analysis','2016-02-27',76592659,'Medtronic',"443");

/*All these series must be refered at least once in the elements table*/
/*|id|name|url|request_no(FK)|descprition(FK)|*/
insert into Series values(1000,'X-ray left foot','www.clinic.com/86351xray',86351,'X-ray left foot');
insert into Series values(2000,'MRI shot','www.clinic.com/126mri',126,'MRI scan');
insert into Series values(3000,'Blood parameters','www.clinic.com/874blood',874,'Blood analysis');
insert into Series values(4000,'Right arm echography','www.clinic.com/9769echo',9769,'Echography right arm');
insert into Series values(5000,'Blood parameters','www.clinic.com/111blood',111,'Blood analysis');
insert into Series values(6000,'MRI shot','www.clinic.com/112mri',112,'MRI scan');
insert into Series values(7000,'Blood parameters','www.clinic.com/128blood',128,'Blood analysis');
insert into Series values(4001,'Right arm echography','www.clinic.com/1000echo',1000,'Echography right arm');

/*All these elements must be referenced at least once in the region table*/
/*|series_id(FK)|elem_index|*/
insert into Element values(1000,1);
insert into Element values(1000,2);
insert into Element values(1000,3);
insert into Element values(2000,1);
insert into Element values(3000,1);
insert into Element values(3000,2);
insert into Element values(4000,1);
insert into Element values(4000,2);
insert into Element values(5000,1);
insert into Element values(5000,2);
insert into Element values(6000,1);
insert into Element values(6000,2);
insert into Element values(7000,1);
insert into Element values(7000,2);	
insert into Element values(7000,3);		
insert into Element values(4001,2);

/*|series_id(FK)|elem_index(FK)|x1|y1|x2|y2|*/
insert into Region values(1000,1,0.5,0.5,0.7,0.7);
insert into Region values(1000,2,0.1,0.5,0.2,0.7);
insert into Region values(1000,3,0.5,0.6,0.7,0.7);
insert into Region values(2000,1,0.5,0.5,0.7,0.7);
insert into Region values(3000,1,0.5,0.5,0.7,0.7);
insert into Region values(3000,1,0.1,0.5,0.3,0.7);
insert into Region values(4000,1,0.5,0.1,0.7,0.3);
insert into Region values(4000,2,0.1,0.5,0.2,0.9);
insert into Region values(5000,1,0.2,0.1,0.3,0.3);
insert into Region values(5000,2,0.4,0.5,0.5,0.6);
insert into Region values(6000,1,0.2,0.5,0.5,0.7);
insert into Region values(6000,2,0.7,0.2,0.9,0.3);
insert into Region values(7000,1,0.2,0.4,0.4,0.5);
insert into Region values(7000,2,0.1,0.5,0.3,0.6);
insert into Region values(7000,3,0.1,0.4,0.4,0.6);
insert into Region values(4001,2,0.2,0.5,0.4,0.7);