<?php

/*
J'utilise un namespace pour renommer la classe en src\Controllers, ça sert à éviter les conflits si j'importe une librairie
qui utilise aussi une classe nommée "BaseController" par exemple.
*/
namespace src\Controllers; // Son nouveau nom est donc son chemin d'accès (src\Controllers)

use src\model\Database;
use \Twig\Environment; // Appel de la classe Environment : qui va stocker la configuration
use Twig\Loader\FilesystemLoader; // Appel de la classe FilesystemLoader : qui va localiser et charger les templates (modèles)


// Le but de cette classe est fournir le chemin d'accès vers une page (template)
abstract class BaseController {

    // Cette propriété est "protégée" : elle est accessible uniquement dans la classe BaseController et ses enfants (les classes qui héritent de BaseController)
    protected $db;

    public function __construct()
    {
        // Je déclare une nouvelle instance de la classe Database
        $this->db = new Database();
    }
}