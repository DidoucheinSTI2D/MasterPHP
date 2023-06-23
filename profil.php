<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasterPHP - Profil</title>
</head>
<?php
session_start();
require './structure/config.php';


if (!empty($_GET['id'])) $getId = intval($_GET['id']);

if ($getId != $_SESSION['id']) header("Location: index.php");

    $connect = $bdd->prepare('SELECT * FROM user WHERE id = :id');
    $connect->bindValue('id', $getId, PDO::PARAM_INT);
    $resultat = $connect->execute();
    $infoUtilisateur = $connect->fetch();


    $Email = $infoUtilisateur['Email'];
    $Nom = $infoUtilisateur['Nom'];
    $Prenom = $infoUtilisateur['Prenom'];
    $id = $infoUtilisateur['id'];


?>

<body>
    <h1>Bienvenue sur votre espace </h1>

    <div>
        <p> Prenom : <?php echo $Prenom?></p>
        <p> Nom : <?php echo $Nom?></p>
        <p> Email : <?php echo $Email?> </p>
    </div>


</body>






