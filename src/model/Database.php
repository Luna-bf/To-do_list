<?php

/*
- Je renomme la classe en src\model, ça sert à éviter les conflits en cas d'importation d'une librairie qui utilise aussi
"new Database" par exemple.

Note : le namespace indique où se situe la classe dans la structure des dossiers, je n'ajoute le nom de fichier que lorsque
j'ai besoin d'accéder à la classe : ici j'indique que la classe se trouve dans le dossier "model", un sous-dossier du dossier
"src". Ajouter "Database" signifierait que "Database" est un sous-dossier de "model", ce qui n'est pas le cas ici.
*/

namespace src\model;

use InvalidArgumentException;
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

    /**
     * Création d'un utilisateur (inscription)
     *
     * @return void
     */
    public function registration($email, $username, $password)
    {
        $statement = $this->pdo->prepare('INSERT INTO users(email, username, password) VALUES(:email, :username, :password)');
        return $statement->execute([':email' => $email, ':username' => $username, ':password' =>  $password]);
    }

    public function checkIfEmailExists($email)
    {
        $statement = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $statement->execute([':email' => $email]);
        return $statement->rowCount(); // Va compter le nombre de ligne où l'email donné apparait
    }

    public function login($email)
    {
        /*Ma requête SQL : je vais récupérer toutes les données de l'utilisateur dont le nom d'utilisateur correspondent à la
        valeur saisie dans le formulaire */
        $statement = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");

        /*
        Ici l'erreur "SQLSTATE[HY093]: Invalid parameter number: parameter was not defined" survenait car je donnais des
        paramètres nommés dans ma requête SQL mais que je les passais à execute() sous forme de tableau non associatif :
        $statement->execute([$email, $password]);

        Lorsque j'utilise des paramètres nommés, il est obligatoire de fournir un tableau associatif afin d'associer chaque
        paramètre nommé à sa valeur (code ci-dessous).
        */
        $statement->execute([":email" => $email]);
        return $statement;
    }

    // Raccourci pour avoir ce type de commentaire : / + ** + Entrée
    /**
     * Récupère toutes les lignes (enregistrements) de la table en fonction de l'id d'un utilisateur précis
     *
     * @return array : Cette méthode retourne un tableau
     */
    public function findUserTasks($user_id, $table)
    {
        $allowedTables = ['tasks'];
        if (!in_array($table, $allowedTables, true)) {
            throw new InvalidArgumentException("Nom de table non autorisé : $table");
        }

        $statement = $this->pdo->prepare(
            "SELECT t.*, c.category_name, p.priority_id
            FROM $table as t
                JOIN categories as c on t.category_id = c.category_id
                JOIN priorities as p on t.priority_id = p.priority_id
            WHERE t.user_id = :user_id
            ORDER BY p.priority_id ASC"
        );

        $statement->execute(['user_id' => $user_id]); // Exécute la requête préparée puis renvoie un résultat true ou false
        return $statement->fetchAll(PDO::FETCH_ASSOC); // Récupère les données de la requête
    }

    /**
     * Récupère une ligne (enregistrement) de la table
     *
     * @param int $id : l'identifiant de la ligne que je veux récupérer
     * @return : Cette méthode retourne un tableau associatif si la donnée existe, ou false si elle n'existe pas
     */
    public function findTask($task_id)
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
        $statement = $this->pdo->prepare("SELECT * FROM tasks WHERE task_id = :task_id"); // $this fait référence à l'objet actuel (ici, Database)
        return $statement->execute(["task_id" => $task_id]);
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
    public function createTask($user_id, $category, $priority, $task_name)
    {
        $task = $this->pdo->prepare("INSERT INTO tasks(user_id, category_id, priority_id, task_name) VALUES(:user_id, :category_id, :priority_id, :task_name)");
        return $task->execute(['user_id' => $user_id, 'category_id' => $category, 'priority_id' => $priority, 'task_name' => $task_name]);
    }

    public function updateTask($category, $priority, $task_name, $task_id)
    {
        $task = $this->pdo->prepare("UPDATE tasks SET category_id = :category_id, priority_id = :priority_id, task_name = :task_name WHERE task_id = :task_id");
        $task->execute(['category_id' => $category, 'priority_id' => $priority, 'task_name' => $task_name, 'task_id' => $task_id]);
        
        return $task->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteTask($task_id)
    {
        $task = $this->pdo->prepare("DELETE FROM tasks WHERE task_id = :task_id"); // $this fait référence à l'objet actuel (ici, Database)
        return $task->execute(["task_id" => $task_id]);
    }
}
