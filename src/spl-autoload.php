<?php

/*
Cette méthode remplace les requires :

Elle charge tous les fichiers de classes grâce au chemin donné dans la variable $path : soit les classes présentes dans le
chemin "To-do_List/src/NomDeLaClasse.php".
*/
spl_autoload_register(function ($class) { // Un callback est une fonction passée en tant que paramètre à une autre fonction
    $path = ROOT . "/src/$class.php";

    if (file_exists($path)) {
        require_once $path;
        // die($class); // die() arrête le script
    }
});