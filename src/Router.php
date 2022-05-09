<?php

require_once("view/MainView.php");
require_once("view/PrivateView.php");
require_once("control/Controller.php");
require_once("model/AuthenticationManager.php");
require_once("model/Account.php");

// Cette classe permet de gérer les requêtes HTTP
class Router {
    // Paramètres : base de données pokemon, base de données user
    private $db;
    private $dbAcc;

    public function __construct(PokemonStorage $db, AuthenticationManager $dbAcc) {
        $this->db = $db;
        $this->dbAcc = $dbAcc;
    }

    //----------------------------------------------------------------------------------------------------------

    public function main() {
        // Début de session pour rester connecter
        session_start();

        // Si la clé feedback existe dans $_SESSION, on la garde dans une variable et on la vide de $_SESSION
        $feedback = key_exists('feedback', $_SESSION) ? $_SESSION['feedback'] : '';
        $_SESSION['feedback'] = '';

        // Si l'utilisateur est connecté ou non
        if ($this->dbAcc->isUserConnected())
            // Une vue pour un utilisateur connecté
            $view = new PrivateView($this, $feedback, $this->dbAcc->readUser($_SESSION["user"]["login"]));
        else
            // Une vue pour un visiteur
            $view = new MainView($this, $feedback);

        // Classe pour gérer les actions
        $control = new Controller($view, $this->db, $this->dbAcc);

        // Lecture du chemin en enlevant le premier '/' et le dernier
        $action = "";
        if (isset($_SERVER["PATH_INFO"])) {
            if (substr($_SERVER["PATH_INFO"], -1) === "/")
                $action = substr($_SERVER["PATH_INFO"], 1, -1);
            else
                $action = substr($_SERVER["PATH_INFO"], 1);
        }

        // S'il y a un '=', on le décompose pour récupérer l'id
        $id = explode("=", $action);

        // On récupère le dernièr élément sur le explode
        $id = $id[count($id) - 1];

        // Si l'id est un pokémon, on le récupère sinon il vaut null
        $pokemon = $this->db->read($id);

        // Actions possibles en fonction de l'utilisateur
        // Tout le monde
        $allowedActions = array(
            "",
            "login",
            "apropos",
            "gallery",
            "register",
        );

        // Si l'utilisateur est connecté
        if ($this->dbAcc->isUserConnected()) {
            // On lui ajoute les actions de déconnexion et de création de pokémon
            array_push($allowedActions, "logout", "creation");
            // Si on a un pokémon
            if ($pokemon !== null) {
                // On lui ajoute l'action de voir la page
                array_push($allowedActions, $id);
                // On ajoute la suppression et la modification d'un pokémon si l'utilisateur est admin ou en est l'auteur
                if ($pokemon->getAuthor() === $_SESSION["user"]["login"] || $this->dbAcc->isAdminConnected())
                    array_push($allowedActions, "asksupprimer=" . $id, "supprimer=" . $id, "modify=" . $id, "update=" . $id);
            }
        }

        // Si $_POST est définie, alors on lui ajoute la confirmation de connexion, de création de pokémon et de création de compte.
        if (!empty($_POST))
            array_push($allowedActions, "confirmAuth", "saveNew", "confirmRegister");

        // Si l'action se trouve dans la liste des actions possibles suivant l'utilisateur
        $boolAllowed = in_array($action, $allowedActions);

        // Selon l'action
        switch ($action) {
            case "": // Page d'accueil
                if ($boolAllowed)
                    $view->makeHomePage();
                break;

            case "login": // Page de login
                if ($boolAllowed)
                    $control->loginPage();
                break;

            case "confirmAuth": // Confirmation de connexion
                if ($boolAllowed)
                    $control->confirmAuth($_POST);
                else
                    $view->makeErrorPermissionPage("Vous devez d'abord remplir le formulaire de connexion : <a href='" . $this->loginPageURL() . "'>s'identifier</a>");
                break;

            case "logout": // Déconnexion
                if ($boolAllowed)
                    $control->logoutUser();
                else
                    $view->makeErrorPermissionPage("Vous devez être connecté : <a href='" . $this->loginPageURL() . "'>s'identifier</a>");
                break;

            case "register": // Page de création de compte
                if ($boolAllowed)
                    $control->registerPage();
                break;

            case "confirmRegister": // Confirmation de création de compte
                if ($boolAllowed)
                    $control->confirmRegister($_POST);
                else
                    $view->makeErrorPermissionPage("Vous devez d'abord remplir le formulaire d'inscription : <a href='" . $this->registerPageURL() . "'>s'inscrire</a>");
                break;

            case "apropos": // Page à propos
                if ($boolAllowed)
                    $view->makeAboutPage();
                break;

            case "creation": // Page de création d'un pokémon
                if ($boolAllowed)
                    $control->creationPage();
                else
                    $view->makeErrorPermissionPage("Seuls les membres du site peuvent effectuer cette action : <a href='" . $this->loginPageURL() . "'>s'identifier</a>");
                break;

            case "saveNew": // Confirmation de création d'un pokémon
                if ($boolAllowed)
                    $control->saveNewPokemon($_POST, $_FILES);
                else
                    $view->makeErrorPermissionPage("Vous devez d'abord remplir le formulaire de création : <a href='" . $this->creationPageURL() . "'>ici</a>");
                break;

            case "gallery": // Liste de tous les pokémons
                if ($boolAllowed)
                    $control->showGallery();
                break;

            case "asksupprimer=" . $id: // Confirmation de suppression par l'utilisateur
                if ($boolAllowed)
                    $control->askPokemonDeletion($id);
                break;

            case "supprimer=" . $id: // Suppresion définitive d'un pokémon
                if ($boolAllowed)
                    $control->deletePokemon($id);
                break;

            case "modify=" . $id: // Page de modification d'un pokémon
                if ($boolAllowed)
                    $control->modifyPokemon($id);
                break;

            case "update=" . $id: // Modification définitive d'un pokémon
                if ($boolAllowed)
                    $control->updateModifiedPokemon($_POST, $_FILES, $id);
                break;

            default:
                // Page de pokémon s'il est différent de null
                if ($pokemon != null) {
                    if ($boolAllowed)
                        $view->makePokemonPage($pokemon, $action);
                    else
                        $view->makeErrorPermissionPage("Seuls les membres du site peuvent effectuer cette action : <a href='" . $this->loginPageURL() . "'>s'identifier</a>");
                } else // L'action n'existe pas
                    $view->makeErrorPage($action);
                break;
        }

        // Si la vue n'est pas définie alors cela veut dire que c'est pour modifier un pokémon
        if ($view->getTitle() === null)
            $view->makeErrorPermissionPage("Seuls l'auteur de ce pokémon et l'admin peuvent modifier ou supprimer ce pokémon.");

        // Et enfin on fait le rendu
        $view->render();
    }

    //----------------------------------------------------------------------------------------------------------

    // Toutes les URL possibles sur le site
    public function homePageURL() {
        return "/projet-inf5c-2022";
    }

    public function pokemonPageURL($id) {
        return "/projet-inf5c-2022/index.php/$id";
    }

    public function proposPageURL() {
        return "/projet-inf5c-2022/index.php/apropos";
    }

    public function registerPageURL() {
        return "/projet-inf5c-2022/index.php/register";
    }

    public function confirmRegisterPageURL() {
        return "/projet-inf5c-2022/index.php/confirmRegister";
    }

    public function loginPageURL() {
        return "/projet-inf5c-2022/index.php/login";
    }

    public function confirmAuthPageURL() {
        return "/projet-inf5c-2022/index.php/confirmAuth";
    }

    public function logoutPageURL() {
        return "/projet-inf5c-2022/index.php/logout";
    }

    public function creationPageURL() {
        return "/projet-inf5c-2022/index.php/creation";
    }

    public function savePageURL() {
        return "/projet-inf5c-2022/index.php/saveNew";
    }

    public function galleryPageURL() {
        return "/projet-inf5c-2022/index.php/gallery";
    }

    public function pokemonAskDeletionURL($id) {
        return "/projet-inf5c-2022/index.php/asksupprimer=$id";
    }

    public function pokemomDeletionURL($id) {
        return "/projet-inf5c-2022/index.php/supprimer=$id";
    }

    public function pokemonModifyURL($id) {
        return "modify=$id";
    }

    public function pokemonUpdateModifyURL($id) {
        return "update=$id";
    }

    //----------------------------------------------------------------------------------------------------------

    // Redirection de page
    public function POSTredirect($url, $feedback) {
        $_SESSION["feedback"] = $feedback;
        header("Location: " . htmlspecialchars_decode($url), true, 303);
    }
}
