<?php
    session_start(); // Récupère la session active
    include '../modele/page_management.php';
    include '../modele/stats_tech_list.php';

    if($_SESSION["Mat"]==null||$_SESSION["redirection"]=="technicien"){ // Si les variables de session diffèrent d'un identifiant
                                                                        // gestionnaire on détruit la session et on renvoi vers la connexion
        header("location:../index.php");
        session_destroy();
    }

    $parPage = 10;
    $premier = (getCurrentPage() * $parPage) - $parPage;
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
                                Se déconnecter <!-- DECONNEXION RENVOI VERS LA PAGE DE DECONNEXION -->
                            </button>
                        </a>
                    </td>
                    <td>
                        <a href="attributioninter.php">
                            <button class="btn btn-success" type="button">
                                Attribuer intervention <!-- DECONNEXION RENVOI VERS LA PAGE DE DECONNEXION -->
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
                            Matricule
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Nombre d'interventions
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Temps passé
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Distance parcourue (km)
                        </center>
                    </td>
                    <td align="center">
                        <form action="" method="post" align="center" style="font-size: 0.9em;"> <!-- FORMULAIRE POUR SELECTIONNER LE MOIS QUI CONCERNE LES STATS A AFFICHER -->
                            <select id="month" name="month" required>
                                <option value="01">Janvier</option>
                                <option value="02">Février</option>
                                <option value="03">Mars</option>
                                <option value="04">Avril</option>
                                <option value="05">Mai</option>
                                <option value="06">Juin</option>
                                <option value="07">Juillet</option>
                                <option value="08">Aout</option>
                                <option value="09">Septembre</option>
                                <option value="10">Octobre</option>
                                <option value="11">Novembre</option>
                                <option value="12">Décembre</option>
                            </select>
                            <?php $annees = ["2017","2018","2019","2020","2021","2022","2023","2024","2025","2026","2027","2028","2029","2030"];?>
                            <select id="year" name="year" required>
                                <?php
                                foreach ($annees as $annee){
                                    echo("<option value='$annee'>$annee</option>");
                                }
                                ?>
                            </select>
                            <input class="btn btn-success m-0" type="submit" name="dateP" id="dateP" value="Trier"/>
                        </form>
                    </td>
                </tr>


                <tr> <!-- LIGNE DE SEPARATION -->
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <?php
                foreach(displayStats($premier,$parPage) as $ligne){ //AFFICHAGE DES DONNEES DANS UN TABLEAU VIA UN FOREACH
                ?>
                        <tr align="center" style="font-size: 1.2em;color: #a3a1a4">
                            <td><?= $ligne['Matricule'] ?></td>
                            <td><?= $ligne['Interventions'] ?></td>
                            <td><?= $ligne['Temps'] ?></td>
                            <td><?= $ligne['Distance'] ?></td>
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
                                <li style="list-style:none" class="page-item disabled <?= (getCurrentPage() == 1) ? "disabled" : "" ?>">
                                    <a class="btn btn-success btn-sep icon-info text-light" href="./statstech.php?page=<?= getCurrentPage() - 1 ?>"style="text-decoration:none">←</a> <!-- PAGE PRECEDENTE -->
                                </li>
                            </td>
                            <td>
                                <?php $page = getPages($parPage); ?>
                                <li style="list-style:none" class="page-item <?= (getCurrentPage() == $page) ? "active" : "" ?>">
                                    <a class="btn btn-sep icon-info text-light" style="text-decoration:none"><b><?= "Page : $page" ?></b></a> <!-- AFFICHAGE DE LA PAGE ACTUEL -->
                                </li>
                            </td>
                            <td>
                                <li style="list-style:none" class="page-item <?= (getCurrentPage() == getPages($parPage)) ? "disabled" : "" ?>">
                                    <a class="btn btn-success btn-sep icon-info text-light" href="./statstech.php?page=<?= getCurrentPage() + 1 ?>"style="text-decoration:none">→</a> <!-- PAGE SUIVANTE -->
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