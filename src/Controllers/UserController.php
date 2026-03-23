<?php

namespace src\controllers;

use Exception;

session_start();

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
                    /*
                    La fonction preg_match() vérifie que la chaîne de caractères présente dans la variable $password contient :
                    - au moins une lettre minuscule
                    - au moins une lettre majuscule
                    - au moins un chiffre
                    - au moins un caractère spécial : @#-_$%^&+=§!?
                    - que la chaîne de caractères contient au minimum 8 caractères et au maximum 30 caractères
                    */
                    if (preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,30}$/', $password)) {

                        // Je hache (hash) le mot de passe
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        // Puis je créé l'utilisateur
                        $user = $this->db->registration($email, $username, $hashed_password);

                        if (!$user) { // Si le contenu de la variable $resultat retourne false, alors...
                            $message = "Une erreur est survenue, l'inscription n'a pas pu être effectuée.";
                        } else {
                            header('Location: /form/login');
                            exit;
                        }
                    } else {
                        $message = "Le mot de passe doit contenir au moins 1 majuscule, 1 chiffre, 1 caractère spécial et comporter au moins 8 caractères.";
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

                    header('Location: /home'); // Puis je redirige l'utilisateur vers une autre page.
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

    public function findUser()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $csrf_token = $_SESSION['csrf_token']; // Je récupère le token CSRF
        $user_id = $_SESSION['user_id']; // Je récupère l'identifiant de l'utilisateur présent dans la session
        $user = $this->db->findUser($user_id); // Je recherche l'utilisateur grâce à l'id récupéré dans la session

        $this->render('user/index.html.twig', ['user_id' => $user_id, 'user' => $user, 'csrf_token' => $csrf_token]);
    }

    public function updateUsername()
    {
        $user_id = $_SESSION['user_id']; // Je récupère l'identifiant de l'utilisateur présent dans la session
        $user = $this->db->findUser($user_id); // Je recherche l'utilisateur grâce à l'id récupéré dans la session
        $username = null;
        $message = "";

        if ($user['user_id'] === $user_id) {

            // Si le token n'existe pas OU qu'il existe mais qu'il ne correspond pas au token créé par la session...
            if (!isset($_POST['csrf_token']) || ($_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
                throw new Exception("Le token CSRF est invalide.");
            } else {

                if (isset($_POST['update_username'])) {

                    // Récupère la valeur du champ de saisie "username"
                    $username = htmlspecialchars($_POST['username']);

                    if (!empty($username)) {

                        $updatedUsername = $this->db->updateUsername($username, $user_id);

                        if ($updatedUsername) {
                            unset($_SESSION['csrf_token']);
                            header('Location: /user/account');
                            exit;
                        }
                    } else {
                        $message = "Le champ de saisie doit être rempli.";
                    }
                }

                $this->render('user/index.html.twig', ['user_id' => $user_id, 'user' => $user, 'username' => $username, 'message' => $message]);
            }
        }
    }

    public function updateEmail()
    {

        $user_id = $_SESSION['user_id']; // Je récupère l'identifiant de l'utilisateur présent dans la session
        $user = $this->db->findUser($user_id); // Je recherche l'utilisateur grâce à l'id récupéré dans la session
        $csrf_token = $_POST['csrf_token'];
        $email = null;
        $message = "";

        if ($user['user_id'] === $user_id) {

            if (!isset($_POST['csrf_token']) || ($_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
                throw new Exception("Le token CSRF est invalide.");
            } else {

                if (isset($_POST['update_email'])) {

                    $email = htmlspecialchars(trim($_POST['email'])); // Champ de saisie nommé "email"

                    if (!empty($email)) {

                        // Vérifie si l'adresse mail saisie existe
                        $emailValidation = $this->db->checkIfEmailExists($email);

                        // Si l'adresse mail saisie par l'utilisateur n'existe pas...
                        if (!$emailValidation) {

                            // Je met à jour l'adresse mail de l'utilisateur
                            $updatedEmail = $this->db->updateEmail($email, $user_id);

                            if ($updatedEmail) {
                                unset($_SESSION['csrf_token']);
                                header('Location: /user/account');
                                exit;
                            }
                        } else {
                            $message = "Cette adresse mail est déjà associée à un compte.";
                        }
                    } else {
                        $message = "Le champ de saisie doit être rempli.";
                    }
                }

                $this->render('user/index.html.twig', ['user_id' => $user_id, 'user' => $user, 'email' => $email, 'message' => $message, 'csrf_token' => $csrf_token]);
            }
        }
    }

    public function updatePassword()
    {
        $user_id = $_SESSION['user_id'];
        $user = $this->db->findUser($user_id);
        // $csrf_token = $_SESSION['csrf_token'];

        if ($user['user_id'] === $user_id) {

            /*if (!isset($_POST['csrf_token']) || ($_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
                throw new Exception("Le token CSRF est invalide.");
            } else {*/
                if (isset($_POST['update-password'])) {

                    $oldPassword = htmlspecialchars(trim($_POST['old_password']));
                    $newPassword = htmlspecialchars(trim($_POST['new_password']));
                    $confirmPassword = htmlspecialchars(trim($_POST['confirm_password']));

                    if (!empty($oldPassword) && !empty($newPassword) && !empty($confirmPassword)) {

                        if ($user && password_verify($oldPassword, $user['password'])) {

                            if (preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,30}$/', $newPassword)) {

                                if ($newPassword === $confirmPassword) {

                                    // Je hache (hash) le nouveau mot de passe
                                    $hashed_password = password_hash($confirmPassword, PASSWORD_DEFAULT);

                                    // Puis je le met à jour
                                    $confirmedPassword = $this->db->updatePassword($hashed_password);
                                    $successMessage = "Le mot de passe a été modifié avec succès.";
                                } else {
                                    $message = "Le mot de passe saisi ne correspond pas au nouveau mot de passe.";
                                }
                            } else {
                                $message = "Le mot de passe doit contenir au moins 1 majuscule, 1 chiffre, 1 caractère spécial et comporter au moins 8 caractères.";
                            }
                        } else {
                            $message = "Le mot de passe saisi ne correspond pas au mot de passe actuel.";
                        }
                    } else {
                        $message = "Tous les champs de saisie doivent être remplis.";
                    }
                }

                $this->render('user/updatePassword.html.twig', ['user_id' => $user_id, 'user' => $user, 'oldPassword' => $oldPassword, 'newPassword' => $newPassword, 'confirmPassword' => $confirmPassword, 'hashed_password' => $hashed_password, 'confirmedPassword' => $confirmedPassword, 'message' => $message, 'successMessage' => $successMessage]);
            }
        }

    public function logout()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset(); // Vide les variables associées à une session
            session_destroy(); // Cette méthode permet d'arrêter une session
            header('Location: ../form/login'); // Une fois que la session est arrêtée, je redirige l'utilisateur vers le formulaire de connexion
            exit;
        }
    }

    public function deleteAccount()
    {
        $user_id = $_SESSION['user_id']; // Je récupère l'id de la tâche à supprimer
        $user = $this->db->findUser($user_id);

        if ($user['user_id'] === $user_id) {
            // Si le token n'existe pas OU qu'il existe mais qu'il ne correspond pas au token créé par la session...
            if (!isset($_POST['csrf_token']) || ($_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
                throw new Exception("Le token CSRF est invalide.");
            } else {
                $statement = $this->db->deleteAccount($user_id);

                if (!$statement) {
                    throw new Exception("Une erreur est survenue : La suppression n'a pas pu être effectuée.");
                } else {
                    header('Location: /'); // Je redirige vers la page d'inscription
                    exit;
                }
            }
        } else {
            throw new Exception("Une erreur est survenue : La suppression n'a pas pu être effectuée.");
        }
    }
}
