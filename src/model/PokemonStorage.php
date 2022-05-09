<?php

require_once("Pokemon.php");

interface PokemonStorage {
    
    public function reinit();

    public function create(Pokemon $p);

    public function read($id);

    public function readAll();

    public function exists($id);

    public function update($id, Pokemon $p);

    public function delete($id);

    public function deleteAll();
}
