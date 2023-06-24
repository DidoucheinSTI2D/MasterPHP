<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasterPHP - Backoffice</title>
</head>

<?php
require "../structure/config.php";

session_start();
require "../structure/config.php";

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

    $sqlget = "SELECT Prenom, Nom, Email, role, id FROM user";
    $result = $bdd->query($sqlget);

?>


<body>
    <div>
        <h1> Backoffice - Gestion des utilisateurs </h1>
    </div>
    <div>
        <table>
            <tr>
                <th> Prenom </th>
                <th> Nom </th>
                <th> Email </th>
                <th> role </th>
            </tr>
            <?php
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                echo '<tr>';
                echo '<td>' . $row['Prenom'] . '</td>';
                echo '<td>' . $row['Nom'] . '</td>';
                echo '<td>' . $row['Email'] . '</td>';
                echo '<td>' . $row['role'] . '</td>';
                echo '<td><a href="modify.php?id=' . $row['id'] . '">Modifier informations</a></td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
    <a href="<?php echo $profil ?>"> Voir votre profil</a>


</body>