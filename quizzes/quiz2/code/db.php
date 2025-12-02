<?php
$dbhost = "localhost";
$dbuser = "collej2";
$dbpass = "Quiz2";
$dbname = "ITWS2110-Fall2025-collej2-Quiz2";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Failed to connect to database");
}
?>