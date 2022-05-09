<?php

$this->title = constTitle;
$this->titlecontent = "Les pokémons de doubi et ramchez";
$this->content = "<section>";
$this->content .= "<h2>Créez votre pokémon</h2>";
$this->content .= "<a id='loginButton' href='" . $this->router->loginPageURL() . "'>Se connecter</a>";
$this->content .= "<a id='aboutButton' href='" . $this->router->proposPageURL() . "'>À propos</a>";
$this->content .= "</section>";
$this->style = "#projet-inf5c-2022 a{ 
        color: gray;
    }
    
    body{
        background: linear-gradient(45deg, #c19a9a, #dbc0c0);
    }

    #titlecontent{
        display: none;
    }

    section{
        text-align: center;
        margin-top: 20%;
    }

    h2{
        margin: auto;
        text-transform: uppercase;
        width: 25%;
        font-size: 55px;
    }
    
    #loginButton:hover{
        background-color: #555;
    }

    #aboutButton:hover{
        background-color: #eee;
    }

    section > a {
        border-radius: 15px;
        display: inline-block;
        padding: 10px 0;
        width: 140px;
        margin: 0 5px;
    }

    #loginButton{
        background-color: black;
        color: white;
    }

    #aboutButton{
        background-color: white;
        color: black;
    }

    #main{
        color: white;
    }

    #feedback{
        color: #b64444;
    }
    ";
