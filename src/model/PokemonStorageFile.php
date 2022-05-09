<?php

require_once("lib/ObjectFileDB.php");
require_once("model/Pokemon.php");
require_once("model/PokemonStorage.php");

class PokemonStorageFile implements PokemonStorage {

	private $db;

	public function __construct($file) {
		$this->db = new ObjectFileDB($file);
	}

	public function reinit() {
		$this->deleteAll();
	}

	public function create(Pokemon $c) {
		return $this->db->insert($c);
	}

	public function read($id) {
		if ($this->db->exists($id)) {
			return $this->db->fetch($id);
		}
		return null;
	}

	public function readAll() {
		return $this->db->fetchAll();
	}

	public function update($id, Pokemon $c) {
		if ($this->db->exists($id)) {
			$this->db->update($id, $c);
			return true;
		}
		return false;
	}

	public function exists($id) {
		return $this->read($id) !== null;
	}

	public function delete($id) {
		if ($this->db->exists($id)) {
			$this->db->delete($id);
			return true;
		}
		return false;
	}

	/* Vide la base. */
	public function deleteAll() {
		$this->db->deleteAll();
	}
}
