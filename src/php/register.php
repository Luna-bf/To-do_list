<?php

/* Pour le formulaire :
    - Best way to avoid the submit due to a refresh of the page
    - prevent form from sending data to the database after f5 reload php
    - https://www.superiorwebsys.com/48-how-to-stop-form-from-submitting-with-page-refresh-using-php/
*/

// J'appelle le fichier contenant la connexion à la base de données
require_once "../../db/db_connect.php";

/*
    Ici je cherche à savoir si l'utilisateur à validé le formulaire en cliquant sur un bouton spécifique : j'utilise donc la
    méthode isset() qui me permet de savoir si une variable est déclarée ou non. Dans la méthode isset(), je déclare la
    variable $_POST : celle-ci va permettre au serveur de récupérer les données de mon formulaire puis de les analyser avant
    de les enregistrer dans le corps de la requête HTTP.

    TL;DR : Si l'utilisateur a cliqué sur le bouton nommé "enregistrer" pour valider l'envoi du formulaire, alors la requête
    post enregistrera les données dans le corps de la requête HTTP.
*/
if (isset($_POST['inscription'])) { // Lorsque l'élément nommé "inscription" est cliqué...

    // Je récupère les différents champs de saisie du formulaire et les stockent dans des variables dédiées
    $email = htmlspecialchars(trim($_POST['email'])); // Champ de saisie nommé "email"
    $nom_utilisateur = htmlspecialchars(trim($_POST['username'])); // Champ de saisie nommé "nom_utilisateur"
    $mot_de_passe = htmlspecialchars(trim($_POST['password'])); // etc...

    /*
        Je vérifie que tous les champs du formulaire ne soient pas vides en utilisant la méthode empty(), utilisée pour
        vérifier si une variable est vide ou égale à FALSE :
    */
    if (!empty($email) && !empty($nom_utilisateur) && !empty($mot_de_passe)) { // Si tous les champs du formulaire ne sont pas vide, alors...

        $hash_mdp = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        /*
            Je créé une variable qui va contenir la requête SQL qui sera effectuée lors de l'envoi du formulaire : celle-ci
            contient la variable de connexion à la base de données, ainsi que la méthode prepare().

            prepare() permet de créer le schéma d'une requête SQL : la requête n'est pas directement utilisable car les valeurs
            réelles ne sont pas encore précisées. Pour le moment on utilise les "paramètres nommés", qui seront ensuite
            remplacés par les vraies valeurs. Cela est très utile pour les appels répétitifs (même requête) avec des
            valeurs différentes.
            
            Puis j'utilise la méthode bindvalue() précédée de la variable contenant la connexion à la base de données :
            cela aura pour effet de lier la valeur de la variable $nom (par exemple) au paramètre nommé ":nom". Par
            exemple si la valeur de la variable $nom_utilisateur est "John", alors la valeur du paramètre nommé sera "John".
            En remplissant le formulaire, la requête SQL serait : INSERT INTO utilisateurs(email, nom_utilisateur, mot_de_passe) VALUES(john.doe@gmail.com, John, JohnDoe123!);
            
            Ensuite, je créé une nouvelle variable qui va exécuter le contenu de la variable $requete : pour cela j'utilise la
            méthode execute(), qui va exécuter le contenu de la méthode prepare().

            Enfin, je vérifie que la requête a bien été exécutée avec une condition if-else : si la variable $resultat renvoie
            false, cela signifie que la requête a été mal exécutée, j'affiche donc un message pour signaler le problème. Sinon,
            si la requête renvoie true, j'affiche un message de succès.

            Ce que je fais avec INSERT peut être fait avec DELETE, UPDATE, etc...
        */
        $requete = $connexion->prepare('INSERT INTO utilisateurs(email, nom_utilisateur, mot_de_passe) VALUES(:email, :nom_utilisateur, :mot_de_passe)');
        $resultat = $requete->execute([':email' => $email, ':nom_utilisateur' => $nom_utilisateur, ':mot_de_passe' =>  $hash_mdp]);

        if (!$resultat) { // Si le contenu de la variable $resultat retourne false, alors...
            $message = "Une erreur est survenue, l'enregistrement n'a pas pu être effectué.";
        } else {
            header('Location: signin.php');
            exit(); //Cette fonction permet de fermer le script qui exécute le formulaire, cela empêche le formulaire d'être envoyé de nouveau lorsque je rafraichi la page
        }
    } else {
        $message = "Tous les champs de saisie doivent être remplis.";
    }
}

?>