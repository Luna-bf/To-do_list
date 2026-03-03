<?php

/*
J'utilise un namespace pour renommer la classe en src\Controllers, ça sert à éviter les conflits si j'importe une librairie
qui utilise aussi une classe nommée "BaseController" par exemple.
*/
namespace src\controllers; // Son nouveau nom est donc son chemin d'accès (src\Controllers)

use src\model\Database; // Appel de la classe "Database", qui supervise les actions liées à la BDD
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

    /*
    La méthode render() appelle le chemin d'accès vers une page : $path correspond au nom du fichier à charger (récupérer) dans le
    dossier TEMPLATES et $data correspond à un tableau associatif qui va contenir les données à envoyer à la page (les données
    sont stockées dans des variables qui vont être transmises au fichier final).
    */
    public function render($path, $data = []) {
        /*
        J'implémente le code de Twig afin qu'il s'occupe du chargement des pages :
        
        D'abord j'indique le nom du dossier qui contient toutes mes pages (la constante TEMPLATES) en tant qu'argument à
        l'instance de la classe "FileSystemLoader" et je stocke cette instance dans une variable nommée $loader.
        
        Je déclare ensuite une variable nommée $twig, qui va contenir l'instance de la classe "Environment" : cette instance prend
        en argument la variable $loader, qui contient le nom du dossier où sont stockées les pages. Cette instance va configurer
        Twig pour lui permettre de charger les fichiers à la demande de la méthode render().
        
        Enfin, je déclare une variable $template : elle va charger l'un des fichiers contenus dans la variable $twig grâce à la
        méthode load(), qui prend la variable $path en argument ($path correspond au chemin d'accès du fichier appelé).
        */
        $loader = new FilesystemLoader(TEMPLATES);
        $twig = new Environment($loader);
        $template = $twig->load($path);

        /* Twig va générer le fichier HTML final (avec les données passées par la variable $data) et echo va l'afficher dans le
        navigateur */
        echo $template->render($data);
        exit; // Stoppe l'exécution du script
    }
}