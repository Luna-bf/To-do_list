<?php

/* Permet de démarrer une session ou de reprendre une session existente en utilisant les données de la DB, cette méthode doit
être présente au début de toutes les pages : avant le code PHP et les balises HTML. */
session_start();

require_once("../../db/db_connect.php"); // J'appelle le fichier contenant la connexion à la base de données :

if (isset($_POST['connexion'])) { // Lorsque l'élément nommé "connexion" est cliqué...

    // Je récupère les différents champs de saisie du formulaire et les stockent dans des variables dédiées :
    $nom_utilisateur = htmlspecialchars(trim($_POST['nom_utilisateur'])); // Champ de saisie nommé "nom_utilisateur"
    $mot_de_passe = htmlspecialchars(trim($_POST['mot_de_passe'])); // Champ de saisie nommé "mot de passe"

    try {
        /*Ma requête SQL : je vais récupérer toutes les données de l'utilisateur dont le nom d'utilisateur correspondent à la valeur
        saisie dans le formulaire */
        $validation = $connexion->prepare("SELECT * FROM utilisateurs WHERE nom_utilisateur = :nom_utilisateur");

        /*
        Ici l'erreur "SQLSTATE[HY093]: Invalid parameter number: parameter was not defined" survenait car je donnais des paramètres 
        nommés dans ma requête SQL mais que je les passais à execute() sous forme de tableau non associatif : $validation->execute([$nom_utilisateur, $mot_de_passe]);

        Lorsque j'utilise des paramètres nommés, il est obligatoire de fournir un tableau associatif afin d'associer chaque
        paramètre nommé à sa valeur (code ci-dessous).
        */
        $validation->execute([":nom_utilisateur" => $nom_utilisateur]);

        /* La méthode fetch() permet de récupérer une ligne associée à une ou plusieurs valeurs, ici, je veux qu'elle récupère
        une ligne associée à la requête SQL déclarée dans la variable $validation. */
        if ($ligne_table = $validation->fetch()) {

            if ($ligne_table && password_verify($mot_de_passe, $ligne_table['mot_de_passe'])) { // Si la requête de la variable $validation récupère une ligne associée au nom d'utilisateur et au mot de passe...
                /* Je stocke l'id de l'utilisateur dans la session en assignant le paramètre nommé "id_utilisateur" à la colonne
                "id_utilisateur" que l'appel fetch() contenu dans la variable $ligne_table aura récupéré. Cela me permet d'avoir
                un identifiant parfaitement unique pour les sessions au cas où deux personnes obtiennent le même nom d'utilisateur par erreur. */
                $_SESSION['id_utilisateur'] = $ligne_table['id_utilisateur'];
                $_SESSION['nom_utilisateur'] = $_POST['nom_utilisateur']; // Je stocke aussi le nom de l'utilisateur trouvé par fetch() dans la session
                header('Location: ../../pages/connected.php'); // Puis je redirige l'utilisateur vers une autre page.
                exit(); // Cette fonction permet de fermer le script qui exécute le formulaire, cela empêche le formulaire d'être envoyé de nouveau lorsque je rafraichi la page

            } else { // Sinon j'affiche un message d'erreur
                $formError = "Ces informations ne correspondent à aucun profil.";
            }
        } else {
            $formError = 'Aucune ligne n\'a été récupérée.';
        }
    } catch (PDOException $error) {

        $message = $error->getMessage();
    }
}

?>