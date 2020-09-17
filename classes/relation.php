<?php
class Relation
{
    private $_idDoc;
    private $_mot;
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
    public function mot()
    {
        return $this->_mot;
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
    public function setMot($mot)
    {
        if (is_string($mot)) {
            $this->_mot = $mot;
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
