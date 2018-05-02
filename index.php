<!DOCTYPE html>

<html lang="hr">

<head>
    <title>Orbis</title>
    <meta charset="UTF-8">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>   
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>   
</head>

<body>



<?php
    require_once "system/config.php";
    require_once "model/entity.php";
    require_once "tools/form_elements.php";

    include "system/topmenu.html.php";

   if(!isset($_SESSION["number"]))
   $_SESSION["number"] = 1;
else {
   $_SESSION["number"] = $_SESSION["number"] + 1;
}

   ?>

<?php  

if(isset($_GET["country"])) {
    $_SESSION["page"] = "country";
}
if(isset($_GET["currency"])) {
    $_SESSION["page"] = "currency";
}
if(isset($_GET["company"])) {
    $_SESSION["page"] = "company";
}

if(isset($_GET["town"])) {
    $_SESSION["page"] = "town";
}

error_log($_SESSION["page"]);

if($_SESSION["page"]==="country") {
    include "view/country/country.html.php"; 
}
if($_SESSION["page"]==="currency") {
    include "view/currency/currency.html.php"; 
}
if($_SESSION["page"]==="company") {
    include "view/company/company.html.php"; 
}
if($_SESSION["page"]==="town") {
    include "view/town/town.html.php"; 
}

?>

</body>
</html>
