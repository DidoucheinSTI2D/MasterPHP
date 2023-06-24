<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasterPHP - Modification</title>
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


    $Nom = $infoUtilisateur['Nom'];
    $Prenom = $infoUtilisateur['Prenom'];
    $id = $infoUtilisateur['id'];



if (isset($_POST['changepn'])){
    $newp = $_POST['newp'];
    $newn = $_POST['newn'];
    $newplength = strlen($newp);
    $newnlenght = strlen($newn);

    if ( $newplength > 50 || $newplength < 3 ) $erreurnom = "Le nom doit faire entre 3 et 50 caractères.";
    if ( $newnlenght > 50 || $newnlenght < 3 ) $erreurprenom = "Le prenom doit faire entre 3 et 50 caractères.";

    if (empty($erreurprenom) && empty($erreurnom)){
        $newPrenom = htmlspecialchars($newp);
        $newNom = htmlspecialchars($newn);
        $insertPrenom = $bdd->prepare('UPDATE user SET Prenom = ? WHERE id = ?');
        $insertNom = $bdd->prepare('UPDATE user SET Nom = ? WHERE id = ?');
        $insertPrenom->execute(array($newPrenom, $_SESSION['id']));
        $insertNom->execute(array($newNom, $_SESSION['id']));
        header("Location: profil.php?id=" . $_SESSION['id']);
    }


}


?>

<body>
    <h1>Changement de Nom et Prenom </h1>

    <form method="POST">
        <div>
            <input name="newp" required="required" autocomplete="on" placeholder="Nom ...">
        </div>
        <div>
            <input name="newn" required="required" autocomplete="on" placeholder="Prenom ...">
        </div>

        <div>
            <button type="submit" name="changepn">Effectuer les changements</button>
            <br>
            <?php if (isset($erreurnom)) echo $erreurnom ?>
            <?php if (isset($erreurprenom)) echo $erreurprenom ?>
        </div>
    </form>


</body>






