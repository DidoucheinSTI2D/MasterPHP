<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=PHPMASTER', 'root');
}
catch(Exception $e)
{
    die('Erreur PDO :' . $e->getMessage());
}
?>