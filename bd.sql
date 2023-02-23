create database if not EXISTS taster;
use taster;
DROP table if EXISTS logins;
CREATE table logins (
    id int not null AUTO_INCREMENT primary key,
    Nom varchar(255) not null UNIQUE,
    Descripcio varchar(255) not null,
    Pass varchar(255) not null,
    Avatar varchar(255) not null
);