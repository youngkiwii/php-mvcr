<?php

$this->title = constTitle . " | Galerie";
$this->titlecontent = "La liste de tous les pokémons";
$s = "<input id='search' type='text' placeholder='Rechercher un pokémon'>";
$s .= "<section>";
$s .= "<h2>Liste</h2>";
foreach ($list as $id => $pokemon) {
    $s .= "<div class='cells' id='" . hash("md5", $id) . "'>";
    $s .= "<a href=\"" . $this->router->pokemonPageURL(self::htmlesc($id)) . "\"></a>";
    $s .= "<label class='pokemons'>" . $pokemon->getName() . "</label>";
    $s .= "</div>";
}
$s .= "</section>";
$this->content = $s;
$this->style = "#gallery a{ 
    color: gray;
}

section {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    grid-gap: 50px 20px;
    transition: all 0.3s ease-in-out;
    -webkit-transition: all 0.3s ease-in-out;
}

section > h2 {
    display: none;
}

.cells {
    text-align: center;
    background-color: #eeeeee;
    aspect-ratio: 1 / 1;
    position: relative;
    box-shadow: 0px 3px 10px gray;
}

.cells > a {
    display: inline-block;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
}

label{
    margin: 0;
    position: absolute;
    top: 110%;
    left: 50%;
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
}

a{
    color: black;
}

#search{
    margin-bottom: 10px;
}

.hidden{
    display: none;
}

@media(max-width:1150px){
    section{
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 80px;
    }

    main{
        padding-bottom: 100px;    
    }
}

@media(max-width:900px){
    section{
        grid-template-columns: 1fr;
        grid-gap: 80px;
    }

    label{
        top: 103%;
    }

    main{
        padding-bottom: 100px;    
    }
}
";

foreach ($list as $id => $pokemon) {
    $this->style .= "
    [id=\"" . hash("md5", $id) . "\"]{
        background-image: url('" . $pokemon->getImage() . "');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    } 
    ";
}


$this->script = "<script defer src='/projet-inf5c-2022/scripts/searchbar.js'></script>";
