<?php

namespace src\controllers;

session_start();

use Exception;

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
        $csrf_token = $_SESSION['csrf_token']; // Je récupère le token CSRF
        $tasks = []; // tasks sera un tableau associatif, pareil pour $categories et $priorities
        $categories = [];
        $priorities = [];
        $message = "";
        $class = "";

        if (isset($_SESSION['user_id'])) {

            $username = $_SESSION['username']; // Récupère le nom de l'utilisateur pour l'afficher dans la page
            $user_id = $_SESSION['user_id']; // Récupère l'identifiant de l'utilisateur pour l'afficher dans la page
            $csrf_token = $_SESSION['csrf_token'];
            $tasks = $this->db->findUserTasks($user_id, 'tasks'); // Je récupère toutes les données de la table tasks grâce à la méthode findUserTasks et les stocke dans un tableau "tasks"
            $categories = $this->db->getCategories('categories');
            $priorities = $this->db->getPriorities('priorities');

            if (count($tasks) >= 5) {
                $class = "footer-dynamic";
            } else {
                $class = "footer-absolute";
            }

            if (empty($tasks)) {
                $message = "Vous n'avez aucune tâche.";
            }
        }
        
        $this->render('home/index.html.twig', ['csrf_token' => $csrf_token, 'tasks' => $tasks, 'categories' => $categories, 'priorities' => $priorities, 'message' => $message, 'username' => $username, 'user_id' => $user_id, 'class' => $class]);
    }

    public function createTask()
    {
        /*
        Je récupère :
        - Le nom de l'utilisateur et son identifiant unique dans la session
        - Toutes les catégories et priorités depuis la base de données grâce aux méthodes définies dans le modèle
        */
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];
        $categories = $this->db->getCategories('categories');
        $priorities = $this->db->getPriorities('priorities');
        $message = "";

        if (isset($_POST['add'])) {

            // Je récupère les valeurs du formulaire
            $task_name = $_POST['task_name'];
            $category = $_POST['category'];
            $priority = $_POST['priority'];

            // Je vérifie que tous les champs du formulaire sont remplis
            if (!empty($category) && !empty($priority) && !empty($task_name)) {

                // Je crée la tâche grâce aux valeurs saisies dans le formulaire
                $task = $this->db->createTask($user_id, $category, $priority, $task_name);

                // Si la tâche a bien été créée, je redirige vers la page d'accueil
                if ($task) {
                    header('Location: /home'); // Je redirige vers la page home
                    exit;
                }
            } else {
                $message = "Tous les champs doivent être remplis.";
            }
        }

        // Je passe les catégories, les priorités et la tâche que je créé en tant que valeurs à la page
        $this->render('home/task/createTask.html.twig', ['user_id' => $user_id, 'username' => $username, 'categories' => $categories, 'priorities' => $priorities, 'category' => $category, 'priority' => $priority, 'task' => $task, 'message' => $message]);
    }

    public function updateTask()
    {
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];
        $task_id = $_GET['id'];
        $is_complete = $_POST['is_complete'];
        $updatedTask = null;
        $message = "";
        
        if (isset($task_id)) {

            $categories = $this->db->getCategories('categories');
            $priorities = $this->db->getPriorities('priorities');
            $task = $this->db->findTask($task_id, 'tasks');

            if (isset($_POST['update'])) {

                $category = $_POST['category'];
                $priority = $_POST['priority'];
                $task_name = $_POST['task_name'];
                $is_complete = isset($_POST['is_complete']) ? 1 : 0; // J'utilise l'opérateur ternaire : si is_complete existe (coché) alors il est égal à 1, sinon s'il est égal à zéro (pas coché) alors il est égal à zéro

                if (!empty($category) && !empty($priority) && !empty($task_name)) {
                    
                    // Je récupère les valeurs saisies dans le formulaire puis je met à jour la tâche
                    $updatedTask = $this->db->updateTask($category, $priority, $task_name, $is_complete, $task_id);

                    if ($updatedTask) {
                        header('Location: /home'); // Je redirige vers la page home
                        exit;
                    }
                } else {
                    $message = "Tous les champs doivent être remplis.";
                }
            }
        } else {
            throw new Exception("Une erreur est survenue, la modification n'a pas pu être effectuée.");
        }

        // Je passe les catégories, les priorités et la tâche modifiée en tant que valeurs à la page
        $this->render('home/task/updateTask.html.twig', ['user_id' => $user_id, 'username' => $username, 'categories' => $categories, 'priorities' => $priorities, 'task' => $task, 'updatedTask' => $updatedTask, 'message' => $message]);
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
                throw new Exception("Une erreur est survenue : La suppression n'a pas pu être effectuée.");
            } else {
                header('Location: /home'); // Je redirige vers la page home
                exit;
            }
        }
    }

    public function deleteAllTasks()
    {

        $user_id = $_SESSION['user_id'];
        $tasks = $this->db->findUserTasks($user_id, 'tasks');

        // Je récupère de l'id de l'utilisateur associé à la tâche afin de comparer avec celui présent dans la session actuelle
        foreach ($tasks as $task) {

            // Je compare l'id de l'utilisateur présent dans chaque tâche afin de le comparer avec celui présent dans la session actuelle
            if ($task['user_id'] === $user_id) {
                if (!isset($_POST['csrf_token']) || ($_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
                    throw new Exception("Le token CSRF est invalide.");
                } else {
                    // J'apelle la méthode deleteTask pour qu'elle supprime cette tâche du tableau "tasks"
                    $statement = $this->db->deleteAllTasks($user_id, 'tasks');

                    if (!$statement) {
                        throw new Exception("Une erreur est survenue : La suppression n'a pas pu être effectuée.");
                    } else {
                        header('Location: /home'); // Je redirige vers la page home
                        exit;
                    }
                }
            } else {
                throw new Exception("Une erreur est survenue : La suppression n'a pas pu être effectuée.");
            }
        }
    }
}
