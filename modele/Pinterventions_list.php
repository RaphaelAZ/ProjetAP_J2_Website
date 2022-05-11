<?php

function displayPInterventions($matricule) // RETOURNE LA LISTE DES INTERVENTIONS
{
    $sql = "SELECT * FROM intervention,client,controler WHERE intervention.Numero_Client=client.Numero_Client 
                                              AND intervention.Numero_Intervention=controler.Numero_Intervention
                                              AND intervention.Matricule='$matricule';"; /* SELECTION DES DONNEES A AFFICHER SUR LA PAGE */
    $liste = mysqli_query(connBDD(), $sql);

    if (isset($_POST['Tetat'])){
        $liste = mysqli_query(connBDD(), sortByState($matricule));
    }
    if (isset($_POST['TdateP'])){
        $liste = mysqli_query(connBDD(), sortByDate($matricule));
    }
    return $liste;
}

function UpdIntervention() // MET A JOUR LA BASE DE DONNÉE AVEC LES INFORMATIONS D'UNE INTERVENTION RÉALISÉE (Commentaire, Temps, Arrivée)
{
    if(isset($_POST['updatesub'])&&isset($_GET['modif'])){ //ENVOI ET VERIFICATION SI LES DONNÉES ONT BIEN ETES MODIFIEES
        $sql="UPDATE intervention,controler
              SET intervention.Date_Visite = '".$_POST["datepick"]."', intervention.Heure_Visite = '".$_POST["heure"]."', intervention.Etat = '".$_POST["etat"]."', controler.Commentaire = '".$_POST["comm"]."', controler.Temps_Passe = '".$_POST["duree"]."'
              WHERE intervention.Numero_Intervention=controler.Numero_Intervention
              AND intervention.Numero_Intervention='".$_GET['modif']."' ";
        if(connBDD()->query($sql)===TRUE ){
            header("location:mesinters.php");
        }
    }
}

function sortByDate($matricule) // RETOURNE LA REQUETE DE TRI PAR DATE (DANS L'ORDRE DÉCROISSANT POUR POUVOIR VOIR LES DERNIERES INTERVENTIONS PROGRAMMÉES)
{
    $sql = "SELECT * FROM intervention,client,controler 
         WHERE intervention.Numero_Client=client.Numero_Client                                    
           AND intervention.Numero_Intervention=controler.Numero_Intervention
            AND intervention.Matricule='$matricule' ORDER BY Date_Visite ASC;";
    return $sql;
}

function sortByState($matricule) // RETOURNE LA REQUETE DE TRI PAR TECHNICIEN (DANS L'ORDRE CROISSANT)
{
    $sql = "SELECT * FROM intervention,client,controler 
         WHERE intervention.Numero_Client=client.Numero_Client                                    
           AND intervention.Numero_Intervention=controler.Numero_Intervention
            AND intervention.Matricule='$matricule' ORDER BY intervention.Etat DESC;";
    return $sql;
}