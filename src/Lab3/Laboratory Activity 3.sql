-- create database task_db
-- use task_db

create table tasks (
id int primary key auto_increment,
task_name varchar(255) not null,
task_date date not null,
status varchar(50) default 'Pending'
);