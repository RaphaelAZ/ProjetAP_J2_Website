<?php
    session_start();
    if($_SESSION["Mat"]==null||$_SESSION["redirection"]=="technicien"){
        header("location:../index.php");
        session_destroy();
    }
    else{
        $matricule= $_SESSION["Mat"];
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


    $sql = "SELECT Numero_Intervention,Date_Visite,Heure_Visite,Nom_Prenom,Adresse,Nom_Agence
            FROM intervention,client,agence
            WHERE Etat!='Fait' AND Matricule IS NULL AND intervention.Numero_Client=client.Numero_Client AND client.Numero_Agence = agence.Numero_Agence
            LIMIT $premier, $parPage;"; /* SELECTION DES DONNEES A AFFICHER SUR LA PAGE */

    $liste = mysqli_query($db, $sql);

    if(isset($_POST['updinter'])&&isset($_GET['inter'])){ //ENVOI ET VERIFICATION SI LES DONNÉES ONT BIEN ETES MODIFIEES
        $sql="UPDATE intervention
                  SET Matricule = '".$_POST["updinter"]."'
                  WHERE Numero_Intervention='".$_GET['inter']."' ";
        if($db->query($sql)===TRUE){
            header("location:attributioninter.php");
        }
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
    <div class="row">
        <div class="col-12 col-lg-10 offset-0 offset-lg-1 align table-responsive" style="color:#FFFFFF;padding-top: 20px;padding-bottom: 100px;">
            <h1 class="login100-form-title p-b-26 text-light mb-4" style="padding-bottom: 20px;">
                Statistiques Techniciens
            </h1>
            <table class="table-borderless">
                <tr>
                    <td>
                        <a href="../controleur/logout.php">
                            <button class="btn btn-success" type="button">
                                Se déconnecter <!-- DECONNEXION RENVOI VERS LA PAGE DE CONNEXION -->
                            </button>
                        </a>
                    </td>
                    <td>
                        <a href="statstech.php">
                            <button class="btn btn-success" type="button">
                                Statistiques <!-- RETOUR VERS LA PAGE DE STATS -->
                            </button>
                        </a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-12 col-lg-10 offset-0 offset-lg-1 table-responsive">
            <table class="table table-borderless pt-5" align="center" border="0" cellspacing="0" cellpadding="0">

                <tr style="background-color: #565e64;color: #ffffff;font-size: 1.5em;"> <!-- INTITULE DES COLONNES -->
                    <td align="center">
                        <center>
                            Numéro
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Date
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Heure
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Client
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Adresse
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Agence
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Attribution
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
                </tr>

                <?php // AFFICHAGE DES DONNEES VIA REQUETES PUIS FOREACH QUI INSERE DANS LES LIGNES D'UN TABLEAU

                foreach($liste as $ligne){

                    ?>

                    <tr align="center" style="font-size: 1.2em;color: #a3a1a4">
                        <td><?= $ligne['Numero_Intervention'] ?></td>
                        <td><?= $ligne['Date_Visite'] ?></td>
                        <td><?= $ligne['Heure_Visite'] ?></td>
                        <td><?= $ligne['Nom_Prenom'] ?></td>
                        <td><?= $ligne['Adresse'] ?></td>
                        <td><?= $ligne['Nom_Agence'] ?></td>
                        <td>
                            <form action="attributioninter.php?inter=<?=$ligne['Numero_Intervention']?>" method="post" align="center" style="font-size: 0.9em;">
                                <select id="updinter" name="updinter" required>
                                    <?php

                                    $sql2 = "SELECT Matricule,Nom_Employe,Prenom_Employe,agence.Nom_Agence
                                    FROM technicien,agence
                                    WHERE technicien.Numero_Agence = agence.Numero_Agence;";

                                    $liste2 = mysqli_query($db, $sql2);

                                    foreach ($liste2 as $ligne){
                                        echo("<option value='".$ligne['Matricule']."'>".$ligne['Nom_Employe']." ".$ligne['Prenom_Employe']." - ".$ligne['Nom_Agence']."</option>");
                                    }
                                    ?>
                                </select>
                                <input class="btn btn-success m-0" type="submit" name="updtech" id="updtech" value="Envoyer"/>
                            </form>
                        </td>
                    </tr>

                    <?php

                }

                ?>

                <tr> <!-- LIGNE DE SEPARATION -->
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <table class="align"> <!-- TABLEAU DE NAVIGATION DES DONNÉES AFFICHÉES -->
                    <tr style="alignment: center">
                        <ul>
                            <td>
                                <li style="list-style:none" class="page-item disabled <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                    <a class="btn btn-success btn-sep icon-info text-light" href="./attributioninter.php?page=<?= $currentPage - 1 ?>"style="text-decoration:none">←</a> <!-- PAGE PRECEDENTE -->
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
                                    <a class="btn btn-success btn-sep icon-info text-light" href="./attributioninter.php?page=<?= $currentPage + 1 ?>"style="text-decoration:none">→</a> <!-- PAGE SUIVANTE -->
                                </li>
                            </td>
                        </ul>
                    </tr>
                </table>
            </table>
        </div>
    </div>
</div>
</body>
</html>