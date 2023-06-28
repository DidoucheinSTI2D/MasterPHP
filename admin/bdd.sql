# TODO: DROP DATABASE; pour relancer le script
# TODO: pas besoin de "`"
CREATE DATABASE `PHPMASTER`;
USE `PHPMASTER`;

# TODO: "id", "Prenom" soit l'un soit l'autre
CREATE TABLE `user`(
    # TODO: INT(11) ??
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    # TODO opt: prénom et nom un peu long non?
    `Prenom` varchar(50),
    `Nom` varchar(50),
    `Email` varchar(250),
    `Password` varchar(250),
    # TODO: imagine tu gère des milliers de roles, 5 caractères suffisent ?
    `role` varchar(5)
)