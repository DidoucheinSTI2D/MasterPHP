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

    $Prenom = $infoUtilisateur['Prenom'];
    $id = $infoUtilisateur['id'];
    $Password = $infoUtilisateur['Password'];

    if (isset($_POST['pwchange'])){
        $PasswordChange = $_POST['Password'];
        $NewPassword = $_POST['NewPassword'];
        $Password_Validation = $_POST['Password_Validation'];
        $pwhash = password_hash($NewPassword, PASSWORD_DEFAULT);

        if (empty($PasswordChange) || empty($NewPassword) || empty($Password_Validation)) $erreur = "Merci de remplir tout les champs"; // Chrome va obliger l'utilisateur à remplir les éléments via le "required", mais certain navigateur non, c'est pour cela que j'ai ajouté cette condition :)
        if (!preg_match('/^(?=.*[A-Z])(?=.*[\W_])(?=.{8,})/', $NewPassword) ) $erreur = "Votre mot de passe doit contenir au moins 8 caractères, 1 majuscule et 1 caractère spéciale.";
        if (!password_verify($PasswordChange, $Password)) $erreur = "ancien mot de passe invalide";
        if ($NewPassword != $Password_Validation) $erreur = "La confirmation ne correspond à votre mot de passe";

        if(empty($erreur)){
            $insertbdd = $bdd->prepare("UPDATE user SET Password = ? WHERE id = ?");
            $insertbdd->execute(array($pwhash, $_SESSION['id']));
            header("Location: profil.php?id=" . $_SESSION['id']);
        }


    }



?>

<body>

    <form method="POST">
        <div>
            <h1> Modification du mot de passe de <?php echo $Prenom ?></h1>
        </div>

        <div>
            <input type="password" name="Password" required="required" autocomplete="off" placeholder="Ancien mot de passe ..."
        </div>
        <div>
            <input type="password" name="NewPassword" required="required" autocomplete="off" placeholder="Nouveau mot de passe ..."
        </div>
        <div>
            <input type="password" name="Password_Validation" required="required" autocomplete="off" placeholder="Valider le nouveau mot de passe ..."
        </div>

        <div>
            <button type="submit" name="pwchange">Valider le nouveau mot de passe</button>
            <p><?php if (isset($erreur)) echo $erreur ?></p>
        </div>

    </form>

</body>