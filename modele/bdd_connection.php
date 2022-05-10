<?php

function connBDD() // RETOURNE UNE CONNECTION A LA BASE DE DONNÉES
{
    $db_username = 'root';
    $db_password = '';
    $db_name = 'ap2';
    $db_host = '127.0.0.1';
    $db = mysqli_connect($db_host, $db_username, $db_password, $db_name) or die('could not connect to database');
    mysqli_set_charset($db, 'utf8');
    return $db;
}