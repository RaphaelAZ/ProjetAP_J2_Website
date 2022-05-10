<?php

function displayInterventions($premier,$parPage) // RETOURNE LA LISTE DES INTERVENTIONS
{
    $sql = "SELECT * FROM intervention,technicien WHERE intervention.Matricule=technicien.Matricule LIMIT $premier, $parPage;"; /* SELECTION DES DONNEES A AFFICHER SUR LA PAGE */
    $liste = mysqli_query(connBDD(), $sql);

    if (isset($_POST['agent'])){
        $liste = mysqli_query(connBDD(), sortByTech($premier,$parPage));
    }
    if (isset($_POST['dateP'])){
        $liste = mysqli_query(connBDD(), sortByDate($premier,$parPage));
    }
    return $liste;
}

function sortByTech($premier,$parPage) // RETOURNE LA REQUETE DE TRI PAR TECHNICIEN (DANS L'ORDRE CROISSANT)
{
        $sql = "SELECT * FROM intervention,technicien WHERE intervention.Matricule=technicien.Matricule ORDER BY intervention.Matricule ASC LIMIT $premier, $parPage;";
        return $sql;
}

function sortByDate($premier,$parPage) // RETOURNE LA REQUETE DE TRI PAR DATE (DANS L'ORDRE DÉCROISSANT POUR POUVOIR VOIR LES DERNIERES INTERVENTIONS PROGRAMMÉES)
{
        $sql = "SELECT * FROM intervention,technicien WHERE intervention.Matricule=technicien.Matricule ORDER BY Date_Visite DESC LIMIT $premier, $parPage;";
        return $sql;
}