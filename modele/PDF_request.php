<?php

include 'bdd_connection.php';

function collectDatas($intervention) //RETOURNE LES DONNÉES D'UNE FICHE D'INTERVENTION
{
    $sql = "SELECT client.Nom_Prenom,client.Numero_Client,client.Telephone_Client,technicien.Nom_Employe,technicien.Prenom_Employe,Agence.Nom_Agence,client.Adresse,intervention.Date_Visite,intervention.Heure_Visite,materiel.Reference_Interne,materiel.Numero_de_Serie,type_materiel.Libelle_Type_materiel   /* SELECTION DES DONNEES A AFFICHER SUR Le PDF */
                FROM intervention,client,controler,materiel,type_materiel,technicien,agence
                WHERE intervention.Numero_Client=client.Numero_Client
                    AND intervention.Numero_Intervention=controler.Numero_Intervention
                    AND intervention.Matricule = technicien.Matricule
                    AND technicien.Numero_Agence = agence.Numero_Agence
                    AND controler.Numero_de_Serie=materiel.Numero_de_Serie
                    AND materiel.Reference_Interne=type_materiel.Reference_Interne
                    AND intervention.Numero_Intervention='$intervention'";
    $liste = mysqli_query(connBDD(), $sql);
    return $liste;
}