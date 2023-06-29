<?php
session_start();


require "./structure/config.php";

if (isset($_POST['connexion'])) {
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];

    $connect = $bdd->prepare('SELECT * FROM user WHERE Email = :Email');
    $connect->bindValue('Email', $Email, PDO::PARAM_STR);
    $resultat = $connect->execute();
    $resultatMembre = $connect->fetch();

    if (empty($Email) || empty($Password)) $erreur = "Merci de remplir tout les champs";
    if (empty($resultatMembre)) $erreur = "Vous n'êtes pas inscrit";

    if (isset($resultatMembre['Password'])){
        $pwcrypt = $resultatMembre['Password'];
        if (!password_verify($Password, $pwcrypt)) $erreur = "Mot de passe invalide";
    }

    if (empty($erreur)){
        $_SESSION['id'] = $resultatMembre['id'];
        $_SESSION['Prenom'] = $resultatMembre['Prenom'];
        $_SESSION['Nom'] = $resultatMembre['Nom'];
        $_SESSION['role'] = $resultatMembre['role'];
        $_SESSION['Email'] = $resultatMembre['Email'];
        if ($_SESSION['role'] == 'user'){
            header("Location: profil.php?id=" .  $_SESSION['id']);
        } elseif ($_SESSION['role'] == 'admin'){
            header("Location: ./admin/backoffice.php?id=" . $_SESSION['id']);
        }

    }



}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasterPHP - Se connecter</title>
</head>


<body>
    <div>
        <h2 style="color: red;">
            <?php
            if (isset($_GET['error']) && $_GET['error'] === "notconnected") {
                echo "Veuillez vous connecter avant d'essayer d'accéder à cette page.";
            }
            ?>
        </h2>
        <form method="POST">
            <h1> Se connecter </h1>
            <div>
                <input type="text" name="Email" required="required" autocomplete="on" placeholder="votre@email.com" value="<?php echo isset($_POST['Email']) ? $_POST['Email'] : ''; ?>">
            </div>
            <div>
                <input type="password" name="Password" required="required" autocomplete="off" placeholder="votre mot de passe">
            </div>
            <button type="submit" name="connexion">Se Connecter</button>
            <p style="color: red;"> <?php if (isset($erreur))  echo "$erreur" ?></p>
        </form>
    </div>
</body>

</html>