<?php

// Cette méthode remplace les requires (à revoir)
// Charge tous les fichiers de classes, je n'ai donc pas besoin d'appeler le fichier Database.php dans BaseController
spl_autoload_register(function ($class) { // Un callback est une fonction passée en tant que paramètre à une autre fonction
    $path = ROOT . "/src/$class.php";

    if (file_exists($path)) {
        require_once $path;
        // die($class); // die() arrête le script
    }
});