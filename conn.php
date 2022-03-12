<?php 
$BD_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASSWORD = '';
$DB_NAME = 'medistation';

$conn = mysqli_connect($BD_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

if (!$conn) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>