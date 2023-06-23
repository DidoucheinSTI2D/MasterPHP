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

    $changepn = "pnchange.php?id=" . $id;
    $pwchange = "pwchange.php?id=" . $id;

?>

<body>
    <h1>Bienvenue sur votre espace </h1>

    <div>
        <h3> Prenom : <?php echo $Prenom?></h3>
        <h3> Nom : <?php echo $Nom?></h3> <p>
        <h3> Email : <?php echo $Email?> </h3>
        <p><a href="<?php echo $changepn ?>">Modifier votre nom et prenom</a></p>
        <p><a href="<?php echo $pwchange ?>">Modifier votre mot de passe</a></p>
    </div>

    <div>
        <button><a href="logout.php">Se déconnecter</a></button>
    </div>


</body>






