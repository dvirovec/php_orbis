<?php

require_once "entity.php";

class UserProfile extends Entity {
            
    public $email;
    public $username;
    public $firstname;
    public $lastname;
    public $passwd;

    function __construct() {
        parent::__construct("sssss");     
    }
}
?>