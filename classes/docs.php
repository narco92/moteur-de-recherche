<?php
class Docs
{
    private $_id;
    private $_nom;
    private $_lien;
    private $_descriptions;
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
    public function id()
    {
        return $this->_id;
    }
    public function nom()
    {
        return $this->_nom;
    }
    public function lien()
    {
        return $this->_lien;
    }
    public function descriptions()
    {
        return $this->_descriptions;
    }
    //////////setter/////////////
    public function setId($id)
    {
        $id = (int) $id;
        if ($id > 0) {
            $this->_id = $id;
        }
    }
    public function setNom($nom)
    {
        if (is_string($nom)) {
            $this->_nom = $nom;
        }
    }
    public function setLien($lien)
    {
        if (is_string($lien)) {
            $this->_lien = $lien;
        }
    }
    public function setDescriptions($descriptions)
    {
        if (is_string($descriptions)) {
            $this->_descriptions  = $descriptions;
        }
    }
}
