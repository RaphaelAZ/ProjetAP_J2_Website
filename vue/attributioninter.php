<?php
    session_start();
    include '../modele/page_management.php';
    include '../modele/attrib_inter.php';

    if($_SESSION["Mat"]==null || $_SESSION["redirection"]=="technicien"){
        header("location:../index.php");
        session_destroy();
    }

    $parPage = 10;
    $premier = (getCurrentPage() * $parPage) - $parPage;
    addInterToTech();
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
                Attribution d'interventions
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

                foreach(displayAttribInterventions($premier,$parPage) as $ligne){

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
                                    <?php displayTechList(); ?>
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
                                <li style="list-style:none" class="page-item disabled <?= (getCurrentPage() == 1) ? "disabled" : "" ?>">
                                    <a class="btn btn-success btn-sep icon-info text-light" href="./attributioninter.php?page=<?= getCurrentPage() - 1 ?>"style="text-decoration:none">←</a> <!-- PAGE PRECEDENTE -->
                                </li>
                            </td>
                            <td>
                                <?php $page = getCurrentPage(); ?>
                                <li style="list-style:none" class="page-item <?= (getCurrentPage() == $page) ? "active" : "" ?>">
                                    <a class="btn btn-sep icon-info text-light" style="text-decoration:none"><b><?= "Page : $page" ?></b></a> <!-- AFFICHAGE DE LA PAGE ACTUEL -->
                                </li>
                            </td>
                            <td>
                                <li style="list-style:none" class="page-item <?= (getCurrentPage() == getPages($parPage)) ? "disabled" : "" ?>">
                                    <a class="btn btn-success btn-sep icon-info text-light" href="./attributioninter.php?page=<?= getCurrentPage() + 1 ?>"style="text-decoration:none">→</a> <!-- PAGE SUIVANTE -->
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