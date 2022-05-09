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

    $DateDefaultD = "2022-04-01";
    $DateDefaultF = "2022-04-31";

    if(isset($_POST['month']) && isset($_POST['year'])){
        $DateDefaultD = $_POST['year']."-".$_POST['month']."-01";
        $DateDefaultF = $_POST['year']."-".$_POST['month']."-31";
    }

    $sql = "SELECT intervention.Matricule, COUNT(distinct intervention.Numero_Intervention) AS Interventions, sum(client.Distance_KM) AS Distance, SEC_TO_TIME(sum(controler.Temps_Passe)) AS Temps
                FROM technicien, intervention, controler, client 
                WHERE Etat='Fait'
                  AND intervention.matricule=technicien.matricule
                  AND intervention.Numero_Intervention = controler.Numero_Intervention
                  AND intervention.Numero_Client = client.Numero_Client
                  AND intervention.Date_Visite BETWEEN '$DateDefaultD' AND '$DateDefaultF'
                GROUP BY Matricule
                LIMIT $premier, $parPage;"; /* SELECTION DES DONNEES A AFFICHER SUR LA PAGE */

    $liste = mysqli_query($db, $sql);

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
            Statistiques Techniciens
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
                    <a href="attributioninter.php">
                        <button class="btn btn-success" type="button">
                            Attribuer intervention <!-- DECONNEXION RENVOI VERS LA PAGE DE DECONNEXION -->
                        </button>
                    </a>
                </td>
            </tr>
        </table>
    </div>
<div class="col-12 col-md-10 offset-0 offset-md-1">
    <table class="table table-borderless table-responsive pt-5" align="center" border="0" cellspacing="0" cellpadding="0">

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
                <form action="" method="post" align="center" style="font-size: 0.9em;">
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
                    <?php $annees = ["2017","2018","2019","2020","2021","2022","2023","2024","2025","2026","2027","2028","2029","2030","2031","2032","2033","2034","2035","2036","2037","2038","2039","2040"];?>
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
        </tr>

        <?php // AFFICHAGE DES DONNEES VIA REQUETES PUIS FOREACH QUI INSERE DANS LES LIGNES D'UN TABLEAU

        foreach($liste as $ligne){

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
            
        </tr>


        <table class="align"> <!-- TABLEAU DE NAVIGATION DES DONNÉES AFFICHÉES -->
            <tr style="alignment: center">
                <ul>
                    <td>
                        <li style="list-style:none" class="page-item disabled <?= ($currentPage == 1) ? "disabled" : "" ?>">
                            <a class="btn btn-success btn-sep icon-info text-light" href="./statstech.php?page=<?= $currentPage - 1 ?>"style="text-decoration:none">←</a> <!-- PAGE PRECEDENTE -->
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
                            <a class="btn btn-success btn-sep icon-info text-light" href="./statstech.php?page=<?= $currentPage + 1 ?>"style="text-decoration:none">→</a> <!-- PAGE SUIVANTE -->
                        </li>
                    </td>
                </ul>
            </tr>
        </table>

    </table>
</div>
</div>
</body>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
<script type="text/javascript">
    $(function() {
        $('.date-picker').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            onClose: function(dateText, inst) {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1));
            },
            beforeShow : function(input, inst) {
                var datestr;
                if ((datestr = $(this).val()).length > 0) {
                    year = datestr.substring(datestr.length-4, datestr.length);
                    month = jQuery.inArray(datestr.substring(0, datestr.length-5), $(this).datepicker('option', 'monthNamesShort'));
                    $(this).datepicker('option', 'defaultDate', new Date(year, month, 1));
                    $(this).datepicker('setDate', new Date(year, month, 1));
                }
            }
        });
    });
</script>
<style>
    .ui-datepicker-calendar {
        display: none;
    }
</style>

</html>