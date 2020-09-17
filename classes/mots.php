<?php
class Mots
{
    private $_mot;
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
    public function mot()
    {
        return $this->_mot;
    }
    //////////setter/////////////
    public function setMot($mot)
    {
        if (is_string($mot)) {
            $this->_mot = $mot;
        }
    }
}
