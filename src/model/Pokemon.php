<?php

class Pokemon {
    private $name;
    private $type;
    private $size;
    private $weight;
    private $author;
    private $img;
    private $date;

    public function __construct($name, $type, $size, $weight, $author, $img, $date) {
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
        $this->weight = $weight;
        $this->author = $author;
        $this->img = $img;
        $this->date = $date;
    }

    public function getName() {
        return $this->name;
    }

    public function getSize() {
        return $this->size;
    }

    public function getType() {
        return $this->type;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getImage() {
        return $this->img;
    }

    public function getDate() {
        return $this->date;
    }
}
