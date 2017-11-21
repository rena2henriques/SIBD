drop table if exists Region;
drop table if exists Element;
drop table if exists Series;
drop table if exists Study;
drop table if exists Request;
drop table if exists Wears;
drop table if exists Period;
drop table if exists Reading;
drop table if exists Sensor;
drop table if exists Device;
drop table if exists Doctor;
drop table if exists Patient;

create table Patient (
	number int unsigned,
	name varchar(255),
	birthday DATE,
	address varchar(255),
	primary key(number) );

create table Doctor(
	number int unsigned,
	doctor_id int unsigned,
	primary key(doctor_id),
	foreign key(number) references Patient(number));

create table Device(
	serialnum  varchar(255),
	manufacturer varchar(255),
	model varchar(255),
	primary key(serialnum, manufacturer));

create table Sensor(
	snum varchar(255),
	manuf varchar(255),
	units varchar(255),
	primary key(snum, manuf),
	foreign key(snum, manuf) references Device(serialnum, manufacturer));

create table Reading(
	snum varchar(255),
	manuf varchar(255),
	datetime DATETIME,
	value numeric(10,2),
	primary key(snum, manuf, datetime),
	foreign key(snum, manuf) references Sensor(snum, manuf));

create table Period(
	start DATETIME,
	end DATETIME,
	primary key(start, end));

create table Wears(
	start DATETIME,
	end DATETIME,
	patient int unsigned,
	snum varchar(255),
	manuf varchar(255),
	primary key(start, end, patient),
	foreign key(start, end) references Period(start, end),
	foreign key(patient) references Patient(number),
	foreign key(snum, manuf) references Device(serialnum, manufacturer));

create table Request(
	number int unsigned,
	patient_id int unsigned,
	doctor_id int unsigned,
	date DATE,
	primary key(number),	
	foreign key(patient_id) references Patient(number),
	foreign key(doctor_id) references Doctor(doctor_id));


create table Study(
	request_number int unsigned,
	description varchar(255),
	date DATE,
	doctor_id int unsigned,
	manufacturer varchar(255),
	serial_number varchar(255),
	primary key(request_number, description),
	foreign key(request_number) references Request(number),
	foreign key(doctor_id) references Doctor(doctor_id),
	foreign key(serial_number, manufacturer) references Device(serialnum, manufacturer));

create table Series(
	series_id int unsigned,
	name varchar(255),
	base_url varchar(255),
	request_number int unsigned,
	description varchar(255),
	primary key(series_id),
	foreign key(request_number, description) references Study(request_number, description));

create table Element(
	series_id int unsigned,
	elem_index int unsigned,
	primary key(series_id, elem_index),
	foreign key(series_id) references Series(series_id));

create table Region(
	series_id int unsigned,
	elem_index int unsigned,
	x1 FLOAT(4, 3),
	y1 FLOAT(4, 3),
	x2 FLOAT(4, 3),
	y2 FLOAT(4, 3),
	primary key(series_id, elem_index, x1, x2, y1, y2),
	foreign key(series_id, elem_index) references Element(series_id, elem_index));