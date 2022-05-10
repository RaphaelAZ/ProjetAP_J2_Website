<?php
session_start(); //RECUPERE LA SESSION ACTIVE
session_destroy(); //DETRUIT LA SESSION
header('Location:../index.php'); //RENVOIE L'UTILISATEUR SUR LA PAGE DE CONNEXION
exit;
