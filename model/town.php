<?php 

require_once "entity.php";

  class Town extends Entity {

    public $name;    
    public $country;

    function __construct() {
        parent::__construct("ss");        
    }
}

?>