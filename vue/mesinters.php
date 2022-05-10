<?php
    session_start();
    include '../modele/Pinterventions_list.php';
    include '../modele/page_management.php';

    if($_SESSION["Mat"]==null){
        header("location:../index.php");
        session_destroy();
    }
    else{
        $matricule= $_SESSION["Mat"];
    }

    $parPage = 10;
    $premier = (getCurrentPage() * $parPage) - $parPage;
    UpdIntervention();
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
                Gestion de Fiches
            </h1>
            <table class="table-borderless">
                <tr>
                    <td>
                        <form action="gestiontech.php" method="post" align="center">
                            <input class="btn btn-success" type="submit" name="mesinters" id="mesinters" value="Accueil"/>
                        </form>
                    </td>
                    <td>
                        <a href="../controleur/logout.php">
                            <button class="btn btn-success" type="button">
                                Se déconnecter <!-- DECONNEXION RENVOI VERS LA PAGE DE DECONNEXION -->
                            </button>
                        </a>
                    </td>
                    <td>
                        <form action="" method="post" align="center">
                            <input class="btn btn-success" type="submit" name="TdateP" id="TdateP" value="Trier par Date"/>
                        </form>
                    </td>
                    <td>
                        <form action="" method="post" align="center">
                            <input class="btn btn-success" type="submit" name="Tetat" id="Tetat" value="Trier par Etat"/>
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
                            Adresse
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Durée
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Commentaire
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Etat
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Modifier
                        </center>
                    </td>
                    <td align="center">
                        <center>
                            Fiches
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
                    <td>&nbsp;</td>
                </tr>

                <?php // AFFICHAGE DES DONNEES VIA REQUETES PUIS FOREACH QUI INSERE DANS LES LIGNES D'UN TABLEAU

                foreach(displayPInterventions($premier,$parPage,$matricule) as $ligne){
                    ?>
                        <tr align="center" style="font-size: 1.2em;color: #a3a1a4">

                            <td><?= $ligne['Numero_Intervention'] ?></td>
                            <td><?= $ligne['Date_Visite'] ?></td>
                            <td><?= $ligne['Heure_Visite'] ?></td>
                            <td><?= $ligne['Adresse'] ?></td>
                            <td><?= $ligne['Temps_Passe'];?></td>
                            <td><?= $ligne['Commentaire'];?></td>
                            <td><?= $ligne['Etat'] ?></td>

                            <td>
                                <?php
                                    if($ligne['Etat']!="Fait"){
                                        if(isset($_GET['modif'])){
                                        if ($_GET['modif']==$ligne['Numero_Intervention']) {
                                            echo("
                                            <a href='mesinters.php'>
                                                <img src='../img/gear.png' style='width: 1.5em'>
                                            </a>");
                                        }
                                        else {
                                            echo("
                                            <a href='mesinters.php?modif=".$ligne['Numero_Intervention']."'>
                                                <img src='../img/gear.png' style='width: 1.5em'>
                                            </a>");
                                        }
                                        }
                                        else {
                                            echo("
                                            <a href='mesinters.php?modif=".$ligne['Numero_Intervention']."'>
                                                <img src='../img/gear.png' style='width: 1.5em'>
                                            </a>");
                                        }
                                    }
                                ?>
                            </td>

                            <td>
                                <a href="../controleur/testpdf.php?num=<?=$ligne['Numero_Intervention']?>">
                                            <img src="../img/download.png" style="width: 1em">
                                </a>
                            </td>
                        </tr>
                    <?php
                }
                ?>
            </table>

            <div class="col-12 align pt-5">
                <table>
                    <tr align="center" style="font-size: 1.2em;color: #a3a1a4">
                        <?php
                            if (isset($_GET['modif'])) {
                                echo("
                                    <form id='upd' name='upd' method='post'>
                                        <h2 class='text-light pb-3'>Modification de la fiche N°".$_GET['modif']."</h2>
                                        <td>Date : <input class='form-control' type='text' id='datepick' name='datepick' style='width: 55%' required/></td>
                                        <td style='padding-right: 10px;'>Heure : <input class='form-control' type='time' id='heure' name='heure' required/></td>
                                        <td>Durée : <input class='form-control' type='time' id='duree' name='duree' style='padding-right: 10px;' required/></td>
                                        <td style='padding-right: 10px'>Commentaire : <input class='form-control' type='text' id='comm' name='comm' style='margin-left: 10px;' placeholder='Installation...' required/></td>
                                        <td>Etat : <select class='form-select' id='etat' name='etat' style='width: 70%' required>
                                            <option value='Fait'>Fait</option>
                                            <option value='Retard'>En retard</option>
                                        </select></td>
                                        <td><button class='btn btn-success' id='updatesub' name='updatesub' type='submit'>Modifier</button></td>
                                    </form>
                                ");
                            }
                        ?>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>
</body>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $( function() {
        $.datepicker.regional['fr'] = {
            dateFormat: 'yy-mm-dd',
            closeText: 'Fermer',
            prevText: '&#x3c; Précédent',
            nextText: 'Suivant &#x3e;',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier','Fevrier','Mars','Avril','Mai','Juin',
                'Juillet','Aout','Septembre','Octobre','Novembre','Decembre'],
            monthNamesShort: ['Jan','Fev','Mar','Avr','Mai','Jun',
                'Jul','Aou','Sep','Oct','Nov','Dec'],
            dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
            dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
            dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
            weekHeader: 'Sm',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: '',
            maxDate: '+12M +0D',
            numberOfMonths: 1,
            showButtonPanel: true
        };
        $.datepicker.setDefaults($.datepicker.regional['fr']);
        $('#datepick').datepicker();
    } );
</script>

</html>