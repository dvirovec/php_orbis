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

error_log($conn_str);

$_SESSION["db_conn"] = new PDO($conn_str, DB_USERNAME, DB_PASSWORD); 
$_SESSION["db_conn"]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$_SESSION["db_conn"]->exec('SET NAMES "utf8"');

$output = "Succesfully connected to databsae server !";
error_log($output);

}
catch(PDOException $e) {
    $output = "Unable to connect to database server !";
    error_log($output);
    exit();
}

$_SESSION["cid"] = 1;

if($_SESSION["db_conn"] === false) {
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

?>