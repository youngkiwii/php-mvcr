<?php
$this->title = constTitle . " | Register";
$this->titlecontent = "Register";
$s = "<section id=\"formRegister\">";
$s .= "<h2>Formulaire d'inscription</h2>";
$s .= "<form method=\"POST\" action=\"" . $this->router->confirmRegisterPageURL() . "\">";
$s .= "<p><input type='text' name='name' placeholder=\"Pseudo\"></p>";
$s .= "<p><input type='text' name='login' placeholder=\"Nom d'utilisateur\"></p>";
$s .= "<p><input id='password' type='password' name='password' placeholder=\"Mot de passe\"></p>";
$s .= "<p><input id='confirmPass' type='password' name='confirmPass' placeholder=\"Confirmer le mot de passe\"></p>";
$s .= "<p><input id='submit' type='submit' value=\"S'inscrire\"></p>";
$s .= "</form>";
$s .= "</section>";
$this->content = $s;
$this->style = "
#formRegister p {
    margin-top: 20px;
}

#formRegister, #titlecontent{
    margin: auto;
    text-align: center;
}

.highlight{
    background-color: rgb(255,0,0,0.3);
}

section > h2{
    display: none;
}
";

$this->script = "<script defer src='/projet-inf5c-2022/scripts/register.js'></script>";
