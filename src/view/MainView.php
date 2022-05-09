<?php
require_once("Router.php");
require_once("model/Pokemon.php");
require_once("model/PokemonBuilder.php");

const constTitle = "POKÉPÉDIA";

class MainView {
    protected $router;
    protected $titlecontent;
    protected $content;
    protected $title;
    protected $style;
    protected $feedback;
    protected $script;

    //----------------------------------------------------------------------------------------------------------

    public function __construct(Router $router, $feedback) {
        $this->router = $router;
        $this->titlecontent = null;
        $this->content = null;
        $this->title = null;
        $this->style = "";
        $this->feedback = $feedback;
        $this->script = "";
    }

    public function getTitle() {
        return $this->title;
    }

    //----------------------------------------------------------------------------------------------------------

    public function render() {
        $menu = $this->getMenu();
        $title = $this->title;
        $titlecontent = $this->titlecontent;
        $content = $this->content;
        include_once("template.php");
    }

    //----------------------------------------------------------------------------------------------------------

    public function makeHomePage() {
        require "homePage.php";
    }

    public function makeGalleryPage($list) {
        require "galleryPage.php";
    }

    public function makeCreationPage(PokemonBuilder $pb) {
        require "creationPage.php";
    }

    public function makeAboutPage() {
        require "aboutPage.php";
    }

    public function makeErrorPage($url) {
        require "errorPage.php";
    }

    public function makePokemonPage(Pokemon $pokemon, $id) {
        require "pokemonPage.php";
    }

    public function makePokemonDeletionPage(Pokemon $pokemon, $id) {
        $this->title = constTitle . " | Suppression";
        $this->titlecontent = "Confirmation de la suppression du Pokémon \"" . $pokemon->getName() . "\"";
        $s = "<form method=\"POST\" action=\"" . $this->router->pokemomDeletionURL(self::htmlesc($id)) . "\">";
        $s .= "<label>Êtes-vous sûr(e) de bien vouloir supprimer ce Pokémon ?</label>";
        $s .= "<p><input type=\"submit\" name=\"supprimer\" value=\"Valider\"></p>";
        $s .= "</form>";
        $this->content = $s;
    }

    public function makePokemonModifyPage(PokemonBuilder $pb, $id) {
        require "modifyPage.php";
    }

    public function makeLoginFormPage() {
        require "loginPage.php";
    }

    public function makeRegisterFormPage() {
        require "registerPage.php";
    }

    public function makeErrorPermissionPage($reason) {
        $this->title = constTitle . " | Erreur";
        $this->titlecontent = "Erreur";
        $this->content = "Vous n'avez pas les permissions. " . $reason;
    }
    //----------------------------------------------------------------------------------------------------------

    public function displayPokemonCreationSuccess($id) {
        $this->router->POSTredirect($this->router->pokemonPageURL($id), "Le pokémon a bien été créé !");
    }

    public function displayPokemonCreationFailure() {
        $this->router->POSTredirect($this->router->creationPageURL(), "Oups ! Il y a des erreurs dans le formulaire.");
    }

    public function displayPokemonDeletion() {
        $this->router->POSTredirect($this->router->galleryPageURL(), "Le pokémon a bien été supprimé");
    }

    public function displayPokemonModifySuccess($id) {
        $this->router->POSTredirect($this->router->pokemonPageURL($id), "Le pokémon a bien été modifié !");
    }

    public function displayPokemonModifyFailure($id) {
        $this->router->POSTredirect($this->router->pokemonModifyURL($id), "Oups ! Il y a des erreurs dans le formulaire.");
    }

    public function displayAuthSuccess() {
        $this->router->POSTredirect($this->router->homePageURL(), "Bonjour " . $_SESSION["user"]["name"] . " !");
    }

    public function displayAuthFailure() {
        $this->router->POSTredirect($this->router->loginPageURL(), "M I N C E ! Login ou mot de passe invalide");
    }

    public function displayRegisterFailure() {
        $this->router->POSTredirect($this->router->registerPageURL(), "Oups ! Il y a des erreurs dans le formulaire.");
    }


    public function redirectionHome() {
        $this->router->POSTredirect($this->router->homePageURL(), "");
    }

    //----------------------------------------------------------------------------------------------------------

    public function makeDebugPage($variable) {
        $this->title = "Debug";
        $this->content = "<pre>" . htmlspecialchars(var_export($variable, true)) . "</pre>";
    }



    //----------------------------------------------------------------------------------------------------------

    protected function getMenu() {
        return array(
            "Accueil" => $this->router->homePageURL(),
            "Galerie" => $this->router->galleryPageURL(),
            "À propos" => $this->router->proposPageURL(),
            "Login" => $this->router->loginPageURL(),
        );
    }

    public static function htmlesc($text) {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8");
    }
}
