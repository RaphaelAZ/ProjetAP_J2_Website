<?php

function displayAttribInterventions($premier,$parPage){ // RETOURNE LA LISTE DES INTERVENTIONS NON-ATTRIBUÉES
    $sql = "SELECT Numero_Intervention,Date_Visite,Heure_Visite,Nom_Prenom,Adresse,Nom_Agence
            FROM intervention,client,agence
            WHERE Etat!='Fait' AND Matricule IS NULL AND intervention.Numero_Client=client.Numero_Client AND client.Numero_Agence = agence.Numero_Agence
            LIMIT $premier, $parPage;";

    return mysqli_query(connBDD(), $sql);
}

function displayTechList(){ //RETOURNE LA LISTE DEROULANTE DES TECHNICIENS
    $sql = "SELECT Matricule,Nom_Employe,Prenom_Employe,agence.Nom_Agence
                                    FROM technicien,agence
                                    WHERE technicien.Numero_Agence = agence.Numero_Agence;";

    $liste = mysqli_query(connBDD(), $sql);

    foreach ($liste as $ligne){
        echo("<option value='".$ligne['Matricule']."'>".$ligne['Nom_Employe']." ".$ligne['Prenom_Employe']." - ".$ligne['Nom_Agence']."</option>");
    }
}

function addInterToTech(){
    if(isset($_POST['updinter'])&&isset($_GET['inter'])){ //ENVOI ET VERIFICATION SI LES DONNÉES ONT BIEN ETES MODIFIEES
        $sql="UPDATE intervention
                  SET Matricule = '".$_POST["updinter"]."'
                  WHERE Numero_Intervention='".$_GET['inter']."' ";
        if(connBDD()->query($sql)===TRUE){
            header("location:attributioninter.php");
        }
    }
}