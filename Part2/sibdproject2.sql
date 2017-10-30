
create table Patient (
	number int unsigned,
	name varchar(255),
	birthday DATE,
	address varchar(255),
	primary key(number) 
);

create table Doctor(
	number int unsigned,
	doctor_id int unsigned,
	primary key(doctor_id),
	foreign key(number) references Patient(number)
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
	datetime DATETIME,
	value numeric(10,2),
	primary key(snum, manuf, datetime),
	foreign key(snum, manuf) references Sensor(snum, manuf)
);

create table Period(
	start DATETIME,
	end DATETIME,
	primary key(start, end)
);

create table Wears(
	start DATETIME,
	end DATETIME,
	patient int,
	snum int unsigned,
	manuf int unsigned,
	primary key(start, end, patient),
	foreign key(start, end) references Period(start, end),
	foreign key(patient) references Patient(number),
	foreign key(snum, manuf) references Device(snum, manuf)
);

create table Request(
	number int,
	patient_id int,
	doctor_id int,
	date DATE,
	primary key(number),
	foreign key(patient_id) references Patient(number),
	foreign key(doctor_id) references Doctor(doctor_id)
);

create table Study(
	request_number int,
	description varchar(255),
	date DATE,
	doctor_id int unsigned,
	manufacturer varchar(255),
	serial_number int unsigned,
	primary key(request_number, description),
	foreign key(request_number) references Request(number),
	foreign key(doctor_id) references Doctor(doctor_id),
	foreign key(manufacturer, serial_number) references Device(serialnum, manufacturer)
);

create table Series(
	series_id int,
	name varchar(255),
	base_utl varchar(255),
	request_number int,
	description varchar(255),
	primary key(series_id),
	foreign key(request_number, description) references Study(request_number, description)
);

create table Element(
	series_id int,
	elem_index int,
	primary key(series_id, elem_index),
	foreign key(series_id) references Series(series_id)
);

create table Region(
	series_id int,
	elem_index int,
	x1 int,
	x2 int,
	y1 int,
	y2 int,
	primary key(x1, x2, y1, y2),
	foreign key(series_id, index) references Element(series_id, elem_index)
);

