<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasterPHP - S'inscrire</title>
</head>
<?php
 require "./structure/config.php";

 if (isset($_POST['registerform'])){

     if(
         empty($_POST['Prenom'])||
         empty($_POST['Nom'])||
         empty($_POST['Email'])||
         empty($_POST['Password'])||
         empty($_POST['password_validation'])
     )die();

     $Prenom = $_POST['Prenom'];
     $Nom = $_POST['Nom'];
     $Password = $_POST['Password'];
     $Email = $_POST['Email'];
     $password_validation = $_POST['password_validation'];

     $pwlength = strlen($Password);
     $nomlenght = strlen($Nom);
     $prenomlenght = strlen($Prenom);

     $pwhash = password_hash($Password, PASSWORD_DEFAULT);

    if ($prenomlenght > 50 || $prenomlenght < 3) $erreurprenom = "Votre prénom ne doit pas faire moins de 3 caractères ou plus de 50 caractères.";
    if ($nomlenght > 50 || $nomlenght < 3) $erreurnom = "Votre nom ne doit pas faire moins de moins de 3 caractères ou plus de 50 caractères";
    if (!preg_match('/^(?=.*[A-Z])(?=.*[\W_])(?=.{8,})/', $Password) ) $erreurpassword = "Votre mot de passe doit contenir au moins 8 caractères, 1 majuscule et 1 caractère spéciale.";
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) $erreurmail = "Votre email n'est pas bon.";
    if ($Password != $password_validation ) $erreurconfirmation = "Les 2 mots de passes indiqués ne correspondent pas.";

    if (empty($erreurprenom) && empty($erreurnom) && empty($erreurmail) && empty($erreurpassword) && empty($erreurconfirmation) ){
        $insertbdd = $bdd->prepare("INSERT INTO user(Prenom,Nom,Email,Password,role) VALUES(?, ?, ?, ?, ?)");
        $insertbdd->execute(array($Prenom, $Nom, $Email, $pwhash,'user'));

        header("Location: login.php");
        exit();
    }





 }
?>
<body>
    <h1>Formulaire d'inscription</h1>
    <div>
        <form method="POST">
            <div>
                <input type="text" name="Prenom" placeholder="Prenom ..." required="required" autocomplete="on">
                <p><?php if (isset($erreurprenom)) echo $erreurprenom ?></p>
            </div>
            <div>
                <input type="text" name="Nom" placeholder="Nom ..." required="required" autocomplete="on">
                <p><?php if (isset($erreurnom)) echo $erreurnom ?></p>
            </div>
            <div>
                <input type="email" name="Email" placeholder="votre@mail.com" required="required" autocomplete="on">
                <p><?php if (isset($erreurmail)) echo $erreurmail ?></p>
            </div>
            <div>
                <input type="password" name="Password" placeholder="mot de passe" required="required" autocomplete="off">
                <p><?php if (isset($erreurpassword)) echo $erreurpassword ?></p>
            </div>
            <div>
                <input type="password" name="password_validation" placeholder="veuillez confirmez votre mot de passe" required="required" autocomplete="off">
                <p><?php if (isset($erreurconfirmation)) echo $erreurconfirmation ?></p>
            </div>

            <button type="submit" name="registerform"> Valider votre inscription </button>
            <p> Déjà Inscrit?</p>
            <a type="button" href="login.php"> Se Connecter </a>
        </form>
    </div>
</body>
</html>