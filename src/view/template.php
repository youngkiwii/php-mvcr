<!DOCTYPE html>
<html lang="fr">

<head>
    <title><?= $title ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/projet-inf5c-2022/style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <style>
        <?= $this->style ?>
    </style>
    <?= $this->script ?>
    <script defer src="/projet-inf5c-2022/scripts/hamburger.js"></script>
</head>

<body>
    <header>
        <h1 id="titre"><a href="/projet-inf5c-2022/"><?= constTitle ?></a></h1>
        <nav id="menu">
            <ul>
                <?php
                foreach ($menu as $name => $link) {
                    $id = explode("/", $link);
                    $id = $id[array_key_last($id)];
                    $submenu = "<ul class=\"sous-menu\"><li id=\"$id\"><a href=\"$link\">$name</a></li></ul>";

                    if ($id === "logout" && isset($_SESSION["user"]))
                        echo "<li class=\"menu-deroulant\"><a href=#>" . $_SESSION["user"]["name"] . "</a>$submenu</li>\n";
                    else
                        echo "<li id=\"" . $id . "\"><a href=\"$link\">$name</a></li>\n";
                }
                ?>
            </ul>
            <div id="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </nav>
    </header>
    <main id="main">
        <?php
        if ($this->feedback !== "")
            echo "<p id=\"feedback\">" . $this->feedback . "</p>";
        ?>
        <h1 id="titlecontent"><?= $titlecontent ?></h1>
        <?= $content ?>
    </main>

    <footer>
        <p id="footerText">© 2021 by Alex & Thomass. Technologies Web 2021</p>
    </footer>
</body>

</html>