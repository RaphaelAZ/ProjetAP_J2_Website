<?php
    session_start();

    if(isset($_POST['username']) && isset($_POST['password'])) {
        // connexion à la base de données
        $db_username = 'root';
        $db_password = '';
        $db_name = 'ap2';
        $db_host = '127.0.0.1';
        $db = mysqli_connect($db_host, $db_username, $db_password, $db_name)
        or die('could not connect to database');
        mysqli_set_charset($db, 'utf8');

        // on applique les deux fonctions mysqli_real_escape_string et htmlspecialchars
        // pour éliminer toute attaque de type injection SQL et XSS
        $username = mysqli_real_escape_string($db, htmlspecialchars($_POST['username']));
        $password = mysqli_real_escape_string($db, htmlspecialchars($_POST['password']));

        $mdpcrypte = md5($password);

        $requete = "SELECT Matricule
                        FROM utilisateurs
                        WHERE email = '" . $username . "' and passworld = '" . $mdpcrypte . "' ";

        $exec_requete = mysqli_query($db, $requete);
        $reponse = mysqli_fetch_array($exec_requete);
        $mat = $reponse["Matricule"];
        $_SESSION["Mat"]=$mat;

        $requete = "SELECT count(*)
                        FROM utilisateurs
                        WHERE email = '" . $username . "' and passworld = '" . $mdpcrypte . "' ";

        $exec_requete = mysqli_query($db, $requete);
        $reponse = mysqli_fetch_array($exec_requete);
        $count = $reponse['count(*)'];

        if ($count != 0) // Nom d'utilisateur et Mot de passe correctes
        {
            $_SESSION['redirection'] = "technicien";
            header('Location: vue/gestiontech.php'); //Redirection
        } else {
            header('Location: index.php?erreur=1'); // utilisateur ou mot de passe incorrect
        }
        mysqli_close($db); // fermer la connexion
    }
?>
