<?php

require_once "entity.php";

class Company extends Entity {
  
    public $name;
    public $vatnumber;  
    public $address;
    public $address_ext;
    public $townid;

    function __construct() {
        
        parent::__construct();
       
        $this->setSelectSQL("SELECT c.id, c.cid, c.vatnumber, c.name, c.address, c.address_ext, t.id townid, t.name townname
                    FROM company c LEFT OUTER JOIN town t ON t.id =  c.townid
                    WHERE c.id = :id");

        $this->setSelectAllSQL("SELECT c.id, c.cid, c.vatnumber, c.name, c.address, c.address_ext, t.id townid, t.name townname
                                FROM company c LEFT OUTER JOIN town t ON t.id =  c.townid
                                WHERE  c.cid = :cid");
        }

 public function validate() {

    unset($_SESSION["error"]);

    if(empty($this->name))
        $_SESSION["error"] = "Naziv poduzeća ne može biti prazan!";

    if(empty($this->vatnumber))
        $_SESSION["error"] = "OIB ne može biti prazan!";
        
    if(strlen($this->vatnumber)<11)
        $_SESSION["error"] = "OIB mora biti dugačak 11 znakova!";

    if(!is_numeric($this->vatnumber))
        $_SESSION["error"] = "OIB ne može sadržavati slova i posebne znakove!";


    return !isset($_SESSION["error"]);
      
 }

}

?>