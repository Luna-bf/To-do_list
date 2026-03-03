<?php

require_once "../config/connection.php";

// Je déclare une variable $query (requête) qui va récupérer tout ce qui se trouve dans la table "catégories"
$query = $connection->prepare("SELECT * FROM tasks ORDER BY task_id ASC");
$query->execute();

if(isset($_POST['update'])) {

    $task_id = array_search("Modifier", $_POST['update']);
    // $task_name =

    // UPDATE produits SET stock = 45, prix = 95.00 WHERE nom = 'Clavier mécanique';

    // function prompt($prompt_msg){
    //     echo("<script type='text/javascript'> var answer = prompt('".$prompt_msg."'); </script>");

        
    // $task_name = "<script type='text/javascript'> document.write(answer); </script>";
    //     return($task_name);
    // }

    // //program
    // $prompt_msg = "Please type your name.";
    // $name = prompt($prompt_msg);

    // $output_msg = "Hello there ".$name."!";
    // echo($output_msg);

    $query = $connection->prepare("UPDATE tasks SET task_name = :task_name WHERE task_id = :task_id");
    $result = $query->execute(['task_name' => $task_name, 'task_id' => $task_id]);

    // echo 'ok';

    if(!$result) {
        echo "<h1>Une erreur est survenue : La suppression n'a pas pu être effectuée.<h1>";
    } else {
        header('Location: ../index.php');
    }
}

?>