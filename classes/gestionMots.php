<?php
class GestionMots
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
    public function existe($mot)
    {
        $query = $this->_database->prepare("SELECT COUNT(mot) AS nbr FROM mots WHERE mot = :mot");
        $query->bindValue(':mot', $mot);
        $query->execute();
        if ($output = $query->fetch(PDO::FETCH_ASSOC)) {
            $query->closeCursor();
            if ($output['nbr'] == 0) return false;
            return true;
        }
    }

    public function getmot($mot)
    {
        $query = $this->_database->query('SELECT mot FROM mots WHERE mot=' . $mot);
        $donnes = $query->fetch(PDO::FETCH_ASSOC);
        return new Mots($donnes);
    }
    public function getListMots()
    {
        $mots = [];
        $query = $this->_database->query('SELECT mot FROM mots');
        while ($donnes = $query->fetch(PDO::FETCH_ASSOC)) {
            $mots[] = $donnes;
        }
        return $mots;
    }
    public function addMot(Mots $mot)
    {
        $query = $this->_database->prepare('INSERT INTO mots(mot)VALUES(:mot)');
        $query->bindValue(':mot', $mot->mot(), PDO::PARAM_STR);
        $query->execute();
    }
    public function updateDoc(Mots $mot)
    {
        $query = $this->_database->prepare('UPDATE mots SET mot=:mot WHERE mot=:motv');
        $query->bindValue(':mot', $mot->mot(), PDO::PARAM_STR);
        $query->bindValue(':motv', $mot->mot(), PDO::PARAM_INT);
        $query->execute();
    }
    public function deleteDoc(Mots $mot)
    {
        $this->_database->exec('DELETE FROM docs WHERE id=' . $mot->id());
    }
}
