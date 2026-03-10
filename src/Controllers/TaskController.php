<?php

namespace src\controllers;

session_start();

use Exception;
use src\controllers\UserController;

// La classe TaskController hérite de la classe BaseController : elle récupère ses propriétés et méthodes
class TaskController extends BaseController
{

    // La méthode render() permet d'appeler un fichier spécifique grâce à son chemin d'accès et de lui transmettre des données (voir BaseController.php)
    // Cette méthode va appeler les méthodes de la classe Database nécessaires à l'affichage des tâches
    public function index()
    {
        // J'initialise le token CSRF dans la méthode qui affiche toutes les tâches, pour que toutes les tâches ai un token CSRF
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(128));
        }

        // Initialisation des variables (comme ça elles restent accessible en dehors de la condition if)
        $username = null; // Par défaut, username est égal à null (aucune valeur)
        $user_id = null; // Par défaut, user_id est égal à null (aucune valeur)
        $table = "";
        $csrf_token = $_SESSION['csrf_token'];
        $tasks = []; // tasks sera un tableau associatif, pareil pour $categories et $priorities
        $categories = [];
        $priorities = [];
        $message = "";

        if (isset($_SESSION['user_id'])) {

            $username = $_SESSION['username']; // Récupère le nom de l'utilisateur pour l'afficher dans la page
            $user_id = $_SESSION['user_id']; // Récupère l'identifiant de l'utilisateur pour l'afficher dans la page
            
            $csrf_token = $_SESSION['csrf_token'];
            $tasks = $this->db->findUserTasks($user_id, 'tasks'); // Je récupère toutes les données de la table tasks grâce à la méthode findUserTasks et les stocke dans un tableau "tasks"
            $categories = $this->db->getCategories('categories');
            $priorities = $this->db->getPriorities('priorities');

            if (empty($tasks)) {
                $message = "Vous n'avez aucune tâche.";
            }
        }

        $this->render('home/index.html.twig', ['csrf_token' => $csrf_token, 'tasks' => $tasks, 'categories' => $categories, 'priorities' => $priorities, 'message' => $message, 'username' => $username, 'user_id' => $user_id]);
    }

    public function createTask()
    {
        /*
        Je récupère les catégories et les priorités pour les afficher en tant qu'option dans le formulaire grâce aux méthodes
        définies dans le modèle (Database.php)
        */
        $user_id = $_SESSION['user_id'];
        $username = $_SESSION['username'];
        $categories = $this->db->getCategories('categories');
        $priorities = $this->db->getPriorities('priorities');

        // Je récupère les valeurs saisies dans le formulaire
        $task = $this->db->createTask('user_id', 'category', 'priority', 'task_name');

        if ($task) {
            header('Location: /'); // Je redirige vers la page d'accueil (la racine, soit : '/')
            exit;
        }

        // Je passe les catégories, les priorités et la tâche que je créé en tant que valeurs à la page
        $this->render('home/task/createTask.html.twig', ['user_id' => $user_id, 'username' => $username, 'categories' => $categories, 'priorities' => $priorities, 'task' => $task]);
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

        // Je passe les catégories, les priorités et la tâche modifiée en tant que valeurs à la page
        $this->render('home/task/updateTask.html.twig', ['categories' => $categories, 'priorities' => $priorities, 'task' => $task]);
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
                $message = "Une erreur est survenue : La suppression n'a pas pu être effectuée.";
            } else {
                header('Location: /'); // Je redirige vers la page d'accueil (la racine, soit : '/')
                exit;
            }
        }
    }
}
