<?php
class GestionRecherche
{
    ///////atributs//////
    private $_database;
    ///constructeur//////
    public function __construct($bdd)
    {
        $this->setDataBase($bdd);
    }
    /////setter///////////
    public function setDataBase(PDO $bdd)
    {
        $this->_database = $bdd;
    }
    ///////////////////////////////////////
    public function getSearchResult($mot) //recuperer un doc avec l'id
    {
        $searchResult = [];
        $query = $this->_database->query("SELECT d.id idDoc, d.nom nomDoc, d.lien lienDoc, d.descriptions descriptionDoc, r.occurrence occurrence FROM docs d INNER JOIN relation r ON d.id = r.iddoc INNER JOIN (Select mot from mots where mot LIKE '" . $mot . "' )m ON r.mot = m.mot ORDER BY occurrence DESC");
        while ($donnes = $query->fetch(PDO::FETCH_ASSOC)) {
            $searchResult[] = $donnes;
        }
        return $searchResult;
    }
}
