<?php

/*
- Je renomme la classe en src\model, ça sert à éviter les conflits en cas d'importation d'une librairie qui utilise aussi
"new Database" par exemple.

Note : le namespace indique où se situe la classe dans la structure des dossiers, je n'ajoute le nom de fichier que lorsque
j'ai besoin d'accéder à la classe : ici j'indique que la classe se trouve dans le dossier "model", un sous-dossier du dossier
"src". Ajouter "Database" signifierait que "Database" est un sous-dossier de "model", ce qui n'est pas le cas ici.
*/

namespace src\model;

use Exception;
use PDO; // Importation de la classe "PDO", qui va me permettre de communiquer avec la BDD

// Cette classe va superviser toutes les opérations liées à la base de données
class Database
{
    // Déclaration des propriétés qui vont constituer la connexion à la base de données
    private $pdo;
    private $host = 'localhost';
    private $db_name = 'todolist_db';
    private $user = 'root';
    private $password = '';

    public function __construct()
    {
        // Instance mise à la ligne pour qu'elle soit plus lisible
        $this->pdo = new PDO(
            "mysql:host=$this->host;dbname=$this->db_name",
            $this->user,
            $this->password
        );
    }
    
    // Raccourci pour avoir ce type de commentaire : / + ** + Entrée
    /**
     * Renvoie toutes les lignes (enregistrements) de la table
     *
     * @return array : Cette méthode retourne un tableau
     */
    public function findAllTasks()
    {
        return $this->pdo->query("SELECT t.*, c.category_name, p.priority_id FROM tasks as t JOIN categories as c on t.category_id = c.category_id JOIN priorities as p on t.priority_id = p.priority_id ORDER BY p.priority_id ASC");
    }

    /**
     * Récupère une ligne (enregistrement) de la table
     *
     * @param int $id : l'identifiant de la ligne que je veux récupérer
     * @return : Cette méthode retourne un tableau associatif si la donnée existe, ou false si elle n'existe pas
     */
    public function findTask($id)
    {
        /*
        - Je prépare une requête qui va récupérer une seule ligne de la table grâce à un identifiant donné,
        - J'exécute la requête en passant l'identifiant récupéré en tant que paramètre dans la requête,
        - Puis je renvoie la ligne récupérée grâce à la méthode "fetch()" de PDO.
        
        Note : FETCH_ASSOC signifie que le résultat sera renvoyé sous forme de tableau associatif, soit :
        $task = [
            "task_id" => 12,
            "category_id" => 3,
            "priority_id" => 2,
            "task_name" => "Faire les courses",
            "is_complete" => 0,
        ];
        */
        $statement = $this->pdo->prepare("SELECT * FROM tasks WHERE id = :id"); // $this fait référence à l'objet actuel (ici, Database)
        $statement->execute(["id" => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getCategories()
    {
        // Revoir à quoi sert cette requête et pourquoi je la déclare comme ça
        return $this->pdo->query("SELECT * FROM categories ORDER BY category_id ASC");
    }

    public function getPriorities()
    {
        // Revoir à quoi sert cette requête et pourquoi je la déclare comme ça
        return $this->pdo->query("SELECT * FROM priorities ORDER BY priority_id ASC");
    }

    // Les autres méthodes qui vont me permettre de manipuler les données de la BDD
    // Cette méthode doit exécuter le code nécessaire à la suppression des données
    public function createTask($category, $priority, $task_name)
    {
        if (isset($_POST['add'])) {

            if (!empty($_POST['category']) && !empty($_POST['priority']) && !empty($_POST['task_name'])) {

                $category = $_POST['category'];
                $priority = $_POST['priority'];
                $task_name = $_POST['task_name'];

                $task = $this->pdo->prepare("INSERT INTO tasks(category_id, priority_id, task_name) VALUES(:category_id, :priority_id, :task_name)");
                return $task->execute(['category_id' => $category, 'priority_id' => $priority, 'task_name' => $task_name]);
            }
        }
    }

    public function updateTask($id, $data) {}

    public function deleteTask($task_id)
    {
        $task = $this->pdo->prepare("DELETE FROM tasks WHERE task_id = :task_id"); // $this fait référence à l'objet actuel (ici, Database)
        return $task->execute(["task_id" => $task_id]);
    }
}
