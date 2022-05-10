<?php
    session_start();

    if(isset($_POST['username']) && isset($_POST['password'])) {

        include ('modele/bdd_connection.php'); // ON INCLUS LA CONNEXION A LA BDD
        include_once ('modele/user_connection.php'); // ON INCLUS LA LISTE DES FONCTIONS DE VERIFICATION POUR LA CONNEXION

        $username = mysqli_real_escape_string(connBDD(), htmlspecialchars($_POST['username']));
        $password = mysqli_real_escape_string(connBDD(), htmlspecialchars($_POST['password']));

        $mdpcrypte = md5($password);

        $_SESSION["Mat"]=userSession($username,$mdpcrypte);

        if (verifUsers($username,$mdpcrypte) && verifTech($_SESSION["Mat"])) // Nom d'utilisateur et Mot de passe correctes
        {
            $_SESSION['redirection'] = "technicien";
            header('Location: vue/gestiontech.php'); //Redirection
        }
        elseif (verifUsers($username,$mdpcrypte) && !verifTech($_SESSION["Mat"])){
            $_SESSION['redirection'] = "gestionnaire";
            header('Location: vue/statstech.php'); //Redirection
        }
        else {
            header('Location: index.php?erreur=1'); // utilisateur ou mot de passe incorrect
        }
    }
?>
