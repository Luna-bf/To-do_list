<?php

namespace src\controllers;

// session_start();

use Exception;

class UserController extends BaseController {

    public function register() {
        if (isset($_POST['register'])) { // Lorsque l'élément nommé "inscription" est cliqué...

            // Je récupère les valeurs des différents champs de saisie du formulaire
            $email = htmlspecialchars(trim($_POST['email'])); // Champ de saisie nommé "email"
            $username = htmlspecialchars(trim($_POST['username'])); // Champ de saisie nommé "username"
            $password = htmlspecialchars(trim($_POST['password'])); // etc...

            /*
            Je vérifie que tous les champs du formulaire ne sont pas vides en utilisant la méthode empty(), utilisée pour
            vérifier si une variable est vide ou égale à FALSE :
            */
            if (!empty($email) && !empty($username) && !empty($password)) { // Si tous les champs du formulaire ne sont pas vide, alors...

                // Je hache (hash) le mot de passe
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Puis je créé l'utilisateur
                $user = $this->db->createUser($email, $username, $hashed_password);

                if (!$user) { // Si le contenu de la variable $resultat retourne false, alors...
                    $message = "Une erreur est survenue, l'inscription n'a pas pu être effectuée.";
                } else {
                    header('Location: /form/login');
                    exit;
                }
            } else {
                $message = "Tous les champs de saisie doivent être remplis.";
            }
        }

        $this->render('form/register.html.twig', ['email' => $email, 'username' => $username, 'password' => $hashed_password, 'message' => $message]);
    }

    public function login() {
        // session_start();

        $this->render('form/login.html.twig', ["str" => "Re bonjour le monde !"]);
    }
}