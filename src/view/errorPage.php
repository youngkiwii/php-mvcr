<?php

$this->title = constTitle . " | Error";
$this->titlecontent = "Erreur URL";
$this->content = "<p>Chemin \"" . self::htmlesc($url) . "\" erroné.</p>";

?>