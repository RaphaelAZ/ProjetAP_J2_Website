<?php
    session_start();
    if($_SESSION["Mat"]==null||$_SESSION["redirection"]=="gestionnaire"){
        header("location:../index.php");
        session_destroy();
    }
    if (isset($_GET['page']) && !empty($_GET['page'])) { /* OBTENTION DE LA PAGE ACTUEL */
        $currentPage = (int)strip_tags($_GET['page']);
    } else {
        $currentPage = 1;
    }

    $db = mysqli_connect("127.0.0.1", "root", "", "ap2"); /* CONNEXION A LA BASE DE DONNEES */
    mysqli_set_charset($db, 'utf8'); /* ENCODAGE DES DONNEES DE LA BDD VERS LE SITE EN UTF-8 */
    $sql = 'SELECT COUNT(*) AS nb_lignes FROM intervention;';

    $requete = mysqli_query($db, $sql);
    $resultat = mysqli_fetch_assoc($requete);

    $nbListe = (int)$resultat['nb_lignes'];

    $parPage = 10;
    $pages = ceil($nbListe / $parPage); /* CALCUL DU NOMBRE DE PAGES AFFICHABLES */
    $premier = ($currentPage * $parPage) - $parPage;

    $sql = "SELECT * FROM intervention,technicien WHERE intervention.Matricule=technicien.Matricule LIMIT $premier, $parPage;"; /* SELECTION DES DONNEES A AFFICHER SUR LA PAGE */
    $liste = mysqli_query($db, $sql);

    if (isset($_POST['dateP'])){
        /* BOUTON DE TRIAGE PAR DATE D'INTERVENTION */
        $sqldate = "SELECT * FROM intervention,technicien WHERE intervention.Matricule=technicien.Matricule ORDER BY Date_Visite LIMIT $premier, $parPage;";
        $liste = mysqli_query($db, $sqldate);
    }

    if (isset($_POST['agent'])){
        /* BOUTON DE TRIAGE PAR MATRICULE D'AGENT */
        $sqlagent = "SELECT * FROM intervention,technicien WHERE intervention.Matricule=technicien.Matricule ORDER BY intervention.Matricule LIMIT $premier, $parPage;";
        $liste=mysqli_query($db,$sqlagent);
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../img/logo.png"/>
    <title>CASHCASH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <div class="col-12 align" style="color:#FFFFFF;padding-top: 20px;padding-bottom: 100px;">
            <h1 class="login100-form-title p-b-26 text-light mb-4" style="padding-bottom: 20px;">
                Liste d'Interventions
            </h1>
            <table>
                <tr>
                    <td>
                        <a href="../controleur/logout.php">
                            <button class="btn btn-success" type="button">
                                Se déconnecter <!-- DECONNEXION RENVOI VERS LA PAGE DE DECONNEXION -->
                            </button>
                        </a>
                    </td>
                    <td>
                        <form action="" method="post" align="center">
                            <input class="btn btn-success" type="submit" name="dateP" id="dateP" value="Trier par Date"/>
                        </form>
                    </td>
                    <td>
                        <form action="" method="post" align="center">
                            <input class="btn btn-success" type="submit" name="agent" id="agent" value="Trier par Agent"/>
                        </form>
                    </td>
                    <td>
                        <form action="mesinters.php" method="post" align="center">
                            <input class="btn btn-success" type="submit" name="mesinters" id="mesinters" value="Mes Interventions"/>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<div class="col-12 col-md-10 offset-0 offset-md-1">
    <table class="table table-borderless table-responsive pt-5" align="center" border="0" cellspacing="0" cellpadding="0">

        <tr style="background-color: #565e64;color: #ffffff;font-size: 1.5em;"> <!-- INTITULE DES COLONNES -->
            <td align="center">
                <center>
                    Numéro
                </center>
            </td>
            <td align="center">
                <center>
                    Date Visite
                </center>
            </td>
            <td align="center">
                <center>
                    Heure Visite
                </center>
            </td>
            <td align="center">
                <center>
                    N° Client
                </center>
            </td>
            <td>
                <center>
                    Etat
                </center>
            </td>
            <td align="center">
                <center>
                    Matricule
                </center>
            </td>
            <td colspan="2" align="center">
                <center>
                    Employé
                </center>
            </td>
        </tr>


        <tr> <!-- LIGNE DE SEPARATION -->
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>

        <?php // AFFICHAGE DES DONNEES VIA REQUETES PUIS FOREACH QUI INSERE DANS LES LIGNES D'UN TABLEAU
        foreach($liste as $ligne){
            ?>
            <tr align="center" style="font-size: 1.2em;color: #a3a1a4">
                <td><?= $ligne['Numero_Intervention'] ?></td>
                <td><?= $ligne['Date_Visite'] ?></td>
                <td><?= $ligne['Heure_Visite'] ?></td>
                <td><?= $ligne['Numero_Client'] ?></td>
                <td><?= $ligne['Etat'] ?></td>
                <td><?= $ligne['Matricule'] ?></td>
                <td><?= $ligne['Nom_Employe'] ?></td>
                <td><?= $ligne['Prenom_Employe'] ?></td>
            </tr>
            <?php
        }
        ?>


        <tr> <!-- LIGNE DE SEPARATION -->
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>


        <table class="align"> <!-- TABLEAU DE NAVIGATION DES DONNÉES AFFICHÉES -->
            <tr style="alignment: center">
                <ul>
                    <td>
                        <li style="list-style:none" class="page-item disabled <?= ($currentPage == 1) ? "disabled" : "" ?>">
                            <a class="btn btn-success btn-sep icon-info text-light" href="./gestiontech?page=<?= $currentPage - 1 ?>"style="text-decoration:none">←</a> <!-- PAGE PRECEDENTE -->
                        </li>
                    </td>
                    <td>
                        <?php $page = $currentPage; ?>
                        <li style="list-style:none" class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                            <a class="btn btn-sep icon-info text-light" style="text-decoration:none"><b><?= "Page : $page" ?></b></a> <!-- AFFICHAGE DE LA PAGE ACTUEL -->
                        </li>
                    </td>
                    <td>
                        <li style="list-style:none" class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                            <a class="btn btn-success btn-sep icon-info text-light" href="./gestiontech?page=<?= $currentPage + 1 ?>"style="text-decoration:none">→</a> <!-- PAGE SUIVANTE -->
                        </li>
                    </td>
                </ul>
            </tr>
        </table>
    </table>
</div>
</body>

<script type="text/javascript" src="../js/style.js"></script>

</html>
