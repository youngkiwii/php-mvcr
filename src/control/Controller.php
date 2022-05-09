<?php
require_once("view/MainView.php");
require_once("model/Pokemon.php");
require_once("model/PokemonStorage.php");
require_once("model/PokemonBuilder.php");
require_once("model/AuthenticationManager.php");

class Controller {

    private $view;
    private $pokemonStorage;
    private $authManager;
    private $newPokemonBuilder;
    private $modifiedPokemonBuilder;

    public function __construct($view, PokemonStorage $pokemonStorage, AuthenticationManager $authManager) {
        $this->view = $view;
        $this->pokemonStorage = $pokemonStorage;
        $this->authManager = $authManager;
        $this->newPokemonBuilder = key_exists("newPokemonBuilder", $_SESSION) ? $_SESSION["newPokemonBuilder"] : null;
        $this->modifiedPokemonBuilder = key_exists("modifiedPokemonBuilder", $_SESSION) ? $_SESSION["modifiedPokemonBuilder"] : null;
    }

    // Dès qu'il n'y a plus de référence sur cette objet
    public function __destruct() {
        $_SESSION['newPokemonBuilder'] = $this->newPokemonBuilder;
        $_SESSION['modifiedPokemonBuilders'] = $this->newPokemonBuilder;
    }

    // Page de creation
    public function creationPage() {
        // Si le PokemonBuilder est null, cela veut dire que l'utilisateur vient pour la première, le cas contraire est qu'il y a eu une erreur dans le formulaire
        if ($this->newPokemonBuilder === null)
            $this->newPokemonBuilder = new PokemonBuilder();
        $this->view->makeCreationPage($this->newPokemonBuilder);
    }

    // Liste de tous les pokémons
    public function showGallery() {
        $this->view->makeGalleryPage($this->pokemonStorage->readAll());
    }

    //----------------------------------------------------------------------------------------------------------

    // Créer un nouveau pokémon quand l'utilisateur aura rempli le formulaire
    public function saveNewPokemon(array $data, array $upload) {
        // S'il n'y a aucune erreur sur l'image (pas d'upload, pas le bon format, fichier trop gros)
        if ($upload["img"]["error"] === UPLOAD_ERR_OK) {
            // On récupère l'extension grâce à une fonction
            $extension = $this->uploadImageExtension($upload);
            // Si l'extension est bonne alors
            if ($extension !== "") {
                $path = "/projet-inf5c-2022/upload/" . hash("md5", $data["name"]) . "." . $extension;
                $this->newPokemonBuilder = new PokemonBuilder($data, $path);

                // On vérifie si le nom du pokémon n'est pas déjà pris
                if ($this->pokemonStorage->exists($data["name"])) {
                    $this->view->displayPokemonAlreadyTaken();
                } else {
                    if ($this->newPokemonBuilder->isValid()) {
                        // On met l'image dans le dossier upload du serveur et crée le pokémon
                        if (move_uploaded_file($upload["img"]["tmp_name"], "/users/21906389/www-dev" . $path)) {
                            $newPokemon = $this->newPokemonBuilder->createPokemon();
                            $id = $this->pokemonStorage->create($newPokemon);
                            $this->newPokemonBuilder = null;
                            $this->view->displayPokemonCreationSuccess($id);
                        } else
                            $this->view->displayPokemonImageFailure();
                    } else
                        $this->view->displayPokemonCreationFailure();
                }
            } else
                $this->view->displayPokemonImageFailure();
        } else
            $this->view->displayPutImagePokemonError();
    }

    //----------------------------------------------------------------------------------------------------------

    // Confirmation de suppression d'un pokémon
    public function askPokemonDeletion($id) {
        $pokemon = $this->pokemonStorage->read($id);
        if ($pokemon === null)
            $this->view->makeErrorPage($id);
        else
            $this->view->makePokemonDeletionPage($pokemon, $id);
    }

    // Suppression d'un pokémon
    public function deletePokemon($id) {
        $pokemon = $this->pokemonStorage->read($id);
        if (file_exists("/users/21906389/www-dev" . $pokemon->getImage()))
            unlink("/users/21906389/www-dev" . $pokemon->getImage());

        $this->pokemonStorage->delete($id);
        $this->view->displayPokemonDeletion();
    }

    //----------------------------------------------------------------------------------------------------------

    // Page de modification avec les informations du pokémon déjà remplies
    public function modifyPokemon($id) {
        $pokemon = $this->pokemonStorage->read($id);
        if ($pokemon === null)
            $this->view->makeErrorPage($id);
        else {
            $this->view->makePokemonModifyPage(new PokemonBuilder(array(
                "name" => $pokemon->getName(),
                "type" => $pokemon->getType(),
                "size" => $pokemon->getSize(),
                "weight" => $pokemon->getWeight(),
                "author" => $pokemon->getAuthor()
            ), $pokemon->getImage()), $id);
        }
    }

    // Modification du pokémon
    public function updateModifiedPokemon(array $data, array $file, $id) {
        $oldPokemon = $this->pokemonStorage->read($id);
        $date = $oldPokemon->getDate();
        $path = "";
        if ($file["img"]["error"] === UPLOAD_ERR_NO_FILE) {
            $path = $oldPokemon->getImage();
        } else if ($file["img"]["error"] === UPLOAD_ERR_OK) {
            $extension = $this->uploadImageExtension($file);
            if ($extension !== "")
                $path = "/projet-inf5c-2022/upload/" . hash("md5", $data["name"]) . "." . $extension;
        } else {
            $this->view->displayPokemonImageFailureModified($id);
            return;
        }

        $pb = new PokemonBuilder($data, $path);
        if ($pb->isValid() && $path !== "") {
            if ($file["img"]["error"] === UPLOAD_ERR_OK) {
                $oldfile_string = "/users/21906389/www-dev" . $oldPokemon->getImage();
                if (file_exists($oldfile_string))
                    unlink($oldfile_string);

                if (!move_uploaded_file($file["img"]["tmp_name"], "/users/21906389/www-dev" . $path)) {
                    $this->view->displayPokemonImageFailureModified($id);
                    return;
                }
            }

            $modifiedPokemon = new Pokemon(MainView::htmlesc($data["name"]), $data["type"], $data["size"], $data["weight"], $_SESSION["user"]["login"], $path, $date);
            $this->pokemonStorage->update(MainView::htmlesc($id), $modifiedPokemon);

            unset($this->modifiedPokemonBuilder[$id]);
            $this->view->displayPokemonModifySuccess($id);
        } else {
            $this->modifiedPokemonBuilder[$id] = $pb;
            $this->view->displayPokemonModifyFailure($id);
        }
    }

    //----------------------------------------------------------------------------------------------------------

    // Page de login
    public function loginPage() {
        if ($this->authManager->isUserConnected())
            $this->view->redirectionHome();
        else
            $this->view->makeLoginFormPage();
    }

    // Déconnexion de l'utilisateur
    public function logoutUser() {
        unset($_SESSION["user"]);
        $this->view->makeLogoutUser();
    }

    // Connexion de l'utilisateur
    public function confirmAuth(array $data) {
        if (count($data) === 0) {
            $this->view->redirectionHome();
        } else {
            if ($this->authManager->checkAuth(MainView::htmlesc($data["login"]), $data["password"]))
                $this->view->displayAuthSuccess();
            else {
                $this->view->displayAuthFailure();
            }
        }
    }

    // Page d'inscription
    public function registerPage() {
        if (isset($_SESSION["user"]))
            $this->view->redirectionHomeAlreadyConnected();
        else
            $this->view->makeRegisterFormPage();
    }

    // Page d'inscription
    public function confirmRegister(array $data) {
        if ($this->authManager->validUserAccount($data)) {
            $this->authManager->createAccount(new Account(MainView::htmlesc($data["name"]), strtolower($data["login"]), password_hash($data["password"], PASSWORD_BCRYPT), "guest"));
            $this->authManager->checkAuth($data["login"], $data["password"]);
            $this->view->displayAuthSuccess();
        } else
            $this->view->displayRegisterFailure();
    }

    //----------------------------------------------------------------------------------------------------------

    // Extension de l'image choisi
    public function uploadImageExtension(array $upload) {
        $extension = "";
        switch (exif_imagetype($upload["img"]["tmp_name"])) {
            case IMAGETYPE_GIF:
                $extension = "gif";
                break;
            case IMAGETYPE_JPEG:
                $extension = "jpg";
                break;
            case IMAGETYPE_PNG:
                $extension = "png";
                break;
            default:
                break;
        }
        return $extension;
    }

    public static function getCurrentDate() {
        setlocale(LC_TIME, "fr_FR");
        return strftime("%d/%m/%Y");
    }
}
