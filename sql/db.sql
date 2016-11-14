drop database if exists cwinkebt_Messenger;
create database cwinkebt_Messenger;

use cwinkebt_Messenger;
create table person (
	person_id int auto_increment primary key,
  fname varchar(50) not null,
  lname varchar(50) not null,
  birthday date not null
);

create table groups (
	groups_id int auto_increment primary key,
	name varchar(50) not null UNIQUE,
	date_added DATETIME not null
);

create table poeple_group (
	poeple_group_id int auto_increment primary key,
	groups_id int not null,
	person_id int not null,
	message varchar(1000),
	posted DATETIME,

	constraint fk_people_group_person foreign key (person_id) references person(person_id),
	constraint fk_people_group_group foreign key (groups_id) references groups(groups_id)
);

create table adims (
	adim_id int auto_increment primary key,
	person_id int not null,
	adim_key varchar(15) not null,

	constraint fk_adim_person foreign key (person_id) references person(person_id)
);

create table feedback (
	feedback_id int auto_increment primary key,
	message varchar(1000) NOT NULL,
  rating int,
	placed DATETIME not null,
  person_id int,
  groups_id int,

	CONSTRAINT fk_feedback_group FOREIGN KEY (groups_id) REFERENCES groups (groups_id),
	CONSTRAINT fk_feedback_person FOREIGN KEY (person_id) REFERENCES person (person_id)
);
