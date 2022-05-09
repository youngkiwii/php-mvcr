<?php

require_once("PokemonStorage.php");
require_once("Pokemon.php");

class PokemonStorageMySQL implements PokemonStorage {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Réinitialisation de la base de données (compteur d'id et variables d'exemple)
    public function reinit() {
        $this->deleteAll();
        $this->db->query("ALTER TABLE pokemons DROP id");
        $this->db->query("ALTER TABLE pokemons ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
        $this->create(new Pokemon("Po", "Combat", "188", "117", "admin", "/projet-inf5c-2022/upload/Po.jpg", Controller::getCurrentDate()));
        $this->create(new Pokemon("ramXVI", "Eau", "165", "55", "admin", "/projet-inf5c-2022/upload/ramXVI.png", Controller::getCurrentDate()));
        $this->create(new Pokemon("youngkiwii", "Feu", "183", "60", "admin", "/projet-inf5c-2022/upload/youngkiwii.gif", Controller::getCurrentDate()));
    }

    //Création d'un pokémon et retourne l'id
    public function create(Pokemon $p) {
        $rq = "INSERT INTO pokemons (name, type, size, weight, author, img, datecrea) VALUES (:name, :type, :size, :weight, :author, :img, :datecrea)";
        $stmt = $this->db->prepare($rq);
        $data = array(
            "name" => $p->getName(),
            "type" => $p->getType(),
            "size" => $p->getSize(),
            "weight" => $p->getWeight(),
            "author" => $p->getAuthor(),
            "img" => $p->getImage(),
            "datecrea" => $p->getDate(),
        );
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    // Retourne le pokémon grâce à son id et null s'il n'existe pas
    public function read($id) {
        $rq = "SELECT * FROM pokemons WHERE id = :id";
        $stmt = $this->db->prepare($rq);
        $data = array(":id" => $id);
        $stmt->execute($data);

        if ($result = $stmt->fetch())
            return new Pokemon($result["name"], $result["type"], $result["size"], $result["weight"], $result["author"], $result["img"], $result["datecrea"]);
        return null;
    }

    // Retourne une liste de tous les pokémons de la base de données
    public function readAll() {
        $array = array();
        $rq = "SELECT * FROM pokemons";
        $stmt = $this->db->query($rq);
        while (($ligne = $stmt->fetch()) !== false) {
            $array[$ligne["id"]] = new Pokemon($ligne["name"], $ligne["type"], $ligne["size"], $ligne["weight"], $ligne["author"], $ligne["img"], $ligne["datecrea"]);
        }
        return $array;
    }

    // Retourne un booléen pour savoir si un pokémon existe ou non
    public function exists($id) {
        return $this->read($id) !== null;
    }

    // Modifier un pokémon
    public function update($id, Pokemon $p) {
        $rq = "UPDATE pokemons SET name=:name, type=:type, size=:size, weight=:weight WHERE id=:id";
        $stmt = $this->db->prepare($rq);
        $data = array(
            "name" => $p->getName(),
            "type" => $p->getType(),
            "size" => $p->getSize(),
            "weight" => $p->getWeight(),
            "id" => $id
        );
        $stmt->execute($data);
    }

    // Supprimer un pokémon
    public function delete($id) {
        $rq = "SELECT * FROM pokemons WHERE id = :id";
        $stmt = $this->db->prepare($rq);
        $data = array(":id" => $id);
        $stmt->execute($data);

        if ($stmt->fetch()) {
            $this->db->query("DELETE FROM pokemons WHERE id = '$id'");
            return true;
        }
        return false;
    }

    // Supprimer tous les pokémons de la base
    public function deleteAll() {
        $rq = "DELETE FROM pokemons";
        $this->db->query($rq);
    }
}
