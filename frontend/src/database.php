<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "interior_design_project";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Something went wrong;");
}

?>