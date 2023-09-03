<?php
    // mysql_xxxxxxxx => On oubli !
    // ==> mysqli_xxxxxxxxx => Style procédural
    // PDO => Style Orienté Objet
    // https://fr.php.net/mysqli_connect

    // Connexion Serveur BDD
    $link = mysqli_connect(SERVEUR_BDD, USER_BDD,PASSWORD_BDD) or die(ERROR_SERVEUR);

    // Selection de la base de données
    mysqli_select_db($link,NAME_BDD) or die(ERROR_BDD);

    // Pour ne pas avoir de soucis d'accent....
    $sql = "SET CHARACTER SET 'utf8mb4';";
    mysqli_query($link, $sql);

    $sql = "SET collation_connection = 'utf8mb4_general_ci';";
    mysqli_query($link, $sql);
?>