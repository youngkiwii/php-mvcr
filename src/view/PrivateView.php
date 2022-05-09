<?php

require_once("view/MainView.php");
require_once("model/Account.php");

class PrivateView extends MainView {
    private $acc;

    public function __construct(Router $router, $feedback, Account $acc) {
        parent::__construct($router, $feedback);
        $this->acc = $acc;
    }

    public function makeLogoutUser() {
        $this->router->POSTredirect($this->router->homePageURL(), "Au-revoir !");
    }

    public function displayPokemonAlreadyTaken() {
        $this->router->POSTredirect($this->router->creationPageURL(), "Le nom du pokémon a déjà été pris ! Veuillez en choisir un autre.");
    }

    public function displayPokemonImageFailure() {
        $this->router->POSTredirect($this->router->creationPageURL(), "L'image n'est pas valide !");
    }

    public function displayPutImagePokemonError() {
        $this->router->POSTredirect($this->router->creationPageURL(), "Veuillez saisir une image.");
    }

    public function displayPokemonImageFailureModified($id) {
        $this->router->POSTredirect($this->router->pokemonModifyURL($id), "L'image n'est pas valide !");
    }


    public function redirectionHomeAlreadyConnected() {
        $this->router->POSTredirect($this->router->homePageURL(), "Vous êtes déjà connecté !");
    }

    //----------------------------------------------------------------------------------------------------------

    //Redéfinition de la méthode pour le menu
    protected function getMenu() {
        return array(
            "Accueil" => $this->router->homePageURL(),
            "Galerie" => $this->router->galleryPageURL(),
            "Création" => $this->router->creationPageURL(),
            "À propos" => $this->router->proposPageURL(),
            "Logout" => $this->router->logoutPageURL(),
        );
    }
}
