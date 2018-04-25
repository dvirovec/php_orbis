<?php 


class Country extends Entity {
    
    public $code;
    public $name;
    public $currencyid;

    function __construct() {
        
        parent::__construct();
        
        $this->setSelectAllSQL("SELECT c.id, c.cid, c.code, c.name, u.id currencyid, u.code currency_code 
        FROM country c LEFT JOIN currency u ON u.id = c.currencyid WHERE c.cid = :cid");
        
        $this->setSelectSQL("SELECT c.id, c.cid, c.code, c.name, u.id currencyid, u.code currency_code 
        FROM country c LEFT JOIN currency u ON u.id = c.currencyid WHERE c.id = :id");
    }
}

?>