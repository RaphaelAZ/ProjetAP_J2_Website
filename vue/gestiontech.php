<?php
    session_start();
    include '../modele/page_management.php';
    include '../modele/interventions_list.php';

    if($_SESSION["Mat"]==null||$_SESSION["redirection"]=="gestionnaire"){
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
                    Liste d'Interventions
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
                    foreach(displayInterventions($premier,$parPage) as $ligne){
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
                                    <li style="list-style:none" class="page-item disabled <?= (getCurrentPage() == 1) ? "disabled" : "" ?>">
                                        <a class="btn btn-success btn-sep icon-info text-light" href="./gestiontech?page=<?= getCurrentPage() - 1 ?>"style="text-decoration:none">←</a> <!-- PAGE PRECEDENTE -->
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
                                        <a class="btn btn-success btn-sep icon-info text-light" href="./gestiontech?page=<?= getCurrentPage() + 1 ?>"style="text-decoration:none">→</a> <!-- PAGE SUIVANTE -->
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

<script type="text/javascript" src="../js/style.js"></script>

</html>
