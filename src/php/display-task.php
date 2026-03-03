<?php

// Installer Twig puis implémenter l'architecture MVC ?
require_once "src/config/connection.php";

// Je déclare une variable $query (requête) qui va récupérer tout ce qui se trouve dans la table "tasks"
$query = $connection->prepare("SELECT t.*, c.category_name, p.priority_id FROM tasks as t JOIN categories as c on t.category_id = c.category_id JOIN priorities as p on t.priority_id = p.priority_id ORDER BY p.priority_id ASC");
$query->execute();

// Pour chaque donnée récupérée par cette requête, je créé un champ "li" qui contient l'id de cette donnée ainsi que son nom
while ($task = $query->fetch()) {
?>
    <li class="d-flex task">
        <input type="checkbox" name="<?= $task['task_id'] ?>" id="<?= $task['task_id'] ?>" />
        <label for="<?= $task['task_id'] ?>" class="priority-<?= $task['priority_id'] ?>"><?= $task['task_name'] ?></label>
        <span class="category" id="<?= $task['category_id'] ?>">(<?= $task['category_name'] ?>)</span>
        <div>
            <form action="php/update-task.php" method="post">
                <input type="submit" name="modifier[<?php echo $task['task_id'] ?>]" class="update" id="update" value="Renommer" onclick="return confirm('Voulez-vous renommer cette tâche ?')" />
            </form>
        </div>
        <div>
            <form action="src/php/delete-task.php" method="get">
                <input type="submit" name="delete[<?php echo $task['task_id'] ?>]" class="delete" id="delete" value="Supprimer" />
            </form>
        </div>
    </li>

    <!-- <div>
        <pre>
        <p><php echo print_r($task) ?></p>
    </div> -->
    <!--
    $_POST['delete'] = [
        $task_id = "Supprimer"
    ]
    -->
<?php
}

if (!$task) {
    $message = "Vous n'avez aucune tâche.";
} else {
    $message = "";
}
?>