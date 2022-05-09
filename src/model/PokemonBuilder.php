<?php

const NAME_REF = "name";
const TYPE_REF = "type";
const SIZE_REF = "size";
const WEIGHT_REF = "weight";
const AUTHOR_REF = "author";

class PokemonBuilder {
    private $data;
    private $error;
    private $path;

    public function __construct($data = null, $path = null) {
        if ($data === null) {
            $data = array(
                NAME_REF => "",
                TYPE_REF => "None",
                SIZE_REF => "",
                WEIGHT_REF => "",
            );
        }

        $this->data = $data;
        $this->error = array();
        $this->path = $path;
    }

    public function getData($ref) {
        return key_exists($ref, $this->data) ? $this->data[$ref] : "";
    }

    public function getError($ref) {
        return key_exists($ref, $this->error) ? $this->error[$ref] : null;
    }

    // Créer un pokémon avec l'échappement html
    public function createPokemon() {
        if (!$this->isValid())
            throw new Exception("Le formulaire n'est pas valide");
        return new Pokemon(MainView::htmlesc($this->data[NAME_REF]), $this->data[TYPE_REF], $this->data[SIZE_REF], $this->data[WEIGHT_REF], $_SESSION["user"]["login"], $this->path, Controller::getCurrentDate());
    }

    // Informations saisies par l'utilisateur valides ou non
    public function isValid() {
        if (!isset($this->data[NAME_REF]) || $this->data[NAME_REF] === "")
            $this->error[NAME_REF] = "Veuillez saisir un nom";
        else if (mb_strlen($this->data[NAME_REF], "UTF-8") >= 20)
            $this->error[NAME_REF] = "Veuillez saisir un nom de moins de 20 caractères";

        if ($this->data[TYPE_REF] === "None")
            $this->error[TYPE_REF] = "Veuillez saisir un type";

        if ($this->data[SIZE_REF] === "")
            $this->error[SIZE_REF] = "Veuillez saisir une taille";
        else if ($this->data[SIZE_REF] < 0)
            $this->error[SIZE_REF] = "Veuillez saisir une taille supérieure à zéro";

        if ($this->data[WEIGHT_REF] === "")
            $this->error[WEIGHT_REF] = "Veuillez saisir un poids";
        else if ($this->data[WEIGHT_REF] < 0)
            $this->error[WEIGHT_REF] = "Veuillez saisir un poids supérieur à zéro";
        return count($this->error) === 0;
    }
}
