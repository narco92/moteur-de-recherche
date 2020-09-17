<?php
//////////////////////////////////////////////////////////////
///////////////// recuperer le titre /////////////////////////
//////////////////////////////////////////////////////////////
function getTitle($html)
{
    $mavar = file_get_contents($html);
    preg_match('#<title[^>]*?>(.*)</title>#s', $mavar, $sortie);
    return $sortie[1];
}
//////////////////////////////////////////////////////////////
///////////////// recuperer les keywords /////////////////////
//////////////////////////////////////////////////////////////
function get_meta_keyWords($html)
{
    $tab_metas = get_meta_tags($html);
    if (isset($tab_metas['keywords'])) {
        return $tab_metas['keywords'];
    }
}
//////////////////////////////////////////////////////////////
/////////////// recuperer la descriptioon ////////////////////
//////////////////////////////////////////////////////////////
function get_meta_description($html)
{
    $tab_metas = get_meta_tags($html);
    if (isset($tab_metas['description'])) {
        return $tab_metas['description'];
    }
}
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
function getHead($html)
{
    $title = getTitle($html);
    $keywords = get_meta_keyWords($html);
    $description = get_meta_description($html);
    return $title . " " . $keywords . " " . $description;
}
//////////////////////////////////////////////////////////////
///////////////// recuperer le body //////////////////////////
//////////////////////////////////////////////////////////////
function getBody($html)
{
    $mavar = file_get_contents($html);
    preg_match('#<body[^>]*?>(.*)</body>#is', $mavar, $sortie);
    $noScript = preg_replace('#<script[^>]*?>.*?</script>#si', '', $sortie[1]);
    $noHtmlSpecialChars = html_entity_decode($noScript);
    $text = strip_tags($noHtmlSpecialChars);
    return $text;
}
//////////////////////////////////////////////////////////////
//////////////// decouper un string //////////////////////////
//////////////////////////////////////////////////////////////
function explodebis($separateur, $texte)
{
    $token = correct_encoding(strtok($texte, $separateur));
    if (strlen($token) > 2) {
        $tab_elements[] = $token;
    }
    while ($token = strtok($separateur)) {
        if (strlen($token) > 2) {
            $tab_elements[] = $token;
        }
    }
    return $tab_elements;
}
//////////////////////////////////////////////////////////////
//////////////////// coefession mot //////////////////////////
//////////////////////////////////////////////////////////////
function arrayCoef($tab)
{
    $endTab = [];
    foreach ($tab as $key => $value) {
        $endTab[$key] = $value * 10;
    }
    return $endTab;
}
//////////////////////////////////////////////////////////////
/////// ajouter a la base de donner avec verefication  ///////
//////////////////////////////////////////////////////////////
function addTobdd(GestionMots $gesMots, GestionRlations $gesRel, $tab, $id)
{
    foreach ($tab as $key => $Value) {
        $mot = new Mots(["mot" => $key]);
        if (!($gesMots->existe($key))) {
            $gesMots->addMot($mot);
        }
        $rel = new Relation(["iddoc" => $id, "mot" => $key, "occurrence" => $Value]);
        if (!($gesRel->existe($id, $key))) {
            $gesRel->addRelation($rel);
        }
    }
}
//////////////////////////////////////////////////////////////
///////////// fonction qui regroupe deux tableaux ////////////
//////////////////////////////////////////////////////////////
function fusion($tab1, $tab2)
{
    foreach ($tab1 as $key => $value) {
        if (isset($tab2[$key])) {
            $tab2[$key] += $value;
        } else {
            $tab2[$key] = $tab1[$key];
        }
    }
    return $tab2;
}
function fusion1()
{
    $result = [];
    foreach (func_get_args() as $array) {

        foreach ($array as $key => $value) {
            $result[$key] += $value;
        }
    }
    return $result;
}
//////////////////////////////////////////////////////////////
///// fonction qui afiche un tableau dans une page html //////
//////////////////////////////////////////////////////////////
function print_tab($tab)
{
    foreach ($tab as $key => $value)
        echo "$key : $value <br>";
}
function correct_encoding($String)
{
    $encoding = mb_detect_encoding($String, mb_detect_order(), false);

    if ($encoding != "UTF-8") {
        $String = mb_convert_encoding($String, 'UTF-8', 'UTF-8');
    }
    return $String;
}
function genererNuage(GestionRlations $gesRel, $id)
{
    $data = [];
    $donnees = $gesRel->getwordsText($id);
    foreach ($donnees as $rel) {
        $data[$rel['mot']] = $rel['occurrence'];
    }
    $minFontSize = 10;
    $maxFontSize = 50;
    $tab_colors = ["#808080", "#FF0000", "#800000", "#00FF00", "#008000", "#000FFF", "#0000FF", "#FF00FF", "#800080", "#000080"];
    $minimumCount = min(array_values($data));
    $maximumCount = max(array_values($data));
    $spread = $maximumCount - $minimumCount;
    $cloudTags = [];
    $spread == 0 && $spread = 1;
    //Mélanger un tableau de manière aléatoire
    srand((float) microtime() * 1000000);
    $mots = array_keys($data);
    shuffle($mots);
    foreach ($mots as $tag) {
        $count = $data[$tag];
        //La couleur aléatoire
        $color = rand(0, count($tab_colors) - 1);
        $size = $minFontSize + ($count - $minimumCount) * ($maxFontSize - $minFontSize) / $spread;
        $cloudTags[] = '<a style="font-size: ' . floor($size) . 'px' . '; color:' . $tab_colors[$color] . '; " title="Rechercher le tag ' . $tag . '" href="?search=' . urlencode($tag) . '">' . $tag . '</a>';
    }
    return join("\n", $cloudTags) . "\n";
}
