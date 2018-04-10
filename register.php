<?php 

require "model/userprofile.php";

$profile = new UserProfile();
$profile->email = $_POST["email"];
$profile->username = $_POST["username"];
$profile->firstname = $_POST["firstname"];
$profile->lastname = $_POST["lastname"];
$profile->passwd = $_POST["passwd"];

?>