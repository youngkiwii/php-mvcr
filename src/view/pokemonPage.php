<?php

$this->title = constTitle . " | " . $pokemon->getName();
$this->titlecontent = $pokemon->getName();
$s = "<p>" . $pokemon->getName() . " est de type " . $pokemon->getType() . " et mesure " . $pokemon->getSize() . " cm et pèse " . $pokemon->getWeight() . " kg.</p>";
$s .= "<p id='signature'>Pokémon créé par " . $pokemon->getAuthor() . " le " . $pokemon->getDate() . "</p>";

// En fonction du rôle de l'utilisateur
if ($pokemon->getAuthor() === $_SESSION["user"]["login"] || $_SESSION["user"]["status"] === "admin") {
    $s .= "<ul>";
    $s .= "<li><a href=\"" . $this->router->pokemonAskDeletionURL(self::htmlesc($id)) . "\">Supprimer</a></li>";
    $s .= "<li><a href=\"" . $this->router->pokemonModifyURL(self::htmlesc($id)) . "\">Modifier</a></li>";
    $s .= "</ul>";
}

$s .= "<div id='img'></div>";

$this->content = $s;

$this->style = "
#img {
    text-align: center;
    background-image: url(" . $pokemon->getImage() . ");
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;
    background-color: #eeeeee;
    aspect-ratio: 1 / 1;
    margin-top: 30px;
    box-shadow: 0px 3px 10px gray;
    right: 30%;
    width: 40%;
    margin-left: auto;
    position: relative;
}

#signature{
    color: #777777;
}

";
