<?php

require_once "src/config/connection.php";

// Je déclare une variable $query (requête) qui va récupérer tout ce qui se trouve dans la table "catégories"
$query = $connection->prepare("SELECT * FROM priorities ORDER BY priority_id ASC");
$query->execute();

// Je stocke chaque données récupérés par la requête dans une variable nommée "priority"
// Pour chaque donnée récupérée par cette requête, je créé un champ "option" qui contient l'id de cette donnée ainsi que son nom
while($priority = $query->fetch()) {
?>
    <!--
    L'attribut value doit contenir l'id de la priorité sélectionnée car c'est la valeur qui est envoyée au formulaire lors
    de son envoi
    
    Explication MDN : Le contenu de cet attribut représente la valeur qu'on souhaite envoyer au formulaire lorsque l'option
    est sélectionnée. Si cet attribut n'est pas défini, la valeur sera le contenu texuel de l'élément <option>.
    
    Je garde quand même l'id car cela me sera utile pour la partie JS
    -->
    <option value="<?= $priority['priority_id'] ?>" id="<?= $priority['priority_id'] ?>"><?= $priority['priority_name'] ?></option>
<?php } ?>