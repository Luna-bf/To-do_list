<?php

require_once "src/config/connection.php";

if(isset($_POST['add'])) {

    // echo 'ok';

    if(!empty($_POST['name']) && !empty($_POST['category']) && !empty($_POST['priority'])) {

        $name = $_POST['name'];
        $category = $_POST['category'];
        $priority = $_POST['priority'];

        $query = $connection->prepare("INSERT INTO tasks(category_id, priority_id, task_name) VALUES(:category_id, :priority_id, :task_name)");
        $result = $query->execute(['category_id' => $category, 'priority_id' => $priority, 'task_name' => $name]);

        if(!$result) {
            echo "Une erreur est survenue : l'ajout de la tâche n'a pas pu être effectuée.";
        }
    }
}