<?php
class GestionRlations
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
    public function existe($id, $mot)
    {
        $id = (int) $id;
        $query = $this->_database->prepare("SELECT COUNT(iddoc) AS nbr FROM relation WHERE iddoc = :iddoc AND mot=:mot ");
        $query->bindValue(':iddoc', $id, PDO::PARAM_INT);
        $query->bindValue(':mot', $mot, PDO::PARAM_STR);
        $query->execute();
        if ($output = $query->fetch(PDO::FETCH_ASSOC)) {
            $query->closeCursor();
            if ($output['nbr'] == 0) return false;
            return true;
        }
    }
    public function getRelation($idDoc, $mot)
    {
        $idDoc = (int) $idDoc;
        $query = $this->_database->prepare('SELECT idDoc, mot, occurrence FROM relation WHERE idDoc=:idDoc AND Mot=:mot');
        $query->bindValue(':idDoc', $idDoc, PDO::PARAM_INT);
        $query->bindValue(':mot', $mot, PDO::PARAM_STR);
        $query->execute();
        $donnes = $query->fetch(PDO::FETCH_ASSOC);
        return new Docs($donnes);
    }
    public function getwordsText($idDoc)
    {
        $wordsText = [];
        $idDoc = (int) $idDoc;
        $query = $this->_database->prepare('SELECT idDoc, mot, occurrence FROM relation WHERE idDoc=:idDoc');
        $query->bindValue(':idDoc', $idDoc, PDO::PARAM_INT);
        $query->execute();
        while ($donnes = $query->fetch(PDO::FETCH_ASSOC)) {
            $wordsText[] = $donnes;
        }
        return $wordsText;
    }
    public function getListRelations()
    {
        $rels = [];
        $query = $this->_database->query('SELECT idDoc, mot, occurrence FROM relation');
        while ($donnes = $query->fetch(PDO::FETCH_ASSOC)) {
            $rels[] = $donnes;
        }
        return $rels;
    }
    public function addRelation(Relation $rel)
    {
        $query = $this->_database->prepare('INSERT INTO relation(idDoc, mot, occurrence)VALUES(:idDoc, :mot, :occurrence)');
        $query->bindValue(':idDoc', $rel->idDoc(), PDO::PARAM_INT);
        $query->bindValue(':mot', $rel->mot(), PDO::PARAM_STR);
        $query->bindValue(':occurrence', $rel->occurrence(), PDO::PARAM_STR);
        $query->execute();
    }
    public function updateRelation(Relation $rel)
    {
        $query = $this->_database->prepare('UPDATE docs SET occurence=:occurence WHERE idDoc=:idDoc AND idMot=:idMot');
        $query->bindValue(':occurence', $rel->occurence(), PDO::PARAM_STR);
        $query->bindValue(':idDoc', $rel->idDoc(), PDO::PARAM_INT);
        $query->bindValue(':mot', $rel->mot(), PDO::PARAM_INT);
        $query->execute();
    }
    public function deleteRelation(Relation $rel)
    {
        $query = $this->_database->prepare('DELETE FROM relation WHERE idDoc=:idDoc AND idMot=:idMot');
        $query->bindValue(':idDoc', $rel->idDoc(), PDO::PARAM_INT);
        $query->bindValue(':idMot', $rel->idMot(), PDO::PARAM_INT);
        $query->execute();
    }
}
