<?php 

class Currency  extends Entity {

    public $code;
    public $name; 
    public $numcode; 

public function validate() {
    
    unset($_SESSION["error"]);

        if(empty($this->code))  
            $_SESSION["error"] = "Oznaka valute ne smije biti prazna !";
    
        if(empty($this->name)) 
            $_SESSION["error"] = "Naziv valute ne smije biti prazan !";

    return !isset($_SESSION["error"]);
}

}
?>