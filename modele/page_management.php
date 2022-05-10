<?php

include 'bdd_connection.php';

function getCurrentPage() // RETOURNE LE NUMÉRO PAGE AFFICHÉE
{
    if (isset($_GET['page']) && !empty($_GET['page'])) { /* OBTENTION DE LA PAGE ACTUEL */
        $currentPage = (int)strip_tags($_GET['page']);
    } else {
        $currentPage = 1;
    }
    return $currentPage;
}

function getPages($parPage) //CALCUL DU NOMBRE DE PAGES AFFICHABLE
{
    $sql = 'SELECT COUNT(*) AS nb_lignes FROM intervention;';
    $requete = mysqli_query(connBDD(), $sql);
    $resultat = mysqli_fetch_assoc($requete);
    $nbListe = (int)$resultat['nb_lignes']; //CALCUL DU NOMBRE DE LIGNES A AFFICHER
    $pages = ceil($nbListe / $parPage);

    return $pages;
}