<?php

$host = "localhost";
$user = "root";
$pass = "1412";
$dbname = "auth_portal";  

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
