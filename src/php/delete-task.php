<?php

require_once "../config/connection.php";

if(isset($_POST['delete'])) {

    // echo "<pre>";
    // print_r($_POST['delete']);
    
    /*
    La variable superglobale $_POST est un tableau qui contient les requêtes HTTP passées par le navigateur, la condition
    if(isset($_POST['delete'])) va donc chercher un élément nommé "delete" afin de pouvoir effectuer l'action demandée ci-dessous.

    Comme la valeur recherchée est l'identifiant de la tâche, je déclare une variable nommée $task_id qui va rechercher la valeur
    "Supprimer" dans le tableau $_POST['delete'] et retourner la clé correspondante à l'aide de la méthode array_search().

    Pour rappel : array_search("value", array) recherche une valeur (ici "Supprimer") dans un tableau et retourne sa clé (ici $task_id).

    Si je clique sur la première tâche de la liste, la clé retournée par $task_id sera "1", ce qui me donne :
    
    Array ($_POST['delete'])
    (
        [1] => "Supprimer" // La clé numéro 1 obtient la valeur "Supprimer"
    )

    La tâche portant l'id numéro 1 sera donc la tâche à supprimer.
    */
    $task_id = array_search("Supprimer", $_GET['delete']);

    $query = $connection->prepare("DELETE FROM tasks WHERE task_id = :task_id");
    $result = $query->execute(['task_id' => $task_id]);

    if(!$result) {
        echo "<h1>Une erreur est survenue : La suppression n'a pas pu être effectuée.<h1>";
    } else {
        header('Location: ../../index.php');
    }
}