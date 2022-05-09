<?php

require_once("model/TypesEnum.php");

$this->title = constTitle . " | Création";
$this->titlecontent = "Créez votre pokémon";
$s = "<section id=\"formCreation\">";
$s .= "<form id=\"form\" enctype='multipart/form-data' method=\"POST\" action=\"" . $this->router->savePageURL() . "\">";

// Nom
$s .= "<p><input type=\"text\" name=\"name\" placeholder=\"Saisissez un nom\" value=\"" . self::htmlesc($pb->getData(NAME_REF)) . "\">";
$err = $pb->getError(NAME_REF);
if ($err !== null)
    $s .= ' <span class="error">' . $err . '</span>';

// Type
$s .= "<select name=\"type\">";
foreach ($TypesEnum as $type) {
    if ($type === $pb->getData(TYPE_REF))
        $s .= "<option selected>" . $type . "</option>";
    else
        $s .= "<option>" . $type . "</option>";
}
$s .= "</select></p>";
$err = $pb->getError(TYPE_REF);
if ($err !== null)
    $s .= ' <span class="error">' . $err . '</span>';

// Taille
$s .= "<p><input type=\"number\" name=\"size\" placeholder=\"Taille (en cm)\" value=\"" . $pb->getData(SIZE_REF) . "\"></p>";
$err = $pb->getError(SIZE_REF);
if ($err !== null)
    $s .= ' <span class="error">' . $err . '</span>';

// Poids
$s .= "<p><input type=\"number\" name=\"weight\" placeholder=\"Poids (en kg)\" value=\"" . $pb->getData(WEIGHT_REF) . "\"></p>";
$err = $pb->getError(WEIGHT_REF);
if ($err !== null)
    $s .= ' <span class="error">' . $err . '</span>';

// Image
$s .= "<p id='imagePara'><label>Choisissez une image (" . ini_get("upload_max_filesize") . "o) : <input id='file' type='file' name='img'></label></p>";

// Bouton submit "créer"
$s .= "<p><input type=\"submit\" value=\"Créer\"></p>";
$s .= "</form>";
$s .= "</section>";

$this->content = $s;
$this->style = "#creation a{ 
    color: gray;
}

#formCreation {
    text-align: center;
}

#formCreation p{
    margin-top: 20px;
}

.error{
    color: red;
}
";

$this->script = "<script defer src='/projet-inf5c-2022/scripts/upload.js'></script>";
