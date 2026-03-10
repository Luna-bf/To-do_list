<?php

namespace src\controllers;

session_start();

use Exception;

class UserController extends BaseController
{

    public function register()
    {
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

                $result = $this->db->checkIfEmailExists($email);

                if ($result > 0) {
                    $message = "Cette adresse mail est déjà associée à un compte.";
                } else {
                    // Je hache (hash) le mot de passe
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Puis je créé l'utilisateur
                    $user = $this->db->registration($email, $username, $hashed_password);

                    if (!$user) { // Si le contenu de la variable $resultat retourne false, alors...
                        $message = "Une erreur est survenue, l'inscription n'a pas pu être effectuée.";
                    } else {
                        header('Location: login');
                        exit;
                    }
                }
            } else {
                $message = "Tous les champs de saisie doivent être remplis.";
            }
        }

        $this->render('form/register.html.twig', ['email' => $email, 'username' => $username, 'password' => $hashed_password, 'message' => $message]);
    }

    public function login()
    {
        if (isset($_POST['login'])) { // Lorsque l'élément nommé "login" est cliqué...

            // Je récupère les différents champs de saisie du formulaire et les stockent dans des variables dédiées :
            $email = htmlspecialchars(trim($_POST['email'])); // Champ de saisie nommé "email"
            $password = htmlspecialchars(trim($_POST['password'])); // Champ de saisie nommé "password"
            $message = "";

            if (!empty($email) && !empty($password)) {
                
                $user_row = $this->db->login($email, $password); // $user_row contient le résultat de la requête SQL, encore les données en elles-mêmes
                $table_row = $user_row->fetch(); // Récupère une du résultat de la requête (ici, je récupère les données d'un utilisateur possédant un email et mot de passe précis)

                /* La méthode fetch() permet de récupérer une ligne associée à une ou plusieurs valeurs, ici, je veux qu'elle
                récupère une ligne associée à la requête SQL déclarée dans la variable $table_row. */
                    if ($table_row && password_verify($password, $table_row['password'])) { // Si la requête de la variable $table_row récupère une ligne associée à l'email et au mot de passe...
                        /* Je stocke l'id de l'utilisateur dans la session en assignant le paramètre nommé "user_id" à la colonne
                        "user_id" que l'appel fetch() contenu dans la variable $ligne_table aura récupéré. Cela me permet d'avoir
                        un identifiant parfaitement unique pour les sessions au cas où deux personnes obtiennent la même adresse
                        mail par erreur. */
                        $_SESSION['user_id'] = $table_row['user_id'];
                        $_SESSION['username'] = $table_row['username']; // Je stocke aussi le nom de l'utilisateur trouvé par fetch() dans la session
                        
                        header('Location: /'); // Puis je redirige l'utilisateur vers une autre page.
                        exit; // Cette fonction permet de fermer le script qui exécute le formulaire, cela empêche le formulaire d'être envoyé de nouveau lorsque je rafraichi la page

                    } else { // Sinon j'affiche un message d'erreur
                        $message = "Ces informations ne correspondent à aucun profil.";
                    }
                } else {
                    $message = "Tous les champs de saisie doivent être remplis.";
                }
            }
            
            /* La méthode render() doit toujours se trouver à la fin de la méthode et en dehors d'une condition (if...else)
            ou d'une boucle (for...) */
            $this->render('form/login.html.twig', ['email' => $email, 'password' => $password, 'message' => $message]);
        }

        public function logout() {
            if (isset($_POST['logout'])) {
                session_unset();
				session_destroy(); // Cette méthode permet d'arrêter une session
				header('Location : login'); // Une fois que la session est arrêtée, je redirige l'utilisateur vers le formulaire de connexion
            }
        }
    }
