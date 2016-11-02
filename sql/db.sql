drop database if exists cwinkebt_Message;
create database cwinkebt_Message;

use cwinkebt_Message;
create table person (
	person_id int auto_increment primary key,
  fname varchar(50) not null,
  lname varchar(50) not null,
  birthday date not null
);
