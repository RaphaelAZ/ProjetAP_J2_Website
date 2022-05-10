<?php

function displayStats($premier,$parPage) // RETOURNE LA LISTE DES INTERVENTIONS
{
    $DateDefaultD = "2022-04-01";
    $DateDefaultF = "2022-04-31";

    $sql = "SELECT intervention.Matricule, COUNT(distinct intervention.Numero_Intervention) AS Interventions, sum(client.Distance_KM) AS Distance, SEC_TO_TIME(sum(controler.Temps_Passe)) AS Temps
                FROM technicien, intervention, controler, client 
                WHERE Etat='Fait'
                  AND intervention.matricule=technicien.matricule
                  AND intervention.Numero_Intervention = controler.Numero_Intervention
                  AND intervention.Numero_Client = client.Numero_Client
                  AND intervention.Date_Visite BETWEEN '$DateDefaultD' AND '$DateDefaultF'
                GROUP BY Matricule
                LIMIT $premier, $parPage;"; /* SELECTION DES DONNEES A AFFICHER SUR LA PAGE */
    $liste = mysqli_query(connBDD(), $sql);

    if (isset($_POST['month']) && isset($_POST['year'])){
        $liste = mysqli_query(connBDD(), sortByMonth($premier,$parPage));
    }

    return $liste;
}

function sortByMonth($premier,$parPage){ //TRIAGE STATS PAR MOIS (MODIFIER LA REQUETE PRINCIPALE)

    $DateDefaultD = $_POST['year']."-".$_POST['month']."-01";
    $DateDefaultF = $_POST['year']."-".$_POST['month']."-31";

    $sql = "SELECT intervention.Matricule, COUNT(distinct intervention.Numero_Intervention) AS Interventions, sum(client.Distance_KM) AS Distance, SEC_TO_TIME(sum(controler.Temps_Passe)) AS Temps
                FROM technicien, intervention, controler, client 
                WHERE Etat='Fait'
                  AND intervention.matricule=technicien.matricule
                  AND intervention.Numero_Intervention = controler.Numero_Intervention
                  AND intervention.Numero_Client = client.Numero_Client
                  AND intervention.Date_Visite BETWEEN '$DateDefaultD' AND '$DateDefaultF'
                GROUP BY Matricule
                LIMIT $premier, $parPage;";
    return $sql;
}