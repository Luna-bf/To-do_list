<?php

// J'importe les contrôleurs à l'aide de leur namespace
use src\controllers\TaskController;
use src\controllers\UserController;

// Je défini le chemin d'accès de chaque dossier ici :
define('ROOT', dirname(__DIR__)); // dirname(__DIR__) va récupérer le nom du dossier parent (ici "Trouver-Ma-Traduction") et le stocker dans la constante nommée "ROOT"
define('TEMPLATES', ROOT . '/templates'); //Je déclare une constante nommée "VIEWS" qui prend comme valeur la racine du projet concaténé avec la chaine de caractères "/views", ce qui donne le chemin complet suivant : racine_du_projet/views

require_once ROOT . "/vendor/autoload.php"; // Va charger toutes les librairies présentes dans le dossier vendors ainsi que les classes du dossier src. Cela remplace la fonction spl_autoload que j'avais déclaré avant (voir fichier spl-autoload.php dans le dossier src)

/* PATH_INFO contient le chemin de l'URL, par exemple : si l'URL appelé est "/home" alors le chemin de l'URL sera
"http://localhost/home". J'utilise l'opérateur de fusion (coalescing operator) afin de vérifier si PATH_INFO existe, si
c'est le cas, je renvoie vers la racine (la page d'accueil) */
switch ($_SERVER['PATH_INFO'] ?? '/') { // J'utilise un switch pour gérer les différents appels de fichiers

    // UserController
    case '/': // Ce chemin défini register.html.twig comme page d'accueil, j'arriverais sur cette page lorsque je lancerais le serveur
        (new UserController())->register(); // Je crée un objet UserController() qui appelle la méthode register() afin charger la vue et les données de cette méthode (ici le fichier register.html.twig)
        break;

    case '/form/login': // Si le chemin d'URL de PATH_INFO est égal à "http://localhost/form/login"
        (new UserController())->login(); // Alors je crée un objet UserController() qui appelle la méthode login() afin charger la vue et les données de cette méthode (ici le fichier login.html.twig)
        break;

    case '/logout':
        (new UserController())->logout();
        break;

    case '/home':
        (new TaskController())->index();
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

    case '/deleteAllTasks':
        (new TaskController())->deleteAllTasks();
        break;
        
    default:
        echo "Page introuvable.";
        break;
}
