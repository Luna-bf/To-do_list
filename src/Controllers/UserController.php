<?php

namespace src\controllers;

// session_start();

use Exception;

class UserController extends BaseController {

    public function register() {
        // Je récupère les catégories et les priorités pour les afficher en tant qu'option dans le formulaire
        // $categories = $this->db->getCategories('categories');
        // $priorities = $this->db->getPriorities('priorities');

        // Je récupère les valeurs saisies dans le formulaire
        // $task = $this->db->createTask('category', 'priority', 'task_name');

        // if ($task) {
            // header('Location: /'); // Je redirige vers la page d'accueil (la racine, soit : '/')
            // exit;
        // }

        // Je passe les catégories, les priorités et la tâche que je créé en tant que valeurs à la page
        $this->render('form/register.html.twig', ["str" => "Bonjour le monde !"]);
    }

    public function login() {
        // session_start();

        $this->render('form/login.html.twig', ["str" => "Re bonjour le monde !"]);
    }
}