<?php

define('DB_SERVER','localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME','habis');
define('DB_PORT',8889);

define('CMD_INSERT',            'INSERT INTO <table_name> (<columns>) VALUES (<attributes>);');
define('CMD_UPDATE',            'UPDATE <table_name> SET <columns> WHERE id = :id;');
define('CMD_DELETE',            'DELETE FROM <table_name> WHERE id = :id;');
define('CMD_SELECT_SINGLE_ROW', 'SELECT id, <columns> FROM <table_name> WHERE id = :id');
define('CMD_SELECT_ALL',        'SELECT id, <columns> FROM <table_name> WHERE cid = :cid;');

try {

$conn_str = "mysql:host=" . DB_SERVER . ";dbname=" .DB_NAME .";port=" . DB_PORT;    

$_SESSION["conn_str"] = $conn_str;
}
catch(PDOException $e) {
    $output = "Unable to connect to database server !";
    error_log($output);
    exit();
}

#$_SESSION["page"] = "home";
$_SESSION["cid"] = 1;

?>