<?php

// J'importe les contrôleurs à l'aide de leur namespace
use src\controllers\TaskController;

// Je défini le chemin d'accès de chaque dossier ici :
define('ROOT', dirname(__DIR__)); // dirname(__DIR__) va récupérer le nom du dossier parent (ici "Trouver-Ma-Traduction") et le stocker dans la constante nommée "ROOT"
define('TEMPLATES', ROOT . '/templates'); //Je déclare une constante nommée "VIEWS" qui prend comme valeur la racine du projet concaténé avec la chaine de caractères "/views", ce qui donne le chemin complet suivant : racine_du_projet/views

require_once ROOT . "/vendor/autoload.php"; // Va charger toutes les librairies présentes dans le dossier vendors ainsi que les classes du dossier src. Cela remplace la fonction spl_autoload que j'avais déclaré avant (voir fichier spl-autoload.php dans le dossier src)

/* PATH_INFO correspond à (à développer) */
switch ($_SERVER['PATH_INFO'] ?? '/') { // J'utilise un switch pour gérer les différents appels de fichiers

    // TaskController
    case '/': // Ce chemin défini index.html.twig comme page d'accueil, j'arriverais sur cette page lorsque je lancerais le serveur
        (new TaskController())->index(); // Je créé un objet TaskController() qui appelle la méthode index() afin d'appeler le fichier index.html.twig
        break;

    case '/task/createTask':
        (new TaskController())->createTask();
        break;

    case '/task/updateTask':
        (new TaskController())->updateTask();
        break;

    // J'ajoute la route pour la suppression (sinon la supression me renverra une erreur et ne supprimera pas la tâche)
    case '/delete':
        (new TaskController())->deleteTask();
        break;
        
    default:
        echo "Page introuvable.";
        break;
}
