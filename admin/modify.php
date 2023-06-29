<?php
require "../structure/config.php";

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php?error=notconnected");
    exit;
}

$getId = $_SESSION['id'];

$connect = $bdd->prepare('SELECT * FROM user WHERE id = :id');
$connect->bindValue('id', $getId, PDO::PARAM_INT);
$resultat = $connect->execute();
$infoUtilisateur = $connect->fetch();

$id = $infoUtilisateur['id'];
$role = $infoUtilisateur['role'];

$profil = "../profil.php?id=" . $id;

if ($role != 'admin') {
    header("Location: ../profil.php?id=" . $getId . "&error=user");
    exit;
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $checkUser = $bdd->prepare('SELECT * FROM user WHERE id = :id');
    $checkUser->bindValue('id', $userId, PDO::PARAM_INT);
    $checkUser->execute();

    if ($checkUser->rowCount() == 1) {
        $getUser = $checkUser->fetch();
        $prenom = $getUser['Prenom'];
        $nom = $getUser['Nom'];
        $email = $getUser['Email'];
        $role = $getUser['role'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPrenom = $_POST['prenom'];
            $newNom = $_POST['nom'];
            $newEmail = $_POST['email'];
            $newRole = $_POST['role'];
            $newPassword = $_POST['password'];
            $passwordValidation = $_POST['password_validation'];

            $erreurPrenom = '';
            $erreurNom = '';
            $erreurPassword = '';
            $erreurEmail = '';
            $erreurConfirmation = '';

            $prenomLength = strlen($newPrenom);
            $nomLength = strlen($newNom);

            if ($prenomLength > 50 || $prenomLength < 3) {
                $erreurPrenom = "Votre prénom ne doit pas faire moins de 3 caractères ou plus de 50 caractères.";
            }

            if ($nomLength > 50 || $nomLength < 3) {
                $erreurNom = "Votre nom ne doit pas faire moins de 3 caractères ou plus de 50 caractères.";
            }

            if (!preg_match('/^(?=.*[A-Z])(?=.*[\W_])(?=.{8,})/', $newPassword)) {
                $erreurPassword = "Votre mot de passe doit contenir au moins 8 caractères, 1 majuscule et 1 caractère spécial.";
            }

            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                $erreurEmail = "Votre email n'est pas valide.";
            }

            if ($newPassword != $passwordValidation) {
                $erreurConfirmation = "Les 2 mots de passe indiqués ne correspondent pas.";
            }

            if (empty($erreurPrenom) && empty($erreurNom) && empty($erreurPassword) && empty($erreurEmail) && empty($erreurConfirmation)) {
                if (!empty($newPassword)) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateUser = $bdd->prepare('UPDATE user SET Prenom = :prenom, Nom = :nom, Email = :email, role = :role, Password = :password WHERE id = :id');
                    $updateUser->bindValue('prenom', $newPrenom, PDO::PARAM_STR);
                    $updateUser->bindValue('nom', $newNom, PDO::PARAM_STR);
                    $updateUser->bindValue('email', $newEmail, PDO::PARAM_STR);
                    $updateUser->bindValue('role', $newRole, PDO::PARAM_STR);
                    $updateUser->bindValue('password', $hashedPassword, PDO::PARAM_STR);
                    $updateUser->bindValue('id', $userId, PDO::PARAM_INT);
                    $updateUser->execute();
                } else {
                    $updateUser = $bdd->prepare('UPDATE user SET Prenom = :prenom, Nom = :nom, Email = :email, role = :role WHERE id = :id');
                    $updateUser->bindValue('prenom', $newPrenom, PDO::PARAM_STR);
                    $updateUser->bindValue('nom', $newNom, PDO::PARAM_STR);
                    $updateUser->bindValue('email', $newEmail, PDO::PARAM_STR);
                    $updateUser->bindValue('role', $newRole, PDO::PARAM_STR);
                    $updateUser->bindValue('id', $userId, PDO::PARAM_INT);
                    $updateUser->execute();
                }

                header("Location: backoffice.php");
                exit;
            }
        }

        echo '<form method="post" action="modify.php?id=' . $userId . '">';
        echo '<input type="hidden" name="id" value="' . $userId . '">';
        echo 'Prénom: <input type="text" name="prenom" value="' . $prenom . '"><br>';
        if (!empty($erreurPrenom)) {
            echo '<p style="color: red;">' . $erreurPrenom . '</p>';
        }
        echo 'Nom: <input type="text" name="nom" value="' . $nom . '"><br>';
        if (!empty($erreurNom)) {
            echo '<p style="color: red;">' . $erreurNom . '</p>';
        }
        echo 'Email: <input type="email" name="email" value="' . $email . '"><br>';
        if (!empty($erreurEmail)) {
            echo '<p style="color: red;">' . $erreurEmail . '</p>';
        }
        echo 'Rôle: <input type="text" name="role" value="' . $role . '"><br>';
        echo 'Nouveau mot de passe: <input type="password" name="password"><br>';
        if (!empty($erreurPassword)) {
            echo '<p style="color: red;">' . $erreurPassword . '</p>';
        }
        echo 'Confirmation du mot de passe: <input type="password" name="password_validation"><br>';
        if (!empty($erreurConfirmation)) {
            echo '<p style="color: red;">' . $erreurConfirmation . '</p>';
        }
        echo '<input type="submit" value="Modifier">';
        echo '</form>';
    } else {
        header("Location: backoffice.php?error=notfound");
        exit;
    }
} else {
    header("Location: backoffice.php?error=noid");
    exit;
}
?>
