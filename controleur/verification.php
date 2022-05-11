<?php
    session_start(); //RECUPERE LA SESSION ACTIVE

    if(isset($_POST['username']) && isset($_POST['password'])) { //Vérifie que l'identifiant et le mot de passe ont étés remplis

        include ('modele/bdd_connection.php'); // On inclus la connexion à la BDD
        include_once ('modele/user_connection.php'); // On inclus la liste des fonctions pour vérifier la connexion

        $username = mysqli_real_escape_string(connBDD(), htmlspecialchars($_POST['username'])); //Les 2 inputs protègent les caractères spéciaux de
        $password = mysqli_real_escape_string(connBDD(), htmlspecialchars($_POST['password'])); //la chaine pour l'utiliser dans les requête des fonctions

        $mdpcrypte = md5($password); //On hash le mot de passe en md5 pour le comparer (OUTDATED)

        $_SESSION["Mat"] = userSession($username,$mdpcrypte); // On récupère le matricule de l'utilisateur connecté

        if (verifUsers($username,$mdpcrypte) && verifTech($_SESSION["Mat"])){ // Si la personne connectée est un utilisateur et un technicien


            $_SESSION['redirection'] = "technicien"; // On identifie la personne comme étant un technicien
            header('Location: vue/gestiontech.php'); // Redirection vers la page principale des techniciens

        }
        elseif (verifUsers($username,$mdpcrypte) && !verifTech($_SESSION["Mat"])){// Si la personne connectée est un utilisateur mais pas un technicien

            $_SESSION['redirection'] = "gestionnaire"; // On identifie la personne comme étant un gestionnaire
            header('Location: vue/statstech.php'); // Redirection vers la page principale des gestionnaires

        }
        else {

            header('Location: index.php?erreur=1'); // Utilisateur ou mot de passe incorrect, on redirige vers la page de connexion avec message d'erreur

        }
    }

