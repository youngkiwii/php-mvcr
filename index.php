<?php
set_include_path("./src");

require_once("model/PokemonStorageMySQL.php");
require_once("Router.php");
require_once("/users/21906389/private/mysql_config.php");

// Base de données PDO en MySQL
$db = new PDO("mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_BD . ";charset=utf8mb4", MYSQL_USER, MYSQL_PASSWORD);

// Initialisation du router avec une base de données et le management des authentifications
$r = new Router(new PokemonStorageMySQL($db), new AuthenticationManager($db));
$r->main();
