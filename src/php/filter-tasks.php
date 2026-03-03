<?php

require_once "src/config/connection.php";

if (isset($_POST['display'])) {

    echo 'ok';

    $filterCategory = $_POST['filter-category'];

    $query = $connection->prepare("SELECT t.task_id, c.category_id, p.priority_id, t.task_name, c.category_name, p.priority_name, t.is_complete FROM tasks as t JOIN categories as c on t.category_id = c.category_id JOIN priorities as p on t.priority_id = p.priority_id WHERE c.category_name = :category_name");
    $result = $query->execute(['category_name' => $filterCategory]);

    if (!$result) {
        echo "Une erreur est survenue : le tri n'a pas pu être effectuée.";
    } else {
        while ($result = $query->fetch()) {
?>
            <li class="d-flex">
                <input type="checkbox" name="<?= $task['task_id'] ?>" id="<?= $task['task_id'] ?>" />
                <label for="<?= $task['task_id'] ?>" class="priority-<?= $task['priority_id'] ?>"><?= $task['task_name'] ?></label>
                <span class="category" id="<?= $task['category_id'] ?>">(<?= $task['category_name'] ?>)</span>
                <div>
                    <form action="php/update-task.php" method="post">
                        <input type="submit" name="modifier[<?php echo $task['task_id'] ?>]" class="update" id="update" value="Renommer" onclick="return confirm('Voulez-vous renommer cette tâche ?')" />
                    </form>
                </div>
                <div>
                    <form action="src/php/delete-task.php" method="post">
                        <!-- onclick="return confirm('Êtes-vous sûr(e) de vouloir supprimer cette tâche ?')" -->
                        <input type="submit" name="delete[<?php echo $task['task_id'] ?>]" class="delete" id="delete" value="Supprimer" />
                    </form>
                </div>
            </li>
<?php
        }
    }
}

?>