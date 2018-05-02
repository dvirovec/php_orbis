<?php 

require_once "entity.php";

  class Town extends Entity {

    public $name;    
    public $countryid;

function __construct() {
        
  parent::__construct();
 
  $this->setSelectSQL("SELECT t.id, t.cid, t.name, t.countryid, 
  c.name countryname FROM town t
  LEFT OUTER JOIN country c ON c.id = t.countryid 
  WHERE t.id = :id");

  $this->setSelectAllSQL("SELECT t.id, t.cid, t.name, t.countryid, 
          c.id countryid, c.name countryname FROM town t
          LEFT OUTER JOIN country c ON c.id = t.countryid 
          WHERE t.cid = :cid");
  }

public function validate() {

unset($_SESSION["error"]);

if(empty($this->name))
  $_SESSION["error"] = "Naziv mjesta ne može biti prazan!";
  
return !isset($_SESSION["error"]);

}

}

?>