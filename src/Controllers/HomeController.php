<?php

namespace src\controllers;

// La classe HomeController hérite de la classe BaseController : elle récupère ses propriétés et méthodes
class HomeController extends BaseController {

    // La méthode render() permet d'appeler un fichier spécifique grâce à son chemin d'accès et de lui transmettre des données (voir BaseController.php)
    public function index() {
        /*
        J'assigne le chemin d'accès au fichier à la variable $path et lui transmet des données grâce à la variable $data : $path
        renvoie le fichier "index.html.twig" et $data renvoie une variable $sentence qui contient une chaîne de caractères que je
        vais pouvoir afficher dans la page.
        */
        $tasks = $this->db->findAllTasks('tasks'); // Je récupère toutes les données de la table tasks grâce à la méthode findAllTasks
        $categories = $this->db->getCategories('categories');
        $priorities = $this->db->getPriorities('priorities');

        $this->render('home/index.html.twig', ['tasks' => $tasks, 'categories' => $categories, 'priorities' => $priorities]);
    }

    public function createTask() {
        $data = $this->db->createTask('data');
    
        $this->render('home/index.html.twig', ['data' => $data]);
    }
}