<?php

require_once "entity.php";

class Company extends Entity {
  
    public $name;
    public $vatnumber;  
    public $address;
    public $townid;

    function __construct() {
        parent::__construct("sssi");        
    }
}

?>