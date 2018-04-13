<?php

define('DB_SERVER','localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME','habis');
define('DB_PORT',8889);

$_SESSION["db_conn"] = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

if($_SESSION["db_conn"] === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}


?>