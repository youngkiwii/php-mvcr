<?php
$this->title = constTitle . " | Login";
$this->titlecontent = "Login";
$s = "<section id=\"formLogin\">";
$s .= "<h2>Formulaire de connexion</h2>";
$s .= "<form method=\"POST\" action=\"" . $this->router->confirmAuthPageURL() . "\">";
$s .= "<p><input type='text' name='login' placeholder=\"Nom d'utilisateur\"></p>";
$s .= "<p><input type='password' name='password' placeholder=\"Mot de passe\"></p>";
$s .= "<p><input type='submit' value='Se connecter'>\n";
$s .= "<a href='" . $this->router->registerPageURL() . "'>S'inscrire</a></p>";
$s .= "</form>";
$s .= "</section>";
$this->content = $s;
$this->style = "
#login a{
    color: gray;
}

#formLogin p {
    margin-top: 20px;
}

#formLogin, #titlecontent{
    margin: auto;
    text-align: center;
}

section > h2{
    display: none;
}
";
