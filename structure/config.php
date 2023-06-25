<?php
try {
    $bdd = new PDO('mysql:host=db;dbname=PHPMASTER', 'root', 'toor');
}
catch(Exception $e)
{
    die('Erreur PDO :' . $e->getMessage());
}
?>