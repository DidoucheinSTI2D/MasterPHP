<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasterPHP - Se connecter</title>
</head>
<?php
    // TODO fix: envoie des headers après le html fail ici
    //  php est un langage compilé donc tout header doit être envoyé
    //  soit au début soit à la sortie (c'est peut être pas clair)
    session_start();

    // TODO enhance: require_once au lieu de require
    require "./structure/config.php";

    // TODO refactor: variables en camelCase et en anglais
    if (isset($_POST['connexion'])) {
        $Email = $_POST['Email'];
        $Password = $_POST['Password'];

        $connect = $bdd->prepare('SELECT * FROM user WHERE Email = :Email');
        // TODO enhance: voir ex n°1 : https://www.php.net/manual/en/pdo.prepare.php#refsect1-pdo.prepare-examples
        $connect->bindValue('Email', $Email, PDO::PARAM_STR);
        $resultat = $connect->execute();
        $resultatMembre = $connect->fetch();

        // TODO fix: Il peut y avoir plus d'une erreur mais la variable d'erreur est override
        if (empty($Email) || empty($Password)) $erreur = "Merci de remplir tout les champs";
        if (empty($resultatMembre)) $erreur = "Vous n'êtes pas inscrit";

        // TODO refactor: norme très hétérogène, la structure des "if" doivent se ressembler
        if (isset($resultatMembre['Password'])){
            $pwcrypt = $resultatMembre['Password'];
            if (!password_verify($Password, $pwcrypt)) $erreur = "Mot de passe invalide";
        }

        // TODO refactor: Gérer la session en multi-tableau
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