<?php
class Recherche
{
    private $_idDoc;
    private $_nomDoc;
    private $_lienDoc;
    private $_descriptionDoc;
    private $_occurrence;
    /////////////////////////////
    public function __construct($donnes)
    {
        $this->hydrate($donnes);
    }
    /////////////////////////////
    public function hydrate(array $donnes)
    {
        foreach ($donnes as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
    ////////getter///////////////
    public function idDoc()
    {
        return $this->_idDoc;
    }
    public function nomDoc()
    {
        return $this->_nomDoc;
    }
    public function lienDoc()
    {
        return $this->_lienDoc;
    }
    public function descriptionDoc()
    {
        return $this->_descriptionDoc;
    }
    public function occurrence()
    {
        return $this->_occurrence;
    }
    //////////setter/////////////
    public function setIdDoc($idDoc)
    {
        $idDoc = (int) $idDoc;
        if ($idDoc > 0) {
            $this->_idDoc = $idDoc;
        }
    }
    public function setNomDoc($nomDoc)
    {
        if (is_string($nomDoc)) {
            $this->_nomDoc = $nomDoc;
        }
    }
    public function setLienDoc($lienDoc)
    {
        if (is_string($lienDoc)) {
            $this->_lienDoc = $lienDoc;
        }
    }
    public function setDescriptionDoc($descriptionDoc)
    {
        if (is_string($descriptionDoc)) {
            $this->_descriptionDoc = $descriptionDoc;
        }
    }
    public function setOccurrence($occurrence)
    {
        $occurrence = (int) $occurrence;
        if ($occurrence > 0) {
            $this->_occurrence = $occurrence;
        }
    }
}
