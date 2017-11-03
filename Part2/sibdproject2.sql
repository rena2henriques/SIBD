
create table Patient (
	number_id int unsigned,
	name varchar(255),
	birthday DATE,
	address varchar(255),
	primary key(number_id) 
);

create table Doctor(
	number_id int unsigned,
	doctor_id int unsigned,
	primary key(doctor_id),
	foreign key(number_id) references Patient(number_id)
);

create table Device(
	serialnum int unsigned,
	manufacturer varchar(255),
	model varchar(255),
	primary key(serialnum, manufacturer)
);

create table Sensor(
	snum int unsigned,
	manuf varchar(255),
	units varchar(255),
	primary key(snum, manuf),
	foreign key(snum, manuf) references Device(serialnum, manufacturer)
);

create table Reading(
	snum int unsigned,
	manuf varchar(255),
	date_of_reading DATETIME,
	value numeric(10,2),
	primary key(snum, manuf, datetime),
	foreign key(snum, manuf) references Sensor(snum, manuf)
);

create table Period(
	start_period DATETIME,
	end_period DATETIME,
	primary key(start_period, end_period)
);

create table Wears(
	start DATETIME,
	end DATETIME,
	patient int unsigned,
	snum int unsigned,
	manuf varchar(255),
	primary key(start_period, end_period, patient),
	foreign key(start_period, end_period) references Period(start_period, end_period),
	foreign key(patient) references Patient(number_id),
	foreign key(snum, manuf) references Device(serialnum, manufacturer)
);

create table Request(
	request_number int unsigned,
	patient_id int unsigned,
	doctor_id int unsigned,
	date_of_request DATE,
	primary key(request_number),
	foreign key(patient_id) references Patient(number),
	foreign key(doctor_id) references Doctor(doctor_id)
);

create table Study(
	request_number int,
	description varchar(255),
	date_of_study DATE,
	doctor_id int unsigned,
	manufacturer varchar(255),
	serial_number int unsigned,
	primary key(request_number, description),
	foreign key(request_number) references Request(request_number),
	foreign key(doctor_id) references Doctor(doctor_id),
	foreign key(manufacturer, serial_number) references Device(serialnum, manufacturer)
);

create table Series(
	series_id int unsigned,
	name varchar(255),
	base_utl varchar(255),
	request_number int unsigned,
	description varchar(255),
	primary key(series_id),
	foreign key(request_number, description) references Study(request_number, description)
);

create table Element(
	series_id int unsigned,
	elem_index int unsigned,
	primary key(series_id, elem_index),
	foreign key(series_id) references Series(series_id)
);

create table Region(
	series_id int unsigned,
	elem_index int unsigned,
	x1 FLOAT(4, 3),
	x2 FLOAT(4, 3),
	y1 FLOAT(4, 3),
	y2 FLOAT(4, 3),
	primary key(series_id, elem_index, x1, x2, y1, y2),
	foreign key(series_id, index) references Element(series_id, elem_index)
);


insert into Patient values(1,'Ruben', '1995-02-25', 'Av. do Técnico');
insert into Patient values(6,'Francisco', '1989-12-19', 'Av. da Liberdade');
insert into Patient values(2,'André', '1912-04-15', 'Rua oliveirinha');
insert into Patient values(18012,'Mariana', '1999-01-23', 'Valverde');
insert into Patient values(27233,'Fedra', '1987-11-25', 'Cova da Moura');
insert into Patient values(9256926,'Ruben', '1991-03-30', 'Benfica');
insert into Patient values(13,'Diogo', '1996-10-20', 'Sintra');
insert into Patient values(187529,'Rita', '1987-01-25', 'Lourel');
insert into Patient values(7465,'Joaquim', '1998-09-15', 'Beja');
insert into Patient values(7549,'Miguel', '1992-01-20', 'Braga');

insert into Doctor values(6, 8246527);
insert into Doctor values(27233, 76592659);

insert into Period values('2012-02-23 11:02:00','2013-02-23 09:10:00');
insert into Period values('2013-12-21 09:10:00','2014-01-19 11:01:00');
insert into Period values('2011-11-26 10:08:00','2015-11-23 07:10:00');
insert into Period values('2009-03-11 10:11:00','2010-03-21 08:11:00');
insert into Period values('2012-02-25 12:02:00','2013-05-19 15:15:00');
insert into Period values('2015-10-03 12:30:00','2017-09-05 09:00:00');
insert into Period values('2016-09-16 08:30:00','2016-12-01 10:05:00');
insert into Period values('2001-01-30 14:00:00','2003-02-16 19:03:00');

insert into Request values(874, 2, 8246527,'2013-02-11');
insert into Request values(86351, 9256926, 76592659, '2009-10-23');
insert into Request values(126, 6, 76592659,'2013-02-11');
insert into Request values(9769, 7465, 8246527, '2009-10-23');

insert into Study values(,'', 'YYYY-MM-DD', ,'', );
insert into Study values(,'', 'YYYY-MM-DD', ,'', );
insert into Study values(,'', 'YYYY-MM-DD', ,'', );
insert into Study values(,'', 'YYYY-MM-DD', ,'', );
insert into Study values(,'', 'YYYY-MM-DD', ,'', );
insert into Study values(,'', 'YYYY-MM-DD', ,'', );
insert into Study values(,'', 'YYYY-MM-DD', ,'', );


