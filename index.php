<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/d219430b1f.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="js/style.js"></script>
    <title>SEARCH DOC'S</title>
</head>

<body>
    <main class="top">
        <h1>SEARCH DOC'S</h1>
        <form action="" method="get" class="SearchBox">
            <input type="text" class="SearchBox-input" placeholder="RECHERCHE" name="search">

            <button class="SearchBox-button">
                <i class="SearchBox-icon  material-icons">search</i>
            </button>
        </form>
        <?php
        require_once('spellchecker.php');
        require_once('connexion.php');
        require_once('function.php');
        //auto upload classes ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        spl_autoload_register(function ($class) {
            require 'classes/' . $class . '.php';
        });
        $gestRel = new GestionRlations($bdd);
        $gesRechrche = new GestionRecherche($bdd);
        if (isset($_GET['search'])) {
            if (!empty($_GET['search'])) {
                if (!empty(nl2br(correct_text(stripslashes($_GET['search']))))) {
                    echo nl2br(correct_text(stripslashes($_GET['search']))) . '<br /><br />';
                }

                $listeResult = $gesRechrche->getSearchResult($_GET['search']);
                if (count($listeResult) > 0) {
                    foreach ($listeResult as $ligne) {
                        $resultSerch = new Recherche($ligne);
                        ?>
                        <div id="searchResult">
                            <h3><a href="<?php echo $resultSerch->lienDoc(); ?>"><?php echo $resultSerch->nomDoc(); ?></a></h3>
                            <p><?php echo $resultSerch->descriptionDoc(); ?></p>
                            <em> <?php echo $_GET['search'] . "(" . $resultSerch->occurrence() . ") "; ?></em><span onclick="cloud(<?php echo $resultSerch->idDoc() ?>)"><i id="cloud" class="fas fa-cloud"></i></span>
                            <div id="<?php echo $resultSerch->idDoc() ?>" class="tagcloud" hidden><?php echo genererNuage($gestRel, $resultSerch->idDoc()) ?></div>

                        </div>
        <?php
                    }
                } else {
                    echo "aucun resultat trouver";
                }
            } else {
                echo "veuiilez entrer le mot a rechercher";
            }
        }
        ?>

    </main>



</html>