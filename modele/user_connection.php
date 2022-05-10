<?php

function userSession($username,$mdpcrypte) // RÉCUPERE LE MATRICULE DE L'UTILISATEUR CONNECTÉ
{
    $requete = "SELECT Matricule
                            FROM utilisateurs
                            WHERE email = '" . $username . "' and passworld = '" . $mdpcrypte . "' ";

    $exec_requete = mysqli_query(connBDD(), $requete);
    $reponse = mysqli_fetch_array($exec_requete);
    $mat = $reponse["Matricule"];
    mysqli_close(connBDD()); // fermer la connexion
    return $mat;
}

function verifUsers($username,$mdpcrypte) // VÉRIFIE QUE L'UTILISATEUR FAIT PARTIE DE LA TABLE UTILISATEURS
{
    $verif=false;
    $requete = "SELECT count(*)
                        FROM utilisateurs
                        WHERE email = '" . $username . "' and passworld = '" . $mdpcrypte . "' ";

    $exec_requete = mysqli_query(connBDD(), $requete);
    $reponse = mysqli_fetch_array($exec_requete);
    $count = $reponse['count(*)'];

    if($count==1){
        $verif=true;
    }

    mysqli_close(connBDD()); // VÉRIFIE QUE L'UTILISATEUR FAIT PARTIE DE LA TABLE TECHNICIEN

    return $verif;
}

function verifTech($mat)
{
    $verif=false;
    $requete = "SELECT count(*)
                        FROM technicien
                        WHERE Matricule='$mat'";

    $exec_requete = mysqli_query(connBDD(), $requete);
    $reponse = mysqli_fetch_array($exec_requete);
    $count = $reponse['count(*)'];

    if($count==1){
        $verif=true;
    }

    mysqli_close(connBDD()); // fermer la connexion

    return $verif;
}
