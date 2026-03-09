<?php

namespace src\controllers;

session_start();

use Exception;

// La classe HomeController hérite de la classe BaseController : elle récupère ses propriétés et méthodes
class HomeController extends BaseController
{

    // La méthode render() permet d'appeler un fichier spécifique grâce à son chemin d'accès et de lui transmettre des données (voir BaseController.php)
    // Cette méthode va appeler les méthodes de la classe Database nécessaires à l'affichage des tâches
    public function index()
    {
        // J'initialise le token CSRF dans la méthode qui affiche toutes les tâches, pour que toutes les tâches ai un token CSRF
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(128));
        }

        /*
        J'assigne le chemin d'accès au fichier à la variable $path et lui transmet des données grâce à la variable $data : $path
        renvoie le fichier "index.html.twig" et $data renvoie une variable $sentence qui contient une chaîne de caractères que je
        vais pouvoir afficher dans la page.
        */
        $csrf_token = $_SESSION['csrf_token'];
        $tasks = $this->db->findAllTasks('tasks'); // Je récupère toutes les données de la table tasks grâce à la méthode findAllTasks et les stockes dans un tableau "tasks"
        $categories = $this->db->getCategories('categories');
        $priorities = $this->db->getPriorities('priorities');

        $this->render('home/index.html.twig', ['csrf_token' => $csrf_token, 'tasks' => $tasks, 'categories' => $categories, 'priorities' => $priorities]);
    }

    // Cette méthode va appeler la méthode de la classe Database nécessaire à la création d'une tâche
    public function createTask()
    {
        // Je récupère les catégories et les priorités pour les afficher en tant qu'option dans le formulaire
        $categories = $this->db->getCategories('categories');
        $priorities = $this->db->getPriorities('priorities');

        // Je récupère les valeurs saisies dans le formulaire
        $task = $this->db->createTask('category', 'priority', 'task_name');

        if ($task) {
            header('Location: /'); // Je redirige vers la page d'accueil (la racine, soit : '/')
            exit;
        }

        // Je passe les catégories, les priorités et la tâche que je créé en tant que valeurs à la page
        $this->render('form/createTask.html.twig', ['categories' => $categories, 'priorities' => $priorities, 'task' => $task]);
    }

    public function updateTask()
    {
        // Je récupère les catégories et les priorités pour les afficher en tant qu'option dans le formulaire
        $task = $this->db->findTask('task_id');
        $categories = $this->db->getCategories('categories');
        $priorities = $this->db->getPriorities('priorities');

        // Je récupère les valeurs saisies dans le formulaire
        $task = $this->db->updateTask('category', 'priority', 'task_name');
        // $updated_task = $this->db->updateTask('category', 'priority', 'task_name');

        if ($task) {
            header('Location: /'); // Je redirige vers la page d'accueil (la racine, soit : '/')
            exit;
        }

        // Je passe les catégories, les priorités et la tâche que je créé en tant que valeurs à la page
        $this->render('form/updateTask.html.twig', ['categories' => $categories, 'priorities' => $priorities, 'task' => $task]);
    }

    public function deleteTask()
    {
        // Si le token n'existe pas OU qu'il existe mais qu'il ne correspond pas au token créé par la session...
        if (!isset($_POST['csrf_token']) || ($_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
            throw new Exception("Le token CSRF est invalide.");
        } else {
            // Je récupère l'id de la tâche à supprimer
            $task_id = $_POST['task_id'];

            // J'apelle la méthode deleteTask pour qu'elle supprime cette tâche du tableau "tasks"
            $statement = $this->db->deleteTask($task_id, 'tasks');

            if (!$statement) {
                echo "<h1>Une erreur est survenue : La suppression n'a pas pu être effectuée.<h1>";
            } else {
                header('Location: /'); // Je redirige vers la page d'accueil (la racine, soit : '/')
                exit;
            }
        }
    }
}
