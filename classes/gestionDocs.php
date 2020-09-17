<?php
class GestionDocs
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
    public function getDoc($id) //recuperer un doc avec l'id
    {
        $id = (int) $id;
        $query = $this->_database->query('SELECT id, nom, lien, descriptions FROM docs WHERE id=' . $id);
        $donnes = $query->fetch(PDO::FETCH_ASSOC);
        return new Docs($donnes);
    }
    public function getListDocs() //retourne la liste des docs 
    {
        $docs = [];
        $query = $this->_database->query('SELECT id, nom, lien, descriptions FROM docs');
        while ($donnes = $query->fetch(PDO::FETCH_ASSOC)) {
            $docs[] = $donnes;
        }
        return $docs;
    }
    public function addDoc(Docs $doc) //ajoute un doc a l bdd
    {
        $query = $this->_database->prepare('INSERT INTO docs(nom, lien, descriptions )VALUES(:nom, :lien, :descriptions )');
        //$query->bindValue(':id', $doc->id(), PDO::PARAM_INT);
        $query->bindValue(':nom', $doc->nom(), PDO::PARAM_STR);
        $query->bindValue(':lien', $doc->lien(), PDO::PARAM_STR);
        $query->bindValue(':descriptions', $doc->descriptions(), PDO::PARAM_STR);
        $query->execute();
    }
    public function updateDoc(Docs $doc) //mis ajour d'un document
    {
        $query = $this->_database->prepare('UPDATE docs SET nom=:nom, lien=:lien descriptions =:descriptions  WHERE id=:id');
        $query->bindValue(':nom', $doc->nom(), PDO::PARAM_STR);
        $query->bindValue(':lien', $doc->lien(), PDO::PARAM_STR);
        $query->bindValue(':descriptions ', $doc->descriptions(), PDO::PARAM_STR);
        $query->bindValue(':id', $doc->id(), PDO::PARAM_INT);
        $query->execute();
    }
    public function deleteDoc($id) //suprime un doc
    {
        $this->_database->exec('DELETE FROM docs WHERE id=' . $id);
    }
}
