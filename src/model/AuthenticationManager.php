<?php

require_once("model/Account.php");
require_once("model/AccountStorage.php");

class AuthenticationManager implements AccountStorage {

    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Réinitialiser la base de données (avec des variables d'exemple)
    public function reinit() {
        $this->deleteAllAccount();
        $this->createAccount(new Account("Factorisator", "vanier", password_hash("toto", PASSWORD_BCRYPT), "guest"));
        $this->createAccount(new Account("Documentor", "lecarpentier", password_hash("toto", PASSWORD_BCRYPT), "guest"));
        $this->createAccount(new Account("DIEU", "admin", password_hash("toto", PASSWORD_BCRYPT), "admin"));
    }

    // Retourne un compte s'il existe grâce à son id, renvoie null sinon
    public function readUser($id) {
        $rq = "SELECT * FROM login WHERE login = :id";
        $stmt = $this->db->prepare($rq);
        $data = array(":id" => $id);
        $stmt->execute($data);

        if ($result = $stmt->fetch())
            return new Account($result["name"], $result["login"], $result["password"], $result["status"]);
        return null;
    }

    // Permet de vérifier la validité de les entrées utilisateurs
    public function validUserAccount(array $data) {
        // Si le login ne contient que des lettres, si le login est disponible et le mot de passe + confirmation identiques
        if (preg_match("/^[a-zA-Z]*$/i", $data["login"]) && !$this->existsLogin($data["login"]) && $data["password"] === $data["confirmPass"])
            return true;
        return false;
    }

    // Création de compte
    public function createAccount(Account $acc) {
        $rq = "INSERT INTO login (name, login, password, status) VALUES (:name, :login, :password, :status)";
        $stmt = $this->db->prepare($rq);
        $data = array(
            "name" => $acc->getName(),
            "login" => $acc->getLogin(),
            "password" => $acc->getPassword(),
            "status" => $acc->getStatus()
        );
        $stmt->execute($data);
    }

    // Retourne un booléen si un compte existe avec son id
    public function existsLogin($id) {
        return $this->readUser($id) !== null;
    }

    // Supprime tous les comptes de la base
    public function deleteAllAccount() {
        $rq = "DELETE FROM login";
        $this->db->query($rq);
    }

    //----------------------------------------------------------------------------------------------------------

    // Permet de vérifier le login et le password de l'utilisateur qui veut se connecter
    public function checkAuth($login, $password) {
        $rq = "SELECT * FROM login WHERE login = :login";
        $stmt = $this->db->prepare($rq);
        $data = array(":login" => $login);
        $stmt->execute($data);

        if ($result = $stmt->fetch())
            // Le mot de passe est hashé dans la base, donc on utilise cette fonction
            if (password_verify($password, $result["password"])) {
                $_SESSION["user"] = $result;
                return true;
            }
        return false;
    }

    // Retourne un booléen si l'utilisateur est connecté
    public function isUserConnected() {
        return isset($_SESSION["user"]);
    }

    // Retourne un booléen si l'utilisateur connecté est l'admin
    public function isAdminConnected() {
        return $_SESSION["user"]["status"] === "admin";
    }

    // Retourne le pseudo de l'utilisateur
    public function getUserName() {
        return $_SESSION["user"]["name"];
    }

    // Déconnecter l'utilisateur
    function disconnectUser() {
        unset($_SESSION["user"]);
    }
}
