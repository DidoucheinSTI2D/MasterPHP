<?php
session_start();
require './structure/config.php';

// TODO: pourquoi intval ???
if (!empty($_GET['id'])) $getId = intval($_GET['id']);

if ($getId != $_SESSION['id']) header("Location: login.php");



$connect = $bdd->prepare('SELECT * FROM user WHERE id = :id');
$connect->bindValue('id', $getId, PDO::PARAM_INT);
$resultat = $connect->execute();
$infoUtilisateur = $connect->fetch();


$Email = $infoUtilisateur['Email'];
$Nom = $infoUtilisateur['Nom'];
$Prenom = $infoUtilisateur['Prenom'];
$id = $infoUtilisateur['id'];
$role = $infoUtilisateur['role'];

$changepn = "pnchange.php?id=" . $id;
$pwchange = "pwchange.php?id=" . $id;
$backoffice = "./admin/backoffice.php?id=" . $id;

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasterPHP - Profil</title>
</head>

<body>
    <h1>Bienvenue sur votre espace </h1>

    <div>
        <h2 style="color: red;"><?php
        if (isset($_GET['error']) && $_GET['error'] === "user"){
            echo "Vous n'êtes pas autorisé à accéder à cette page.";
        }
        ?></h2>
        <h3> Prenom : <?php echo $Prenom?></h3>
        <h3> Nom : <?php echo $Nom?></h3> <p>
        <h3> Email : <?php echo $Email?> </h3>
        <p><a href="<?php echo $changepn ?>">Modifier votre nom et prenom</a></p>
        <p><a href="<?php echo $pwchange ?>">Modifier votre mot de passe</a></p>
    </div>

    <div>
        <button><a href="logout.php">Se déconnecter</a></button>
    </div>

    <?php
        if ($role == 'admin'){
            ?> <a href="<?php echo $backoffice ?>" > accéder au backoffice </a>
    <?php
        }
    ?>


</body>






