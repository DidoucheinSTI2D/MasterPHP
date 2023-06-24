CREATE DATABASE `PHPMASTER`;
USE `PHPMASTER`;

CREATE TABLE `user`(
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `Prenom` varchar(50),
    `Nom` varchar(50),
    `Email` varchar(250),
    `Password` varchar(250),
    `role` varchar(5)
)