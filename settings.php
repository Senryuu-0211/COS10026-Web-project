<?php
    $host = "feenix-mariadb.swin.edu.au";
    $user = "s104993201";
    $pwd = "060605";
    $sql_db = "s104993201_db";
?>

<?php

$hostName = "feenix-mariadb.swin.edu.au";
$dbUser = "s104993405";
$dbPassword = "021105";
$dbName = "s104993405_db";
$connect = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$connect) {
    die("Something went wrong;");
}

?>