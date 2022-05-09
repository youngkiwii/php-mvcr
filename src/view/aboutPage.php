<?php

$this->title = constTitle . " | À propos";
$this->titlecontent = "À propos";

$s = "<section>";
$s .= "<h2>Présentation</h2>";
$s .= "<p>Groupe 8</p>";
$s .= "<p>Alex DURAND - 21906389</p>";
$s .= "<p>Thomass MONTAINE - 21307449</p>";
$s .= "</section>";

$s .= "<section>";
$s .= "<h2>Compléments</h2>";
$s .= "<p>Points réalisés :</p>";
$s .= "<ul>";
$s .= "<li>Un objet peut être illustré par une images (modifiables), uploadées par le créateur de l'objet et l'upload de cette image a une barre de progression (label avec un pourcentage).</li>";
$s .= "<li>Une recherche d'objets</li>";
$s .= "<li>Site responsive</li>";
$s .= "</ul>";
$s .= "</section>";

$s .= "<section>";
$s .= "<h2>Répartition des tâches</h2>";
$s .= "<p>Alex :</p>";
$s .= "<ul>";
$s .= "<li>Création de compte</li>";
$s .= "<li>Galerie</li>";
$s .= "<li>Modifier ou supprimer un pokémon</li>";
$s .= "<li>Style CSS (avec l'aide de Thomass)</li>";
$s .= "<li>JavaScript</li>";
$s .= "<li>Compléments (avec l'aide de Thomass)</li>";
$s .= "</ul>";

$s .= "<p>Thomass :</p>";
$s .= "<ul>";
$s .= "<li>Ajout d'un pokémon</li>";
$s .= "<li>Connexion d'un utilisateur (sessions)</li>";
$s .= "<li>Page de pokémon</li>";
$s .= "</ul>";
$s .= "</section>";

$s .= "<section>";
$s .= "<h2>Choix</h2>";
$s .= "<p>Design :</p>";
$s .= "<ul>";
$s .= "<li>Affection particulière pour les designs épurés noirs et blancs</li>";
$s .= "<li>Boutons très simples sans cadres (sauf l'accueil)</li>";
$s .= "<li>Choix hésitant d'un pourcentage à la place de la barre pour rester dans la sobriété</li>";
$s .= "</ul>";
$s .= "</section>";

$this->content = $s;

$this->style = "
#apropos a{ 
    color: gray;
}

section{
    margin: 20px 0;
}

";
