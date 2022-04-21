<?php
    session_start();
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

    $sql = "SELECT * FROM intervention LIMIT $premier, $parPage;"; /* SELECTION DES DONNEES A AFFICHER SUR LA PAGE */
    $liste = mysqli_query($db, $sql);

    if ($_SESSION['redirection'] == "technicien") {
        $user = $_SESSION['redirection'];
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
	<span class="login100-form-title p-b-26">
		Gestion de Fiches
	</span>

    <form action="../index.php" align="center"> <!-- DECONNEXION A MODIFIER -->
        <button type="submit" onclick="<?php session_destroy(); ?>">
            Se déconnecter
        </button>
    </form>


    <form action="<?php /* BOUTON DE TRIAGE PAR MATRICULE D'AGENT */
        $sqlagent = "SELECT * FROM intervention ORDER BY Matricule LIMIT $premier, $parPage;";
        $liste=mysqli_query($db,$sqlagent);
        ?>" align="center">
        <button>
            Trier par agent
        </button>
    </form>


    <form action="<?php /* BOUTON DE TRIAGE PAR DATE D'INTERVENTION */
        $sqldate = "SELECT * FROM intervention ORDER BY Date_Visite LIMIT $premier, $parPage;";
        $liste=mysqli_query($db,$sqldate);
        ?>" align="center">
        <button>
            Trier par date
        </button>
    </form>


    <table width="950" align="center" border="0" cellspacing="0" cellpadding="0">

        <tr style="background-color: #565e64;color: #ffffff"> <!-- INTITULE DES COLONNES -->
            <td align="center">
                <center>
                    <b>N° d'Intervention</b>
                </center>
            </td>
            <td align="center">
                <strong>Date Visite</strong>
            </td>
            <td align="center">
                <center>
                    <b>Heure Visite</b>
                </center>
            </td>
            <td align="center">
                <center>
                    <b>Numéro Client</b>
                </center>
            </td>
            <td align="center">
                <center>
                    <b>Matricule Employé</b>
                </center>
            </td>
        </tr>


        <tr> <!-- LIGNE DE SEPARATION -->
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>


        <?php // AFFICHAGE DES DONNEES VIA REQUETES PUIS FOREACH QUI INSERE DANS LES LIGNES D'UN TABLEAU
        foreach($liste as $ligne){
            ?>
            <tr align="center">
                <td><?= $ligne['Numero_Intervention'] ?></td>
                <td><?= $ligne['Date_Visite'] ?></td>
                <td><?= $ligne['Heure_Visite'] ?></td>
                <td><?= $ligne['Numero_Client'] ?></td>
                <td><?= $ligne['Matricule'] ?></td>
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


        <table width="400" border="0" cellspacing="20" cellpadding="10"> <!-- TABLEAU DE NAVIGATION DES DONNÉES AFFICHÉES -->
            <tr align="center">
                <ul>
                    <td>
                        <li style="list-style:none" class="page-item disabled <?= ($currentPage == 1) ? "disabled" : "" ?>">
                            <a href="./gestiontech?page=<?= $currentPage - 1 ?>"style="text-decoration:none">←</a> <!-- PAGE PRECEDENTE -->
                        </li>
                    </td>
                    <td>
                        <?php $page = $currentPage; ?>
                        <li style="list-style:none" class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                            <a class="btn btn-1 btn-sep icon-info" style="text-decoration:none"><?= "Page : $page" ?></a> <!-- AFFICHAGE DE LA PAGE ACTUEL -->
                        </li>
                    </td>
                    <td>
                        <li style="list-style:none" class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                            <a href="./gestiontech?page=<?= $currentPage + 1 ?>"style="text-decoration:none">→</a> <!-- PAGE SUIVANTE -->
                        </li>
                    </td>
                </ul>
            </tr>
        </table>

    </table>
</body>
<script type="text/javascript" src="../js/style.js"></script>
</html>
